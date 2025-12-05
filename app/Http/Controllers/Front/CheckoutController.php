<?php

namespace App\Http\Controllers\Front;

use App\Events\OrderCreated;
use App\Exceptions\InvalidOrderException;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\Cart\CartRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Intl\Countries;
use Throwable;

class CheckoutController extends Controller
{
    public function create(CartRepository $cart)
    {
        if ($cart->get()->count() == 0) {
            // throw new InvalidOrderException('Cart is empty');
        }
        return view('front.checkout', [
            'cart' => $cart,
            'countries' => Countries::getNames(),
        ]);
    }

    public function store(Request $request, CartRepository $cart)
    {
        $request->validate([
            'addr.billing.first_name' => ['required', 'string', 'max:255'],
            'addr.billing.last_name' => ['required', 'string', 'max:255'],
            'addr.billing.email' => ['required', 'string', 'max:255'],
            'addr.billing.phone_number' => ['required', 'string', 'max:255'],
            'addr.billing.city' => ['required', 'string', 'max:255'],
        ]);
        $items = $cart->get()->groupBy('product.store_id')->all();

        // dd($items);

        /* the output will be something like this:
            array:2 [▼ // app\Http\Controllers\Front\CheckoutController.php:21
            4 => Illuminate\Database\Eloquent\Collection {#1063 ▼
                        #items: array:2 [▼
                        0 => 
                            App\Models\Cart {#1072 ▶}
                        1 => 
                            App\Models\Cart {#1073 ▶}
                        ]
                        #escapeWhenCastingToString: false
                }
            8 => Illuminate\Database\Eloquent\Collection {#1068 ▼
                        #items: array:1 [▼
                        0 => 
                            App\Models\Cart {#1074 ▶}
                        ]
                        #escapeWhenCastingToString: false
                }
            ]
        */

        /**
         * [Database Transaction]– Critical for Data Consistency
         *
         * When multiple related database operations must succeed together
         * (e.g., creating Order + Order Items + Addresses + deducting stock + charging payment),
         * we wrap them in a transaction.
         *
         * Goal:
         * → All operations succeed → commit
         * → Any operation fails → rollback everything
         *
         * example:
         * If OrderItem creation fails after Order was saved → rollback deletes the Order too.
         */

        // DB Transaction → All or nothing
        // Ensures data consistency: if any query fails → rollback everything

        // DB::connection('custom_connection_name_in_config')->beginTransaction();
        DB::beginTransaction(); // Default connection

        /**
         * How [Database Transactions] Work in Laravel (The Auto-Commit Secret)
         *
         * By default: Every single query is auto-committed immediately.
         * → Laravel (and MySQL/PostgreSQL) executes each INSERT/UPDATE instantly.
         *
         * DB::beginTransaction():
         * → Disables auto-commit mode
         * → All queries become "pending" – nothing is saved permanently yet
         *
         * DB::commit():
         * → "Apply all changes now" – makes everything permanent
         *
         * DB::rollBack():
         * → "Cancel everything" – undoes all pending changes
         */

        $orders = new Collection();
        try {
            foreach ($items as $store_id => $cart_items) {

                $order = Order::create([
                    'store_id' => $store_id,
                    'user_id' => Auth::id(),
                    'payment_method' => 'cod',
                ]);

                foreach ($cart_items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name,
                        'price' => $item->product->price,
                        'quantity' => $item->quantity,
                    ]);
                }

                foreach ($request->post('addr') as $type => $address) {
                    $address['type'] = $type; // add 'type' key to address array
                    $order->addresses()->create($address); // 
                }

                $orders->push($order);
            }
            // dd($orders);
            DB::commit();

            /**
             * Fire OrderCreated Event – The Clean & Scalable Way
             *
             * After successfully creating an order, we don't clutter the CheckoutController with unrelated responsibilities:
             *   • Clear cart
             *   • Decrease product stock
             *   • Send notification / email
             *
             * Each of these is a separate concern → Single Responsibility Principle.
             *
             * Solution: Event → Listener Architecture
             *
             * 1. event(new OrderCreated($order))
             *    → Acts as a "global trigger: "Hey, an order was just created!"
             *
             * 2. Multiple Listeners subscribe to this event (in EventServiceProvider):
             *    → ClearCartListener
             *    → DecreaseStockListener
             *    → SendOrderConfirmationNotification
             *    → GenerateInvoiceListener
             *    → SyncWithWarehouseListener
             *
             * Benefits:
             * • Controller stays thin and focused
             * • Each listener has one job → easy to test & maintain
             * • Add/remove features without touching checkout logic
             * • Works perfectly with queued listeners (async processing)
             *
             * This is Laravel's official & recommended pattern for complex workflows.
             * 
             * then: Bind the OrderCreated event to a listeners in EventServiceProvider → app/Providers/EventServiceProvider.
             */

            // Leagacy Code :
            // foreach ($orders as $order) {
            //     event('order.created', $order, Auth::user());
            // }
            event(new OrderCreated($orders, Auth::user()));
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect()->route('cart.index')->with('success', 'Order placed successfully');
        // return redirect()->route('orders.payments.create', $order->id);
    }
}
