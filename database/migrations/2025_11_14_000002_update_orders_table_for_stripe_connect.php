<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add Stripe Connect fields
            if (!Schema::hasColumn('orders', 'stripe_payment_intent_id')) {
                $table->string('stripe_payment_intent_id')->nullable()->after('vender_id');
            }
            if (!Schema::hasColumn('orders', 'stripe_transfer_id')) {
                $table->string('stripe_transfer_id')->nullable()->after('stripe_payment_intent_id');
            }
            if (!Schema::hasColumn('orders', 'commission_amount')) {
                $table->decimal('commission_amount', 10, 2)->default(0)->after('total_price');
            }
            if (!Schema::hasColumn('orders', 'vendor_amount')) {
                $table->decimal('vendor_amount', 10, 2)->default(0)->after('commission_amount');
            }
            if (!Schema::hasColumn('orders', 'currency')) {
                $table->string('currency', 3)->default('usd')->after('vendor_amount');
            }
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('order_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'stripe_payment_intent_id',
                'stripe_transfer_id',
                'commission_amount',
                'vendor_amount',
                'currency',
                'payment_status'
            ]);
        });
    }
};
