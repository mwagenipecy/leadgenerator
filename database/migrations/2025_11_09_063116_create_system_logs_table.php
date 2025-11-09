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
        Schema::create('system_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action'); // e.g., 'user_created', 'user_updated', 'user_deleted', 'login', 'logout', 'role_assigned', etc.
            $table->string('model_type')->nullable(); // e.g., 'User', 'Application', 'Lender'
            $table->unsignedBigInteger('model_id')->nullable(); // ID of the affected model
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->text('description')->nullable();
            $table->json('old_values')->nullable(); // Store old values before change
            $table->json('new_values')->nullable(); // Store new values after change
            $table->json('metadata')->nullable(); // Additional context data
            $table->string('request_method')->nullable(); // GET, POST, PUT, DELETE
            $table->string('request_url')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index('user_id');
            $table->index('action');
            $table->index('severity');
            $table->index('model_type');
            $table->index('created_at');
            $table->index(['model_type', 'model_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_logs');
    }
};
