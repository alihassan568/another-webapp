<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Add Stripe fields to users table (not vendors - vendors don't exist)
        if (!Schema::hasColumn('users', 'stripe_account_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('stripe_account_id')->nullable();
                $table->timestamp('stripe_onboarded_at')->nullable();
                $table->string('stripe_status', 20)->default('incomplete');
                $table->boolean('charges_enabled')->default(false);
                $table->boolean('payouts_enabled')->default(false);
            });
        }

        // Add Stripe fields to existing orders table
        if (!Schema::hasColumn('orders', 'stripe_payment_intent_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('stripe_payment_intent_id')->nullable();
                $table->string('stripe_transfer_id')->nullable();
                $table->string('payment_status')->default('pending');
                $table->decimal('commission_amount', 10, 2)->nullable();
                $table->decimal('vendor_amount', 10, 2)->nullable();
            });
        }

        // Create payouts table
        if (!Schema::hasTable('payouts')) {
            Schema::create('payouts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('vendor_id')->constrained('users');
                $table->decimal('amount', 10, 2);
                $table->string('currency', 3)->default('usd');
                $table->string('status')->default('pending');
                $table->string('stripe_payout_id')->nullable();
                $table->timestamp('arrival_date')->nullable();
                $table->text('metadata')->nullable();
                $table->timestamps();
            });
        }

        // Create stripe_webhook_events table
        if (!Schema::hasTable('stripe_webhook_events')) {
            Schema::create('stripe_webhook_events', function (Blueprint $table) {
                $table->id();
                $table->string('stripe_id')->unique();
                $table->string('event_type');
                $table->json('payload');
                $table->boolean('processed')->default(false);
                $table->timestamps();
            });
        }

        // Create commission_settings table
        if (!Schema::hasTable('commission_settings')) {
            Schema::create('commission_settings', function (Blueprint $table) {
                $table->id();
                $table->string('type')->default('default'); // default, category, vendor
                $table->decimal('rate', 5, 2)->default(10.00);
                $table->boolean('active')->default(true);
                $table->timestamps();
            });
        }
    }
};
