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
        // Schema::create('application_lender_submissions', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestamps();
        // });


        Schema::create('application_lender_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('application_id')->constrained()->onDelete('cascade');
            $table->foreignId('lender_id')->constrained()->onDelete('cascade');
            $table->foreignId('loan_product_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('status', [
                'pending',
                'submitted', 
                'under_review',
                'approved',
                'rejected',
                'withdrawn',
                'expired'
            ])->default('pending');
            $table->json('submission_data')->nullable(); // Store lender-specific data
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('decision_at')->nullable();
            $table->json('lender_response')->nullable(); // Store lender's response/notes
            $table->decimal('offered_amount', 15, 2)->nullable();
            $table->decimal('offered_interest_rate', 5, 2)->nullable();
            $table->integer('offered_tenure_months')->nullable();
            $table->json('offered_terms')->nullable(); // Additional terms from lender
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            // Indexes for better performance
            $table->index(['user_id', 'status']);
            $table->index(['application_id', 'status']);
            $table->index(['lender_id', 'status']);
            $table->unique(['application_id', 'lender_id']); // Prevent duplicate submissions
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_lender_submissions');
    }
};
