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
        if (!Schema::hasTable('commission_settings')) {
            Schema::create('commission_settings', function (Blueprint $table) {
                $table->id();
                $table->decimal('default_commission', 5, 2)->default(10.00); // 10% default
                $table->json('tiered_commissions')->nullable();
                $table->timestamps();
            });

            // Insert default settings
            DB::table('commission_settings')->insert([
                'default_commission' => 10.00,
                'tiered_commissions' => json_encode([
                    [
                        'min_sales' => 0,
                        'max_sales' => 100000, // $1,000 in cents
                        'commission' => 10.00
                    ],
                    [
                        'min_sales' => 100001,
                        'max_sales' => 500000, // $5,000 in cents
                        'commission' => 8.00
                    ],
                    [
                        'min_sales' => 500001,
                        'max_sales' => null,
                        'commission' => 6.00
                    ]
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_settings');
    }
};
