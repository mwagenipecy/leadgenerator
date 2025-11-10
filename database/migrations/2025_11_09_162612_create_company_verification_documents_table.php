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
        Schema::create('company_verification_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('document_type', ['brela', 'tin_certificate', 'passport', 'personal_kyc', 'company_documents', 'nida_verification'])->nullable();
            $table->string('document_name');
            $table->string('file_path');
            $table->string('file_type'); // pdf, jpg, jpeg, png
            $table->integer('file_size'); // in bytes
            $table->string('mime_type');
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->string('file_hash')->nullable(); // For integrity checking
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'document_type']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_verification_documents');
    }
};
