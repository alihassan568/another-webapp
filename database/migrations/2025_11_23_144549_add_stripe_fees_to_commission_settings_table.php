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
        Schema::table('commission_settings', function (Blueprint $table) {
            $table->decimal('stripe_fee_percentage', 5, 2)->default(2.90);
            $table->decimal('stripe_fee_fixed', 5, 2)->default(0.30);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commission_settings', function (Blueprint $table) {
            $table->dropColumn(['stripe_fee_percentage', 'stripe_fee_fixed']);
        });
    }
};
