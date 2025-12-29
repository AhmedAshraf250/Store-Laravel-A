<?php

namespace App\Services\Stripe;

use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class StripeWebhookService
{
    public function handle(\Stripe\Event $event)
    {
        $correlationId = (string) Str::uuid();

        Log::channel('stripe')->info('Webhook received', [
            'correlation_id' => $correlationId,
            'event_id' => $event->id,
            'type' => $event->type,
        ]);

        match ($event->type) {
            'payment_intent.succeeded' =>
            $this->paymentSucceeded($event, $correlationId),

            'payment_intent.payment_failed' =>
            $this->paymentFailed($event, $correlationId),

            default =>
            $this->unhandled($event, $correlationId),
        };
    }

    protected function paymentSucceeded($event, $cid)
    {
        $pi = $event->data->object;

        if (empty($pi->metadata->order_id)) {
            Log::channel('stripe')->notice('Missing order_id', [
                'correlation_id' => $cid,
                'pi_id' => $pi->id,
            ]);
            return;
        }

        $updated = Payment::where('order_id', $pi->metadata->order_id)
            ->where('status', '!=', 'paid')
            ->update([
                'status' => 'paid',
                // 'transaction_id' => $pi->id,
            ]);

        Log::channel('stripe')->info('Payment processed', [
            'correlation_id' => $cid,
            'order_id' => $pi->metadata->order_id,
            'updated' => $updated,
        ]);
    }

    protected function paymentFailed($event, $cid)
    {
        Log::channel('stripe')->error('Payment failed', [
            'correlation_id' => $cid,
            'pi_id' => $event->data->object->id,
        ]);
    }

    protected function unhandled($event, $cid)
    {
        Log::channel('stripe')->warning('Unhandled event', [
            'correlation_id' => $cid,
            'type' => $event->type,
        ]);
    }
}
