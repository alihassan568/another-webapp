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
        Schema::create('admin_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_user_id'); // The admin who performed the action
            $table->unsignedBigInteger('invited_by_user_id')->nullable(); // The admin who invited this user
            $table->string('action'); // approve_item, reject_item, set_commission, invite_user, create_role
            $table->string('action_type')->nullable(); // approved, rejected, etc.
            $table->text('description'); // Human-readable description
            $table->unsignedBigInteger('target_id')->nullable(); // ID of the item/role/invite affected
            $table->string('target_type')->nullable(); // item, role, invite, user
            $table->json('metadata')->nullable(); // Additional data (old_value, new_value, etc.)
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('admin_user_id');
            $table->index('invited_by_user_id');
            $table->index('action');
            $table->index('target_id');
            $table->index('created_at');
            
            // Foreign keys
            $table->foreign('admin_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('invited_by_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_activity_logs');
    }
};
