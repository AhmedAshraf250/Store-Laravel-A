<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Facades\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Throwable;

class DeductProductQuantity
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    // public function handle($orders, $user = null)
    // {
    //     // foreach (Cart::get() as $item) {
    //     //     // Product::where('id', '=', $item->product_id)->decrement('quantity', $item->quantity);
    //     //     // OR
    //     //     Product::where('id', '=', $item->product_id)->update([
    //     //         'qunttity' => DB::raw('ququntity - ' . $item->quantity)
    //     //     ]);
    //     // }

    //     foreach ($orders->products as $product) {
    //         $product->decrement('quantity', $product->pivot->quantity);
    //     }
    // }

    public function handle(OrderCreated $event)
    {
        // Leagacy code || الكود شغال عادى بس صعب قرائته اللى تحت اوضح 
        // $event->orders->loadMissing('products');
        // foreach ($event->orders as $order) {
        //     foreach ($order->products as $product) {
        //         $quantity = $product->pivot->quantity; // the quantity which the agent ordered
        //         $product->decrement('quantity', $quantity);
        //     }
        // }
        // ====***====
        // $orders >> Order Model have "items" relation >> Returns OrderItem Model which have "product" relation >> Returns Product Model
        // Order Model >> OrderItem Model >> Product Model
        // loadMissing() to load the missing relationships [eager loading] seems to with() method
        try {
            $event->orders->loadMissing('items.product');
            foreach ($event->orders as $order) {
                foreach ($order->items as $item) {
                    $item->product->decrement('quantity', $item->quantity);
                }
            }
        } catch (Throwable $e) {
        }
    }
}

// "App\Models\Order" :

    // public function products()
    // {
    //     return $this->belongsToMany(
    //         Product::class,  // The related model is Product::class because this relation returns products.
    //         'orders_items',  // pivot table
    //         'order_id',      // FK in Pivot table for the Current Model
    //         'product_id',    // FK in Pivot table for the Related Model
    //         'id',            // PK Current Model
    //         'id'             // PK Related Model
    //     )
    //         ->using(OrderItem::class) // to use 'using' method (OrderItem::class) must exten from "Pivot"
    //         ->withPivot(['product_name', 'price', 'quantity', 'options']);
    // }

// --------------------------

    //  dd($orders->products);   || OUTPUT:
    /*
        Illuminate\Database\Eloquent\Collection {#1086 ▼ // app\Listeners\DeductProductQuantity.php:32
        #items: array:2 [▼


            0 => App\Models\Product {#1098 ▼
            #connection: "mysql"
            #table: "products"
            #primaryKey: "id"
            #keyType: "int"
            +incrementing: true
            #with: []
            #withCount: []
            +preventsLazyLoading: false
            #perPage: 15
            +exists: true
            +wasRecentlyCreated: false
            #escapeWhenCastingToString: false
            #attributes: array:17 [▼
                "id" => 93
                "store_id" => 5
                "category_id" => 1
                "name" => "Gorgeous Wooden Hat"
                "slug" => "gorgeous-wooden-hat"
                "description" => "Qui voluptatem qui quis dolorum repellat quia accusamus asperiores nihil tempora laborum."
                "image" => ""
                "price" => 68.6
                "compare_price" => 869.9
                "quantity" => 20
                "options" => null
                "rating" => 0.0
                "features" => 0
                "status" => "active"
                "created_at" => "2025-11-25 16:51:22"
                "updated_at" => "2025-11-25 16:51:22"
                "deleted_at" => null
            ]
            #original: array:23 [▶]
            #changes: []
            #casts: []
            #classCastCache: []
            #attributeCastCache: []
            #dates: []
            #dateFormat: null
            #appends: []
            #dispatchesEvents: []
            #observables: []
            #relations: array:1 [▼
                "pivot" => App\ModelOrderItem {#1095 ▼
                #connection: "mysql"
                #table: "order_items"
                #primaryKey: "id"
                #keyType: "int"
                +incrementing: true
                #with: []
                #withCount: []
                +preventsLazyLoading: false
                #perPage: 15
                +exists: true
                +wasRecentlyCreated: false
                #escapeWhenCastingToString: false
                #attributes: array:6 [▼
                    "order_id" => 48
                    "product_id" => 93
                    "product_name" => "Gorgeous Wooden Hat"
                    "price" => 68.6
                    "quantity" => 1
                    "options" => null
                ]
                #original: array:6 [▶]
                #changes: []
                #casts: []
                #classCastCache: []
                #attributeCastCache: []
                #dates: []
                #dateFormat: null
                #appends: []
                #dispatchesEvents: []
                #observables: []
                #relations: []
                #touches: []
                +timestamps: false
                #hidden: []
                #visible: []
                #fillable: []
                #guarded: []
                +pivotParent: App\Models\Order {#1075 ▶}
                #foreignKey: "order_id"
                #relatedKey: "product_id"
                }
            ]
            #touches: []
            +timestamps: true
            #hidden: []
            #visible: []
            #fillable: array:9 [▶]
            #guarded: array:1 [▶]
            }


            1 => App\Models\Product {#1099 ▶}


        ]
            #escapeWhenCastingToString: false
        }
    */
