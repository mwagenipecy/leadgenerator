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
        Schema::create('nida_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nida_number');
            $table->enum('status', ['pending', 'verified', 'failed', 'expired'])->default('pending');
            
            // QR Code verification fields
            $table->string('verification_token')->nullable();
            $table->timestamp('token_expires_at')->nullable();
            $table->boolean('phone_connected')->default(false);
            
            // Verification method and data
            $table->enum('verification_method', ['phone_photo', 'qr_code', 'mobile_photo', 'questionnaire'])->nullable();
            $table->string('photo_type')->nullable(); // 'id_document' or 'fingerprint'
            $table->string('photo_path')->nullable();
            $table->json('questionnaire_answers')->nullable();
            
            // Verification results
            $table->timestamp('verified_at')->nullable();
            $table->json('nida_response')->nullable();
            $table->integer('match_score')->nullable();
            $table->decimal('confidence_score', 5, 2)->nullable();
            
            // Session management
            $table->timestamp('expires_at');
            $table->timestamps();

            // Indexes for performance
            $table->index(['user_id', 'status']);
            $table->index('verification_token');
            $table->index('nida_number');
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nida_verifications');
    }
};
