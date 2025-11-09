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
        Schema::create('report_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('creditinfo_id'); // CreditInfo individual ID
            $table->string('report_url')->nullable(); // URL to the generated PDF report
            $table->string('search_full_name')->nullable(); // Search criteria used
            $table->string('search_id_number')->nullable();
            $table->string('search_phone_number')->nullable();
            $table->string('individual_full_name')->nullable(); // Individual's name from search results
            $table->string('individual_national_id')->nullable();
            $table->string('individual_date_of_birth')->nullable();
            $table->text('search_criteria')->nullable(); // JSON or text representation of search criteria
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->enum('status', ['success', 'failed', 'pending'])->default('success');
            $table->text('error_message')->nullable();
            $table->timestamp('retrieved_at')->useCurrent();
            $table->timestamps();
            
            // Indexes for performance
            $table->index('user_id');
            $table->index('creditinfo_id');
            $table->index('retrieved_at');
            $table->index('status');
            $table->index(['user_id', 'retrieved_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_logs');
    }
};
