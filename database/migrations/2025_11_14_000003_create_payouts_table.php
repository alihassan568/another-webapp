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
        if (!Schema::hasTable('payouts')) {
            Schema::create('payouts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('vendor_id');
                $table->decimal('amount', 10, 2);
                $table->string('currency', 3)->default('usd');
                $table->string('status')->default('pending'); // pending, paid, failed, cancelled
                $table->string('method'); // stripe, manual_bank, paypal, etc.
                $table->string('reference')->nullable();
                $table->string('stripe_payout_id')->nullable();
                $table->timestamp('arrival_date')->nullable();
                $table->text('notes')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();

                $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');
                $table->index('vendor_id');
                $table->index('status');
                $table->index('stripe_payout_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
