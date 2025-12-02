<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Item;
use App\Models\User;
use App\Models\Payment;
use App\Models\CommissionSetting;
use Illuminate\Support\Facades\Auth;
use App\Services\StripeService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * Create payment intent for checkout (Stripe Connect + commission)
     */
    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $items = $request->items;
            $orderNumber = mt_rand(1000000, 9999999);

            // Default commission rate (admin configurable)
            $defaultCommissionRate = CommissionSetting::getDefaultRate();

            // Group items by vendor
            $vendorOrders = [];
            $totalAmount = 0;
            $totalCommission = 0;

            foreach ($items as $itemData) {
                $item = Item::find($itemData['item_id']);
                if (!$item) {
                    continue;
                }

                $vendor = User::find($item->user_id);
                if (!$vendor) {
                    return $this->error('Vendor not found', 400);
                }

                if (empty($vendor->stripe_account_id) ||
                    !$this->stripeService->canAccountAcceptCharges($vendor->stripe_account_id)) {
                    return $this->error("Vendor {$vendor->name} is not set up to accept payments", 400);
                }

                $finalPrice = $item->discount_percentage == "0.00"
                    ? $item->price
                    : ($item->price * (1 - $item->discount_percentage / 100));

                $quantity = $itemData['quantity'];
                $itemTotal = $finalPrice * $quantity;

                // Calculate commission based on item's commission percentage,
                // falling back to the global default commission rate
                $commissionPercent = $item->commission ?? $defaultCommissionRate;
                $commissionAmount = ($itemTotal * $commissionPercent) / 100;
                $vendorAmount = $itemTotal - $commissionAmount;

                if (!isset($vendorOrders[$vendor->id])) {
                    $vendorOrders[$vendor->id] = [
                        'vendor' => $vendor,
                        'items' => [],
                        'total' => 0,
                        'commission' => 0,
                        'vendor_amount' => 0,
                    ];
                }

                $vendorOrders[$vendor->id]['items'][] = [
                    'item' => $item,
                    'quantity' => $quantity,
                    'price' => $finalPrice,
                    'total' => $itemTotal,
                    'commission' => $commissionAmount,
                ];

                $vendorOrders[$vendor->id]['total'] += $itemTotal;
                $vendorOrders[$vendor->id]['commission'] += $commissionAmount;
                $vendorOrders[$vendor->id]['vendor_amount'] += $vendorAmount;

                $totalAmount += $itemTotal;
                $totalCommission += $commissionAmount;
            }

            // Create orders for each vendor
            $paymentIntents = [];

            foreach ($vendorOrders as $vendorId => $vendorData) {
                $vendor = $vendorData['vendor'];

                // Calculate Stripe Fees (for tracking only - Stripe deducts this automatically)
                $stripeFees = CommissionSetting::getStripeFees();
                $stripeFeeAmount = ($vendorData['total'] * $stripeFees['percentage'] / 100) + $stripeFees['fixed'];

                // Calculate Tax (based on tax percentage from settings)
                $taxPercentage = CommissionSetting::getTaxPercentage();
                $taxAmount = ($vendorData['total'] * $taxPercentage) / 100;

                // Application fee = commission + tax (Stripe fee is deducted automatically by Stripe)
                $totalCommission = $vendorData['commission'] + $taxAmount;
                
                // Vendor amount = total - commission - tax - Stripe fee
                $adjustedVendorAmount = $vendorData['total'] - $totalCommission - $stripeFeeAmount;


                // Create order record
                $order = Order::create([
                    'order_id' => $orderNumber,
                    'order_by' => $user->name,
                    'user_id' => $user->id,
                    'vender_id' => $vendor->id,
                    'total_price' => $vendorData['total'],
                    'tax_amount' => $taxAmount,
                    'commission_amount' => $totalCommission, // Commission + Tax (Stripe fee separate)
                    'vendor_amount' => $adjustedVendorAmount,
                    'currency' => 'usd',
                    'order_status' => 'Pending',
                    'payment_status' => 'pending',
                ]);

                // Create order items
                foreach ($vendorData['items'] as $itemData) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'name' => $itemData['item']->name,
                        'price' => $itemData['price'],
                        'quantity' => $itemData['quantity'],
                    ]);
                }

                // Create Stripe payment intent
                $amountInCents = (int) ($vendorData['total'] * 100);
                $commissionInCents = (int) ($totalCommission * 100);

                $paymentIntent = $this->stripeService->createPaymentIntent(
                    $amountInCents,
                    'usd',
                    $commissionInCents,
                    $vendor->stripe_account_id,
                    [
                        'order_id' => $order->id,
                        'order_number' => $orderNumber,
                        'user_id' => $user->id,
                        'vendor_id' => $vendor->id,
                    ]
                );

                $order->stripe_payment_intent_id = $paymentIntent->id;
                $order->save();

                // Record payment metadata for tracking
                Payment::create([
                    'order_id' => $order->id,
                    'vendor_id' => $vendor->id,
                    'user_id' => $user->id,
                    'amount' => $vendorData['total'],
                    'currency' => 'usd',
                    'application_fee_amount' => $vendorData['commission'],
                    'stripe_payment_intent_id' => $paymentIntent->id,
                    'status' => 'pending',
                    'metadata' => [
                        'order_number' => $orderNumber,
                        'vendor_name' => $vendor->name,
                    ],
                ]);

                $paymentIntents[] = [
                    'vendor_name' => $vendor->name,
                    'amount' => $vendorData['total'],
                    'commission' => $vendorData['commission'],
                    'client_secret' => $paymentIntent->client_secret,
                    'payment_intent_id' => $paymentIntent->id,
                ];
            }

            DB::commit();

            return $this->success([
                'order_number' => $orderNumber,
                'total_amount' => $totalAmount,
                'total_commission' => $totalCommission,
                'payment_intents' => $paymentIntents,
                'publishable_key' => config('services.stripe.key'),
            ], 'Payment intent created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment Intent Creation Failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->error('Failed to create payment intent: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Confirm payment and update order status
     */
    public function confirmPayment(Request $request)
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
        ]);

        try {
            $paymentIntentId = $request->payment_intent_id;
            $order = Order::where('stripe_payment_intent_id', $paymentIntentId)->first();

            if (!$order) {
                return $this->error('Order not found', 404);
            }

            $order->payment_status = 'paid';
            $order->order_status = 'In Progress';
            $order->save();

            // Mark related payment record as succeeded
            $payment = Payment::where('stripe_payment_intent_id', $paymentIntentId)->first();
            if ($payment) {
                $payment->markAsSucceeded();
            }

            return $this->success($order, 'Payment confirmed successfully');
        } catch (\Exception $e) {
            Log::error('Payment Confirmation Failed', [
                'payment_intent_id' => $request->payment_intent_id,
                'error' => $e->getMessage(),
            ]);

            return $this->error('Failed to confirm payment: ' . $e->getMessage(), 500);
        }
    }

    public function place_order(Request $request)
    {
        $OrderNumber = mt_rand(1000, 9999);

        $items = $request->items;

        if (empty($items)) {
            return $this->error('Please provide valid order details', 401);
        }

        foreach ($items as $item) {
            $result = Item::where('id', '=', $item['item_id'])->first();
            if (!empty($result)) {

                $final_price = $result->discount_percentage == "0.00" ? $result->price : $result->discounted_price;

                $order = Order::create([
                    'order_id' => $OrderNumber,
                    'order_by' => Auth::User()->name,
                    'user_id' => Auth::id(),
                    'vender_id' => $result->user_id,
                    'total_price' => ($item['quantity'] * $final_price)
                ]);

                OrderItem::create([
                    'name' => $result->name,
                    'price' => $final_price,
                    'quantity' => $item['quantity'],
                    'order_id' => $order->id
                ]);
            }
        }

        return $this->success([], 'Order Placed successfully', 201);
    }

    public function get_vender_orders(Request $request)
    {
        $type = $request->query('type');

        $orders = Order::where('vender_id', Auth::id())
            ->when($type == 'pending', function ($query) {
                $query->where('order_status', 'Pending');
            })
            ->when($type == 'in-progress', function ($query) {
                $query->where('order_status', 'In Progress');
            })
            ->when($type == 'in-progress', function ($query) {
                $query->where('order_status', 'Completed');
            })->orderBy('id', 'desc')->get();

        foreach ($orders as $order) {
            $vender = User::where('id', '=', $order->vender_id)->first();
            $user = User::where('id', '=', $order->user_id)->first();
            $order->user_email = $user->email ?? null;
            $order->user_phone = $user->phone ?? null;
            $order->store_name = $vender->name ?? null;
            $order->store_logo = $vender->image ?? null;
            $order->items = OrderItem::where('order_id', '=', $order->id)->get();
        }

        return $this->success($orders, 'orders fetched successfully');
    }

    public function get_user_orders(Request $request)
    {
        $type = $request->query('type');

        $orders = Order::where('user_id', Auth::id())
            ->when($type == 'pending', function ($query) {
                $query->where('order_status', 'Pending');
            })
            ->when($type == 'in-progress', function ($query) {
                $query->where('order_status', 'In Progress');
            })
            ->when($type == 'in-progress', function ($query) {
                $query->where('order_status', 'Completed');
            })->orderBy('id', 'desc')->get();

        foreach ($orders as $order) {
            $vender = User::where('id', '=', $order->vender_id)->first();
            $order->store_name = $vender->name ?? null;
            $order->store_logo = $vender->image ?? null;
            $order->items = OrderItem::where('order_id', '=', $order->id)->get();
        }

        return $this->success($orders, 'orders fetched successfully');
    }

    public function update_order_status(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required',
            'order_status' => 'required|in:Pending,In Progress,Completed'
        ]);

        $order = Order::where('id', '=', $validated['order_id'])->first();

        if (!empty($order)) {
            $order->order_status = $validated['order_status'];
            $order->updated_at = now();
            $order->save();
        }

        return $this->success($order, 'order updated successfully');
    }
}
