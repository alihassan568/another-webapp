<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payout;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
        $this->middleware('auth:api');
    }

    /**
     * Create a payment intent for checkout
     */
    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'vendor_id' => 'required|exists:users,id',
            'order_id' => 'required|exists:orders,id',
            'currency' => 'sometimes|string|size:3|default:usd',
        ]);

        $vendor = User::findOrFail($request->vendor_id);
        
        if (!$vendor->stripe_account_id || !$vendor->stripe_onboarded_at) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vendor payment account is not set up',
            ], 400);
        }

        try {
            // Calculate application fee (commission)
            $amount = (int) ($request->amount * 100); // Convert to cents
            $commission = $this->calculateCommission($vendor->id, $amount);
            $applicationFee = $commission['amount'];
            
            // Create payment intent
            $paymentIntent = $this->stripeService->createPaymentIntent(
                $amount,
                $request->currency,
                $applicationFee,
                $vendor->stripe_account_id,
                [
                    'order_id' => $request->order_id,
                    'vendor_id' => $vendor->id,
                ]
            );

            // Update order with payment intent ID
            Order::where('id', $request->order_id)->update([
                'stripe_payment_intent_id' => $paymentIntent->id,
                'status' => 'pending_payment',
            ]);

            return response()->json([
                'status' => 'success',
                'client_secret' => $paymentIntent->client_secret,
                'publishable_key' => config('services.stripe.key'),
                'amount' => $amount,
                'currency' => $request->currency,
                'application_fee' => $applicationFee,
            ]);

        } catch (\Exception $e) {
            Log::error('Payment intent creation failed: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create payment intent: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Confirm a payment and update order status
     */
    public function confirmPayment(Request $request)
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
            'order_id' => 'required|exists:orders,id',
        ]);

        try {
            $order = Order::findOrFail($request->order_id);
            
            // Verify payment intent
            $paymentIntent = $this->stripeService->retrievePaymentIntent($request->payment_intent_id);
            
            if ($paymentIntent->status !== 'succeeded') {
                throw new \Exception('Payment not completed');
            }
            
            // Update order status
            $order->update([
                'status' => 'processing',
                'payment_status' => 'paid',
                'paid_at' => now(),
            ]);
            
            // Create a record of the transfer (for reconciliation)
            if (isset($paymentIntent->transfer_data->destination)) {
                $order->update([
                    'stripe_transfer_id' => $paymentIntent->transfer_data->destination,
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Payment confirmed successfully',
                'order' => $order->fresh(),
            ]);

        } catch (\Exception $e) {
            Log::error('Payment confirmation failed: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to confirm payment: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Initiate a manual payout to a vendor
     */
    public function createPayout(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'currency' => 'sometimes|string|size:3|default:usd',
            'method' => 'required|in:stripe,manual_bank,other',
            'notes' => 'nullable|string',
        ]);

        $vendor = User::findOrFail($request->vendor_id);
        $amount = (int) ($request->amount * 100); // Convert to cents

        try {
            // Create payout record
            $payout = Payout::create([
                'vendor_id' => $vendor->id,
                'amount' => $request->amount,
                'currency' => $request->currency,
                'status' => 'pending',
                'method' => $request->method,
                'notes' => $request->notes,
            ]);

            // Process payout based on method
            if ($request->method === 'stripe' && $vendor->stripe_account_id) {
                // Process via Stripe Connect
                $transfer = $this->stripeService->createTransfer(
                    $amount,
                    $request->currency,
                    $vendor->stripe_account_id,
                    'Payout for order #' . ($payout->id ?? 'N/A')
                );

                $payout->update([
                    'status' => 'completed',
                    'reference' => $transfer->id,
                    'processed_at' => now(),
                ]);

            } else {
                // Manual payout - requires admin approval
                // Notification can be sent to admin here
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Payout processed successfully',
                'payout' => $payout->fresh(),
            ]);

        } catch (\Exception $e) {
            Log::error('Payout failed: ' . $e->getMessage());
            
            // Update payout status if it was created
            if (isset($payout)) {
                $payout->update([
                    'status' => 'failed',
                    'notes' => ($payout->notes ? $payout->notes . '\n' : '') . 'Error: ' . $e->getMessage(),
                ]);
            }
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process payout: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Calculate commission for a given amount
     */
    protected function calculateCommission($vendorId, $amountInCents)
    {
        $amount = $amountInCents / 100; // Convert to dollars for commission calculation
        $settings = CommissionSetting::getSettings();
        return $settings->calculateCommission($vendorId, $amount);
    }
}
