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
        Schema::create('integrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name'); // Integration name (e.g., "CRM Integration")
            $table->string('api_name')->unique(); // Unique API identifier
            $table->text('description')->nullable();
            $table->string('webhook_url'); // Target URL to send data
            $table->enum('http_method', ['POST', 'PUT', 'PATCH'])->default('POST');
            $table->string('auth_type')->default('basic'); // basic, bearer, api_key, none
            $table->string('auth_username')->nullable(); // For basic auth
            $table->string('auth_password')->nullable(); // For basic auth (encrypted)
            $table->string('auth_token')->nullable(); // For bearer token (encrypted)
            $table->string('api_key_header')->nullable(); // Header name for API key
            $table->string('api_key_value')->nullable(); // API key value (encrypted)
            $table->json('headers')->nullable(); // Additional headers
            $table->json('field_mappings'); // Which application fields to send
            $table->json('trigger_conditions')->nullable(); // When to trigger (status changes, etc.)
            $table->boolean('is_active')->default(true);
            $table->integer('timeout_seconds')->default(30); // Request timeout
            $table->integer('retry_attempts')->default(3); // Number of retries on failure
            $table->boolean('verify_ssl')->default(true); // SSL verification
            $table->string('content_type')->default('application/json'); // Content type for request
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'is_active']);
            $table->index('api_name');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('integrations');
    }
};
