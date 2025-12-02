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
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained('orders');
                $table->foreignId('vendor_id')->constrained('users');
                $table->foreignId('user_id')->constrained('users');
                $table->decimal('amount', 10, 2);
                $table->string('currency', 3)->default('usd');
                $table->decimal('application_fee_amount', 10, 2)->default(0);
                $table->string('stripe_payment_intent_id')->index();
                $table->string('stripe_charge_id')->nullable();
                $table->string('stripe_transfer_id')->nullable();
                $table->string('status')->default('pending'); // pending, succeeded, failed, refunded
                $table->json('metadata')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
