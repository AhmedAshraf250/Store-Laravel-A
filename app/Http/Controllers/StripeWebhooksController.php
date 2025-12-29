<?php

namespace App\Http\Controllers;

use App\Jobs\HandleStripeEvent;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class StripeWebhooksController extends Controller
{

    public function __invoke(Request $request)
    {
        $event = $this->verifyWebhook($request);

        dispatch(new HandleStripeEvent($event)); //->onQueue('webhooks');
        // HandleStripeEvent::dispatch($event)->onQueue('webhooks')->onConnection('database');

        return response('OK', 200);
    }

    protected function verifyWebhook(Request $request): \Stripe\Event
    {

        // Set your secret key. Remember to switch to your live secret key in production.
        \Stripe\Stripe::setApiKey(config('services.stripe.secret_key'));

        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        try {
            return \Stripe\Webhook::constructEvent(
                $payload,
                $sigHeader,
                $secret
            ); // returns a "Stripe Event"
        } catch (\Throwable $e) {
            abort(400, 'Invalid Stripe webhook');
        }
    }

    public function explain_HMAC() // its for me 
    {
        // HMAC => Hash-based Message Authentication Code

        /*
        |--------------------------------------------------------------------------
        | STRIPE-LIKE HMAC SIGNATURE EXAMPLE
        |--------------------------------------------------------------------------
        |
        | This is a simplified example of sending and verifying a webhook
        | request using HMAC SHA256 signatures. The flow is visualized below:
        |
        |       ┌───────────────┐
        |       |  SEND PAYLOAD |
        |       └───────┬───────┘
        |               |
        |               v
        |       ┌───────────────┐
        |       |  VERIFICATION |
        |       └───────┬───────┘
        |               |
        |               v
        |       ┌───────────────┐
        |       |   TRUSTED?    |
        |       └───────────────┘
        |
        */

        /* =========================
        STEP 1: SENDING THE PAYLOAD
        ========================= */

        // Shared secret between sender and receiver
        $secret = 'my-secret';

        // Raw JSON payload
        $payload = '{"order_id":123,"amount":100}';

        // Current timestamp (used to prevent replay attacks)
        $timestamp = time();

        // Combine timestamp + payload for signing
        $signedPayload = $timestamp . '.' . $payload;
        // -> "1735400000.{\"order_id\":123,\"amount\":100}"

        // Generate HMAC SHA256 signature
        $signature = hash_hmac('sha256', $signedPayload, $secret);
        // -> unique fingerprint only reproducible with the secret

        // Prepare headers to send with the request
        $headers = [
            'X-Signature: t=' . $timestamp . ',v1=' . $signature,
        ];
        // Example header: X-Signature: t=1735400000,v1=e4b5c9d6a1f8...

        /* =========================
        STEP 2: RECEIVING & VERIFYING
        ========================= */

        // Simulate received payload and header
        $receivedPayload = $payload;
        $receivedHeader = 't=1735400000,v1=' . $signature;

        // Parse the timestamp and signature from the header
        parse_str(str_replace(',', '&', $receivedHeader), $parts);
        // $parts['t'] => timestamp
        // $parts['v1'] => signature

        // Reconstruct the signed payload exactly like the sender
        $signedPayload = $parts['t'] . '.' . $receivedPayload;

        // Recalculate the HMAC using the shared secret
        $expected = hash_hmac('sha256', $signedPayload, $secret);

        // Compare the expected signature with the received signature
        if (hash_equals($expected, $parts['v1'])) {
            // ✅ Request is trusted (came from the sender with the secret)
            echo 'Trusted request';
        } else {
            // ❌ Signature mismatch, reject request
            echo 'Fake request';
        }

        /*
        |--------------------------------------------------------------------------
        | NOTES:
        |--------------------------------------------------------------------------
        | 1. hash_equals() is used to prevent timing attacks.
        | 2. The raw payload must never be modified before verification.
        | 3. Timestamp can be used to prevent replay attacks (check if it's recent).
        | 4. Any change in the payload or header will invalidate the signature.
        */
    }
}
