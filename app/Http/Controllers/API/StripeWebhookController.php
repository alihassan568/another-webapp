<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payout;
use App\Models\StripeWebhook;
use App\Models\User;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class StripeWebhookController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * Handle incoming Stripe webhooks
     */
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = $this->stripeService->handleWebhook($payload, $sigHeader);
        } catch (\Exception $e) {
            Log::error('Webhook signature verification failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Store webhook event
        StripeWebhook::create([
            'stripe_id' => $event->id,
            'event_type' => $event->type,
            'payload' => json_decode($payload, true),
            'processed' => false,
        ]);

        // Handle the event
        try {
            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $this->handlePaymentIntentSucceeded($event->data->object);
                    break;

                case 'payment_intent.payment_failed':
                    $this->handlePaymentIntentFailed($event->data->object);
                    break;

                case 'charge.refunded':
                    $this->handleChargeRefunded($event->data->object);
                    break;

                case 'account.updated':
                    $this->handleAccountUpdated($event->data->object);
                    break;

                case 'transfer.created':
                    $this->handleTransferCreated($event->data->object);
                    break;

                case 'transfer.failed':
                    $this->handleTransferFailed($event->data->object);
                    break;

                case 'payout.created':
                case 'payout.paid':
                case 'payout.failed':
                case 'payout.canceled':
                    $this->handlePayoutEvent($event->type, $event->data->object);
                    break;

                default:
                    Log::info('Received unhandled webhook event', ['type' => $event->type]);
            }

            // Mark webhook as processed
            StripeWebhook::where('stripe_id', $event->id)->update(['processed' => true]);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Webhook processing failed', [
                'event_type' => $event->type,
                'event_id' => $event->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }

    /**
     * Handle successful payment intent
     */
    protected function handlePaymentIntentSucceeded($paymentIntent)
    {
        $order = Order::where('stripe_payment_intent_id', $paymentIntent->id)->first();
        
        if ($order) {
            $order->markAsPaid($paymentIntent->id);
            
            Log::info('Payment intent succeeded', [
                'order_id' => $order->id,
                'payment_intent_id' => $paymentIntent->id,
                'amount' => $paymentIntent->amount / 100,
            ]);

            // Optional: Send notification to customer and vendor
            // Notification::send($order->customer, new PaymentSuccessfulNotification($order));
        }
    }

    /**
     * Handle failed payment intent
     */
    protected function handlePaymentIntentFailed($paymentIntent)
    {
        $order = Order::where('stripe_payment_intent_id', $paymentIntent->id)->first();
        
        if ($order) {
            $order->markAsPaymentFailed();
            
            Log::warning('Payment intent failed', [
                'order_id' => $order->id,
                'payment_intent_id' => $paymentIntent->id,
                'failure_message' => $paymentIntent->last_payment_error->message ?? 'Unknown error',
            ]);

            // Optional: Send notification to customer
            // Notification::send($order->customer, new PaymentFailedNotification($order));
        }
    }

    /**
     * Handle charge refunded
     */
    protected function handleChargeRefunded($charge)
    {
        $paymentIntentId = $charge->payment_intent;
        $order = Order::where('stripe_payment_intent_id', $paymentIntentId)->first();
        
        if ($order) {
            $order->payment_status = 'refunded';
            $order->order_status = 'Cancelled';
            $order->save();
            
            Log::info('Charge refunded', [
                'order_id' => $order->id,
                'charge_id' => $charge->id,
                'amount' => $charge->amount_refunded / 100,
            ]);

            // Optional: Send refund notification
        }
    }

    /**
     * Handle account updated
     */
    protected function handleAccountUpdated($account)
    {
        $vendor = User::where('stripe_account_id', $account->id)->first();
        
        if ($vendor) {
            $vendor->stripe_status = $account->details_submitted ? 'complete' : 'incomplete';
            $vendor->charges_enabled = $account->charges_enabled ?? false;
            $vendor->payouts_enabled = $account->payouts_enabled ?? false;
            
            if ($account->details_submitted && !$vendor->stripe_onboarded_at) {
                $vendor->stripe_onboarded_at = now();
            }
            
            $vendor->save();
            
            Log::info('Stripe account updated', [
                'vendor_id' => $vendor->id,
                'account_id' => $account->id,
                'charges_enabled' => $account->charges_enabled,
                'payouts_enabled' => $account->payouts_enabled,
            ]);

            // Optional: Send notification to vendor
        }
    }

    /**
     * Handle transfer created
     */
    protected function handleTransferCreated($transfer)
    {
        $metadata = $transfer->metadata ?? [];
        $orderId = $metadata->order_id ?? null;
        
        if ($orderId) {
            $order = Order::find($orderId);
            if ($order) {
                $order->stripe_transfer_id = $transfer->id;
                $order->save();
                
                Log::info('Transfer created', [
                    'order_id' => $order->id,
                    'transfer_id' => $transfer->id,
                    'amount' => $transfer->amount / 100,
                    'destination' => $transfer->destination,
                ]);
            }
        }
    }

    /**
     * Handle transfer failed
     */
    protected function handleTransferFailed($transfer)
    {
        $metadata = $transfer->metadata ?? [];
        $orderId = $metadata->order_id ?? null;
        
        Log::error('Transfer failed', [
            'order_id' => $orderId,
            'transfer_id' => $transfer->id,
            'failure_message' => $transfer->failure_message ?? 'Unknown error',
        ]);

        // Optional: Send notification to admin
        // Notification::route('slack', config('services.slack.webhook'))
        //     ->notify(new TransferFailedNotification($transfer));
    }

    /**
     * Handle payout events
     */
    protected function handlePayoutEvent($eventType, $payout)
    {
        // Extract status from event type (e.g., 'payout.paid' -> 'paid')
        $status = str_replace('payout.', '', $eventType);
        
        // Find vendor by destination
        $destinationAccount = $payout->destination ?? $payout->account ?? null;
        $vendor = User::where('stripe_account_id', $destinationAccount)->first();
        
        if (!$vendor) {
            Log::warning('Payout event for unknown vendor', [
                'payout_id' => $payout->id,
                'destination' => $destinationAccount,
            ]);
            return;
        }

        // Create or update payout record
        Payout::updateOrCreate(
            ['stripe_payout_id' => $payout->id],
            [
                'vendor_id' => $vendor->id,
                'amount' => $payout->amount / 100,
                'currency' => $payout->currency,
                'status' => $status,
                'method' => 'stripe',
                'arrival_date' => $payout->arrival_date 
                    ? \Carbon\Carbon::createFromTimestamp($payout->arrival_date) 
                    : null,
                'metadata' => json_encode($payout->metadata ?? []),
            ]
        );
        
        Log::info('Payout event processed', [
            'vendor_id' => $vendor->id,
            'payout_id' => $payout->id,
            'status' => $status,
            'amount' => $payout->amount / 100,
        ]);

        // Send notifications for failed payouts
        if ($status === 'failed') {
            Log::error('Payout failed', [
                'vendor_id' => $vendor->id,
                'payout_id' => $payout->id,
                'failure_message' => $payout->failure_message ?? 'Unknown error',
            ]);

            // Optional: Send notification to admin and vendor
        }
    }
}
