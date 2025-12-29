<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Stripe\StripeClient;

class PaymentsController extends Controller
{
    private StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret_key'));
    }

    public function showPaymentForm(Order $order)
    {
        if (! $this->orderBelongsToSession($order)) {
            abort(403, 'Unauthorized access to order.');
        }

        if ($order->isPaid()) {
            return redirect()->route('cart.index')->with('error', 'Order already paid.');
        }

        return view('front.payments.create', compact('order'));
    }

    public function createStripePaymentIntent(Order $order)
    {
        if (! $this->orderBelongsToSession($order)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($order->isPaid()) {
            return response()->json(['error' => 'Order already paid'], 400);
        }
        \Barryvdh\Debugbar\Facades\Debugbar::disable();

        header('Content-Type: application/json');

        $amount = (int) round($order->total() * 100);
        try {
            // // retrieve JSON from POST body //
            // $jsonStr = file_get_contents('php://input');
            // $jsonObj = json_decode($jsonStr);

            // Create a PaymentIntent with amount and currency
            $paymentIntent = $this->stripe->paymentIntents->create([
                'amount' => $amount,
                'currency' => 'usd',
                // In the latest version of the API, specifying the `automatic_payment_methods` parameter is optional because Stripe enables its functionality by default.
                'automatic_payment_methods' => ['enabled' => true,],
                'metadata' => [
                    'order_id' => $order->id,
                    'guest' => auth()->guest() ? 'true' : 'false',
                ],
            ]);

            /* [$paymentIntent =]
            "data": {
                "id": "pi_3SjHLQRvH9mMfc2x1jvIgmWK",
                "object": "payment_intent",
                "amount": 75620,
                "amount_capturable": 0,
                "amount_details": { "tip": [] },
                "amount_received": 0,
                "application": null,
                "application_fee_amount": null,
                "automatic_payment_methods": {
                "allow_redirects": "always",
                "enabled": true
                },
                "canceled_at": null,
                "cancellation_reason": null,
                "capture_method": "automatic_async",
                "client_secret": "pi_3SjHLQRvH9mMfc2x1jvIgmWK_secret_zUIduRWhDuObOOD1DyaksgHSv",
                "confirmation_method": "automatic",
                "created": 1766918224,
                "currency": "usd",
                "customer": null,
                "customer_account": null,
                "description": null,
                "excluded_payment_method_types": null,
                "last_payment_error": null,
                "latest_charge": null,
                "livemode": false,
                "metadata": [],
                "next_action": null,
                "on_behalf_of": null,
                "payment_method": null,
                "payment_method_configuration_details": {
                "id": "pmc_1SiI6gRvH9mMfc2x2jgVqjxu",
                "parent": null
                },
                "payment_method_options": {
                "affirm": [],
                "amazon_pay": { "express_checkout_element_session_id": null },
                "card": {
                    "installments": null,
                    "mandate_options": null,
                    "network": null,
                    "request_three_d_secure": "automatic"
                },
                "cashapp": [],
                "klarna": { "preferred_locale": null },
                "link": { "persistent_token": null }
                },
                "payment_method_types": [
                "card",
                "klarna",
                "link",
                "affirm",
                "cashapp",
                "amazon_pay"
                ],
                "processing": null,
                "receipt_email": null,
                "review": null,
                "setup_future_usage": null,
                "shipping": null,
                "source": null,
                "statement_descriptor": null,
                "statement_descriptor_suffix": null,
                "status": "requires_payment_method",
                "transfer_data": null,
                "transfer_group": null
            }
        */

            Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'amount' => $paymentIntent->amount,
                    'currency' => $paymentIntent->currency,
                    'method' => 'stripe',
                    'status' => 'pending',
                    'transaction_id' => $paymentIntent->id,
                ]
            );
            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
            ]);
        } catch (\Error $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function confirm(Request $request, Order $order)
    {
        // dd($request->all());
        /**
         * array:3 [▼ // app/Http/Controllers/Front/PaymentsController.php:69
         *  "payment_intent" => "pi_3SjFUtRvH9mMfc2x0EnMYqzp"
         *  "payment_intent_client_secret" => "pi_3SjFUtRvH9mMfc2x0EnMYqzp_secret_HpDlvX7fOWMyCJOatb5cv9BJY"
         *  "redirect_status" => "succeeded"
         * ]
         */

        if (! $this->orderBelongsToSession($order)) {
            return redirect()->route('home')->with('error', 'Unauthorized');
        }

        $paymentIntentId = $request->input('payment_intent');

        if (!$paymentIntentId) {
            return redirect()->route('checkout.payment.create', $order->id)
                ->with('error', 'Payment failed.');
        }

        try {
            $paymentIntent = $this->stripe->paymentIntents->retrieve($paymentIntentId);

            if ((string) $paymentIntent->metadata->order_id !== (string) $order->id) {
                return redirect()->route('home')->with('error', 'Invalid payment.');
            }

            $payment = Payment::where('order_id', $order->id)
                ->where('transaction_id', $paymentIntent->id)
                ->firstOrFail();

            if ($paymentIntent->status === 'succeeded') {
                $payment->update([
                    'status' => 'completed',
                    'transaction_data' => $paymentIntent->toArray(),
                ]);

                $order->update(['payment_status' => 'paid']);

                Session::forget('guest_order_id');
                // event(new OrderCreated($orders, Auth::user())); // TODO: $notifications
                return redirect()->route('home', ['status' => $paymentIntent->status]);
            }

            return redirect()->route('stripe.paymentIntent.create', $order->id)
                ->with('error', 'Payment ' . ucfirst($paymentIntent->status));
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Payment verification failed.');
        }
    }

    private function orderBelongsToSession(Order $order): bool
    {
        // if the user is logged in → he owns the order
        if (auth()->check() && $order->user_id === auth()->id()) {
            return true;
        }

        // if guest → the order_id from the session must match
        return Session::get('guest_order_id') == $order->id;
    }
}
