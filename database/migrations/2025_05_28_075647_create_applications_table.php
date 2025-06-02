<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('loan_product_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('lender_id')->nullable()->constrained()->onDelete('set null');
            
            // Application Status
            $table->enum('status', [
                'draft', 'submitted', 'under_review', 'approved', 
                'rejected', 'disbursed', 'cancelled'
            ])->default('draft');
            
            // Loan Details
            $table->decimal('requested_amount', 15, 2);
            $table->integer('requested_tenure_months');
            $table->string('loan_purpose')->nullable();
            
            // Personal Information
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('marital_status');
            $table->string('national_id');
            $table->string('phone_number');
            $table->string('email');
            
            // Address Information
            $table->string('current_address');
            $table->string('current_city');
            $table->string('current_region');
            $table->string('current_postal_code')->nullable();
            $table->integer('years_at_current_address');
            
            $table->string('permanent_address')->nullable();
            $table->string('permanent_city')->nullable();
            $table->string('permanent_region')->nullable();
            $table->boolean('is_permanent_same_as_current')->default(false);
            
            // Employment Information
            $table->enum('employment_status', [
                'employed', 'self_employed', 'unemployed', 'retired', 'student'
            ]);
            $table->string('employer_name')->nullable();
            $table->string('job_title')->nullable();
            $table->string('employment_sector')->nullable();
            $table->integer('years_of_employment')->nullable();
            $table->integer('months_with_current_employer')->nullable();
            $table->decimal('monthly_salary', 12, 2)->nullable();
            $table->decimal('other_monthly_income', 12, 2)->nullable();
            $table->string('salary_payment_method')->nullable(); // bank, cash, mobile_money
            
            // Business Information (for self-employed)
            $table->string('business_name')->nullable();
            $table->string('business_type')->nullable();
            $table->string('business_registration_number')->nullable();
            $table->integer('years_in_business')->nullable();
            $table->decimal('monthly_business_income', 12, 2)->nullable();
            $table->string('business_address')->nullable();
            
            // Financial Information
            $table->decimal('total_monthly_income', 12, 2);
            $table->decimal('monthly_expenses', 12, 2);
            $table->decimal('existing_loan_payments', 12, 2)->default(0);
            $table->decimal('debt_to_income_ratio', 5, 2)->nullable();
            $table->integer('credit_score')->nullable();
            $table->boolean('has_bad_credit_history')->default(false);
            
            // Bank Information
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_name')->nullable();
            $table->enum('account_type', ['savings', 'current'])->nullable();
            $table->integer('years_with_bank')->nullable();
            
            // Emergency Contact
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_relationship');
            $table->string('emergency_contact_phone');
            $table->string('emergency_contact_address')->nullable();
            
            // Loan Specific
            $table->json('collateral_offered')->nullable();
            $table->json('guarantors')->nullable();
            $table->string('preferred_disbursement_method')->nullable();
            
            // Application Processing
            $table->json('matching_products')->nullable(); // Store matched loan products
            $table->json('rejection_reasons')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('disbursed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            
            // Tracking
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->json('application_source')->nullable(); // web, mobile, agent
            
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'status']);
            $table->index(['loan_product_id', 'status']);
            $table->index(['lender_id', 'status']);
            $table->index('application_number');
            $table->index('submitted_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('applications');
    }
};