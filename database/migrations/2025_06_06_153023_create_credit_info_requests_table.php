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
        Schema::create('credit_info_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_id')->nullable();
            $table->string('application_number')->nullable();
            $table->string('national_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('full_name');
            $table->date('date_of_birth');
            $table->string('phone_number');
            $table->string('message_id');
            $table->string('strategy_id')->default('2e1a9e93-0489-40e7-8fc2-185a21ae171a');
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('requested_at')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            $table->foreign('loan_id')->references('id')->on('applications')->onDelete('set null');
            $table->index(['loan_id', 'status']);
            $table->index(['national_id', 'created_at']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_info_requests');
    }
};
