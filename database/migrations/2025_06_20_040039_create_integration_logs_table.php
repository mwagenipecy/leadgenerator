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
        Schema::create('integration_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('integration_id');
            $table->unsignedBigInteger('application_id');
            $table->string('trigger_event'); // offer_accepted, status_changed, etc.
            $table->enum('status', ['pending', 'success', 'failed', 'retrying'])->default('pending');
            $table->json('request_payload'); // Data sent to webhook
            $table->text('request_url');
            $table->json('request_headers')->nullable();
            $table->text('response_body')->nullable();
            $table->integer('response_status')->nullable(); // HTTP status code
            $table->json('response_headers')->nullable();
            $table->text('error_message')->nullable();
            $table->integer('retry_count')->default(0);
            $table->timestamp('last_attempt_at')->nullable();
            $table->timestamp('next_retry_at')->nullable();
            $table->decimal('response_time_ms', 8, 2)->nullable(); // Response time in milliseconds
            $table->timestamps();

            $table->foreign('integration_id')->references('id')->on('integrations')->onDelete('cascade');
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');
            $table->index(['integration_id', 'status']);
            $table->index(['application_id', 'trigger_event']);
            $table->index('status');
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('integration_logs');
    }
};
