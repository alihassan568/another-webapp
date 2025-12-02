<?php

namespace App\Http\Controllers\Api;

use App\Models\StripeWebhook;
use App\Models\Order;
use App\Models\Payout;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class WebhookController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
        // Disable CSRF protection for webhook endpoint
        $this->middleware('api');
    }

    /**
     * Handle incoming Stripe webhook events
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            // Verify the webhook signature
            $event = $this->stripeService->handleWebhook($payload, $sigHeader);
            
            // Store the webhook for auditing
            $webhook = StripeWebhook::create([
                'stripe_id' => $event->id,
                'event_type' => $event->type,
                'payload' => $event->toArray(),
            ]);

            // Handle the event
            $this->handleEvent($event);

            // Mark as processed
            $webhook->update(['processed' => true]);

            return response()->json(['received' => true]);

        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            Log::error('Invalid webhook payload: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            Log::error('Invalid webhook signature: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        } catch (\Exception $e) {
            Log::error('Webhook error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle the Stripe event
     */
    protected function handleEvent($event)
    {
        $method = 'handle' . str_replace('.', '', $event->type);
        
        if (method_exists($this, $method)) {
            return $this->{$method}($event->data->object);
        }
        
        Log::info('Unhandled Stripe event: ' . $event->type);
    }

    /**
     * Handle payment intent succeeded
     */
    protected function handlePaymentIntentSucceeded($paymentIntent)
    {
        // Update order status to paid
        $order = Order::where('stripe_payment_intent_id', $paymentIntent->id)->first();
        
        if ($order) {
            $order->update([
                'status' => 'processing',
                'payment_status' => 'paid',
                'paid_at' => now(),
            ]);
            
            // Additional logic for successful payment
        }
    }

    /**
     * Handle payment intent payment failed
     */
    protected function handlePaymentIntentPaymentFailed($paymentIntent)
    {
        $order = Order::where('stripe_payment_intent_id', $paymentIntent->id)->first();
        
        if ($order) {
            $order->update([
                'status' => 'payment_failed',
                'payment_status' => 'failed',
            ]);
            
            // Notify admin or user about the failed payment
        }
    }

    /**
     * Handle charge succeeded
     */
    protected function handleChargeSucceeded($charge)
    {
        // Handle successful charge
    }

    /**
     * Handle charge failed
     */
    protected function handleChargeFailed($charge)
    {
        // Handle failed charge
    }

    /**
     * Handle transfer created
     */
    protected function handleTransferCreated($transfer)
    {
        // Handle transfer creation
    }

    /**
     * Handle transfer failed
     */
    protected function handleTransferFailed($transfer)
    {
        // Handle transfer failure
        $order = Order::where('stripe_transfer_id', $transfer->id)->first();
        
        if ($order) {
            $order->update([
                'transfer_status' => 'failed',
                'transfer_failure_reason' => $transfer->failure_message ?? 'Unknown error',
            ]);
            
            // Notify admin about the transfer failure
        }
    }

    /**
     * Handle payout paid
     */
    protected function handlePayoutPaid($payout)
    {
        // Update payout status to paid
        Payout::where('stripe_payout_id', $payout->id)
            ->update([
                'status' => 'completed',
                'processed_at' => now(),
            ]);
    }

    /**
     * Handle payout failed
     */
    protected function handlePayoutFailed($payout)
    {
        // Update payout status to failed
        Payout::where('stripe_payout_id', $payout->id)
            ->update([
                'status' => 'failed',
                'notes' => 'Payout failed: ' . ($payout->failure_message ?? 'Unknown error'),
            ]);
    }

    /**
     * Handle account updated
     */
    protected function handleAccountUpdated($account)
    {
        // Update vendor's account status
        $user = User::where('stripe_account_id', $account->id)->first();
        
        if ($user) {
            $user->update([
                'stripe_status' => $account->payouts_enabled ? 'active' : 'incomplete',
            ]);
        }
    }
}
