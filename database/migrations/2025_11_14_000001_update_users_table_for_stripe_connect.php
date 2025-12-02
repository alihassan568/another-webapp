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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'stripe_account_id')) {
                $table->string('stripe_account_id')->nullable()->after('image');
            }
            if (!Schema::hasColumn('users', 'stripe_status')) {
                $table->string('stripe_status')->nullable()->default('incomplete')->after('stripe_account_id');
            }
            if (!Schema::hasColumn('users', 'stripe_onboarded_at')) {
                $table->timestamp('stripe_onboarded_at')->nullable()->after('stripe_status');
            }
            if (!Schema::hasColumn('users', 'charges_enabled')) {
                $table->boolean('charges_enabled')->default(false)->after('stripe_onboarded_at');
            }
            if (!Schema::hasColumn('users', 'payouts_enabled')) {
                $table->boolean('payouts_enabled')->default(false)->after('charges_enabled');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'stripe_account_id',
                'stripe_status',
                'stripe_onboarded_at',
                'charges_enabled',
                'payouts_enabled'
            ]);
        });
    }
};
