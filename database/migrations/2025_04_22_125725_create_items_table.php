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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->string('sub_category')->nullable();
            $table->string('name');
            $table->string('description');
            $table->integer('quantity')->nullable();
            $table->integer('price');
            $table->string('image')->nullable();
            $table->decimal('discount_percentage', 5, 2)->default(0.00); 
            $table->string('valid_from')->nullable();
            $table->string('valid_until')->nullable();
            $table->string('pickup_start_time')->nullable();
            $table->string('pickup_end_time')->nullable();
            $table->double('commission')->default(0.00);
            $table->double('requested_commission')->default(0.00);
            $table->enum('commission_status',['pending','approved','rejected'])->nullable();
            $table->integer('user_id');
            $table->text('rejection_reason')->nullable();
            $table->enum('status',['pending','approved','rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
