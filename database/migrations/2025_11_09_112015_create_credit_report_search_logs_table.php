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
        Schema::create('credit_report_search_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('search_full_name')->nullable();
            $table->string('search_id_number')->nullable();
            $table->string('search_phone_number')->nullable();
            $table->string('search_id_number_type')->default('NationalID');
            $table->text('search_criteria')->nullable(); // JSON representation
            $table->integer('results_count')->default(0);
            $table->enum('status', ['success', 'failed', 'no_results'])->default('success');
            $table->text('error_message')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('searched_at')->useCurrent();
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('searched_at');
            $table->index('status');
            $table->index(['user_id', 'searched_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_report_search_logs');
    }
};
