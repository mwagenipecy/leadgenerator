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
        Schema::create('transaction_analyses', function (Blueprint $table) {
            $table->id();
            $table->string('account_number');
            $table->enum('status', ['success', 'failed'])->default('success');
            $table->json('profile_data')->nullable();
            $table->json('analysis_1d')->nullable();
            $table->json('analysis_2d')->nullable();
            $table->json('analysis_3d')->nullable();
            $table->json('affordability_scores')->nullable();
            $table->json('full_response'); // Store complete response for reference
            $table->timestamps();
            
            $table->index('account_number');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_analyses');
    }
};
