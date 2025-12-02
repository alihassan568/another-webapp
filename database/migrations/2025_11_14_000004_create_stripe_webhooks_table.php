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
        if (!Schema::hasTable('stripe_webhooks')) {
            Schema::create('stripe_webhooks', function (Blueprint $table) {
                $table->id();
                $table->string('stripe_id')->unique();
                $table->string('event_type');
                $table->json('payload');
                $table->boolean('processed')->default(false);
                $table->timestamps();

                $table->index('event_type');
                $table->index('processed');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stripe_webhooks');
    }
};
