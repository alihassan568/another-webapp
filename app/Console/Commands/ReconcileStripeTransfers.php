<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\StripeService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ReconcileStripeTransfers extends Command
{
    protected $signature = 'stripe:reconcile {--date= : The date to reconcile (Y-m-d format)}';
    protected $description = 'Reconcile Stripe transfers with local orders';

    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        parent::__construct();
        $this->stripeService = $stripeService;
    }

    public function handle()
    {
        $date = $this->option('date') 
            ? \Carbon\Carbon::parse($this->option('date'))
            : \Carbon\Carbon::yesterday();

        $this->info("Reconciling Stripe transfers for {$date->toDateString()}");

        $startOfDay = $date->startOfDay()->timestamp;
        $endOfDay = $date->copy()->endOfDay()->timestamp;

        // Get local orders
        $orders = Order::whereNotNull('stripe_payment_intent_id')
            ->where('payment_status', 'paid')
            ->whereBetween('updated_at', [
                \Carbon\Carbon::createFromTimestamp($startOfDay),
                \Carbon\Carbon::createFromTimestamp($endOfDay)
            ])
            ->get();

        $this->info("Found {$orders->count()} paid orders");

        $mismatches = [];
        $matchedCount = 0;

        foreach ($orders as $order) {
            try {
                // Retrieve payment intent from Stripe
                $paymentIntent = \Stripe\PaymentIntent::retrieve($order->stripe_payment_intent_id);

                $localAmount = (int)($order->total_price * 100);
                $stripeAmount = $paymentIntent->amount;
                $localCommission = (int)($order->commission_amount * 100);
                $stripeCommission = $paymentIntent->application_fee_amount ?? 0;

                if ($localAmount !== $stripeAmount) {
                    $mismatches[] = [
                        'order_id' => $order->id,
                        'order_number' => $order->order_id,
                        'issue' => 'Amount mismatch',
                        'local_amount' => $localAmount / 100,
                        'stripe_amount' => $stripeAmount / 100,
                        'difference' => ($stripeAmount - $localAmount) / 100,
                    ];
                } elseif ($localCommission !== $stripeCommission) {
                    $mismatches[] = [
                        'order_id' => $order->id,
                        'order_number' => $order->order_id,
                        'issue' => 'Commission mismatch',
                        'local_commission' => $localCommission / 100,
                        'stripe_commission' => $stripeCommission / 100,
                        'difference' => ($stripeCommission - $localCommission) / 100,
                    ];
                } else {
                    $matchedCount++;
                }

            } catch (\Exception $e) {
                $mismatches[] = [
                    'order_id' => $order->id,
                    'order_number' => $order->order_id,
                    'issue' => 'Stripe API error',
                    'error' => $e->getMessage(),
                ];
            }
        }

        $this->info("Matched orders: {$matchedCount}");
        $this->info("Mismatches found: " . count($mismatches));

        if (count($mismatches) > 0) {
            // Generate CSV report
            $csvData = $this->generateCSV($mismatches, $date);
            $filename = "stripe_reconciliation_{$date->format('Y_m_d')}.csv";
            Storage::disk('local')->put("reconciliation/{$filename}", $csvData);

            $this->warn("Mismatches found! Report saved to storage/app/reconciliation/{$filename}");

            // Log mismatches
            Log::warning('Stripe reconciliation mismatches found', [
                'date' => $date->toDateString(),
                'total_orders' => $orders->count(),
                'matched' => $matchedCount,
                'mismatches' => count($mismatches),
            ]);

            // Optional: Send email to admin
            // Mail::to(config('mail.admin_email'))->send(new ReconciliationReport($mismatches, $date));

            $this->table(
                ['Order ID', 'Order Number', 'Issue', 'Details'],
                collect($mismatches)->map(function ($mismatch) {
                    return [
                        $mismatch['order_id'],
                        $mismatch['order_number'],
                        $mismatch['issue'],
                        isset($mismatch['difference']) 
                            ? "Difference: $" . number_format($mismatch['difference'], 2)
                            : ($mismatch['error'] ?? 'N/A'),
                    ];
                })
            );
        } else {
            $this->info("All orders reconciled successfully!");
        }

        return 0;
    }

    protected function generateCSV(array $mismatches, $date)
    {
        $csv = "Stripe Reconciliation Report - {$date->toDateString()}\n\n";
        $csv .= "Order ID,Order Number,Issue,Local Amount,Stripe Amount,Difference,Error\n";

        foreach ($mismatches as $mismatch) {
            $csv .= implode(',', [
                $mismatch['order_id'],
                $mismatch['order_number'],
                $mismatch['issue'],
                $mismatch['local_amount'] ?? '',
                $mismatch['stripe_amount'] ?? '',
                $mismatch['difference'] ?? '',
                $mismatch['error'] ?? '',
            ]) . "\n";
        }

        return $csv;
    }
}
