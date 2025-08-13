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
              // Create user_profiles table for storing user basic information
              Schema::create('user_profiles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
                
                // Personal Information
                $table->string('first_name')->nullable();
                $table->string('middle_name')->nullable();
                $table->string('last_name')->nullable();
                $table->date('date_of_birth')->nullable();
                $table->enum('gender', ['male', 'female', 'other'])->nullable();
                $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed', 'separated'])->nullable();
                $table->string('national_id', 50)->nullable();
                $table->string('phone_number', 20)->nullable();
                $table->string('email')->nullable();
                
                // Address Information
                $table->text('current_address')->nullable();
                $table->string('current_city')->nullable();
                $table->string('current_region')->nullable();
                $table->string('current_postal_code')->nullable();
                $table->integer('years_at_current_address')->default(0);
                $table->boolean('is_permanent_same_as_current')->default(true);
                $table->text('permanent_address')->nullable();
                $table->string('permanent_city')->nullable();
                $table->string('permanent_region')->nullable();
                
                // Employment Information
                $table->enum('employment_status', ['employed', 'self_employed', 'unemployed', 'retired', 'student'])->nullable();
                $table->string('employer_name')->nullable();
                $table->string('job_title')->nullable();
                $table->string('employment_sector')->nullable();
                $table->integer('years_of_employment')->default(0);
                $table->integer('months_with_current_employer')->default(0);
                
                // Business Information (for self-employed)
                $table->string('business_name')->nullable();
                $table->string('business_type')->nullable();
                $table->string('business_registration_number')->nullable();
                $table->integer('years_in_business')->default(0);
                $table->text('business_address')->nullable();
                
                // Financial Information
                $table->decimal('monthly_salary', 15, 2)->default(0);
                $table->decimal('other_monthly_income', 15, 2)->default(0);
                $table->decimal('monthly_business_income', 15, 2)->default(0);
                $table->decimal('total_monthly_income', 15, 2)->default(0);
                $table->decimal('monthly_expenses', 15, 2)->default(0);
                $table->decimal('existing_loan_payments', 15, 2)->default(0);
                $table->integer('credit_score')->nullable();
                $table->boolean('has_bad_credit_history')->default(false);
                
                // Bank Information
                $table->boolean('has_bank_account')->default(true);
                $table->string('bank_name')->nullable();
                $table->string('account_number')->nullable();
                $table->string('account_name')->nullable();
                $table->enum('account_type', ['savings', 'current'])->nullable();
                $table->integer('years_with_bank')->default(0);
                
                // Emergency Contact
                $table->string('emergency_contact_name')->nullable();
                $table->string('emergency_contact_relationship')->nullable();
                $table->string('emergency_contact_phone')->nullable();
                $table->text('emergency_contact_address')->nullable();
                
                // Preferences
                $table->enum('preferred_disbursement_method', ['bank_transfer', 'mobile_money', 'cash', 'check'])->default('bank_transfer');
                
                // Profile completion tracking
                $table->integer('profile_completion_percentage')->default(0);
                $table->timestamp('last_updated')->nullable();
                
                $table->timestamps();
                
                // Indexes
                $table->index(['user_id', 'profile_completion_percentage']);
            });
            
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
