<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('application_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->onDelete('cascade');
            
            // Document Information
            $table->string('document_type'); // national_id, salary_slip, bank_statement, etc.
            $table->string('document_name'); // Original filename
            $table->string('file_path'); // Storage path
            $table->string('file_type'); // pdf, jpg, png, etc.
            $table->integer('file_size'); // in bytes
            $table->string('mime_type');
            
            // Document Status
            $table->enum('status', ['uploaded', 'verified', 'rejected', 'expired'])->default('uploaded');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            
            // Document Metadata
            $table->date('document_date')->nullable(); // Date on the document
            $table->date('expiry_date')->nullable(); // For documents that expire
            $table->json('extracted_data')->nullable(); // OCR or manual data entry
            $table->boolean('is_required')->default(true);
            
            // Security
            $table->string('file_hash')->nullable(); // For integrity checking
            $table->boolean('is_encrypted')->default(false);
            
            $table->timestamps();
            
            // Indexes
            $table->index(['application_id', 'document_type']);
            $table->index(['status', 'document_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('application_documents');
    }
};