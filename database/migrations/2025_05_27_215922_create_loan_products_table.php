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
        Schema::create('loan_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lender_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('product_code')->unique();
            
            // Amount limits
            $table->decimal('min_amount', 15, 2);
            $table->decimal('max_amount', 15, 2);
            
            // Tenure limits (in months)
            $table->integer('min_tenure_months');
            $table->integer('max_tenure_months');
            
            // Interest rates
            $table->decimal('interest_rate_min', 5, 2);
            $table->decimal('interest_rate_max', 5, 2);
            $table->enum('interest_type', ['fixed', 'reducing', 'flat'])->default('reducing');
            
            // Employment requirements
            $table->enum('employment_requirement', ['employed', 'unemployed', 'all'])->default('all');
            $table->integer('min_employment_months')->nullable(); // Minimum employment period
            
            // Age requirements
            $table->integer('min_age')->default(18);
            $table->integer('max_age')->default(65);
            
            // Income requirements
            $table->decimal('min_monthly_income', 12, 2)->nullable();
            $table->decimal('max_debt_to_income_ratio', 5, 2)->nullable(); // Percentage
            
            // Credit requirements
            $table->integer('min_credit_score')->nullable();
            $table->boolean('allow_bad_credit')->default(false);
            
            // Fees and charges
            $table->decimal('processing_fee_percentage', 5, 2)->default(0);
            $table->decimal('processing_fee_fixed', 10, 2)->default(0);
            $table->decimal('late_payment_fee', 10, 2)->default(0);
            $table->decimal('early_repayment_fee_percentage', 5, 2)->default(0);
            
            // Collateral and guarantor requirements
            $table->boolean('requires_collateral')->default(false);
            $table->text('collateral_types')->nullable(); // JSON array
            $table->boolean('requires_guarantor')->default(false);
            $table->integer('min_guarantors')->default(0);
            
            // Documents required
            $table->json('required_documents'); // Dynamic document requirements
            
            // Processing details
            $table->integer('approval_time_days')->default(7);
            $table->integer('disbursement_time_days')->default(3);
            $table->json('disbursement_methods')->nullable(); // bank_transfer, mobile_money, cash
            
            // Product settings
            $table->boolean('is_active')->default(true);
            $table->boolean('auto_approval_eligible')->default(false);
            $table->decimal('auto_approval_max_amount', 15, 2)->nullable();
            
            // Additional terms and conditions
            $table->text('terms_and_conditions')->nullable();
            $table->text('eligibility_criteria')->nullable();
            $table->json('business_sectors_allowed')->nullable(); // For business loans
            
            // Marketing
            $table->string('promotional_tag')->nullable(); // "Best Rate", "Quick Approval", etc.
            $table->text('key_features')->nullable(); // JSON array of key selling points
            
            $table->timestamps();
            
            $table->foreign('lender_id')->references('id')->on('lenders')->onDelete('cascade');
            $table->index(['lender_id', 'is_active']);
            $table->index('product_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_products');
    }
};