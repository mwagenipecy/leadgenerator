<?php

namespace Database\Seeders;

use App\Models\LoanProduct;
use App\Models\Lender;
use App\Models\LoanCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoanProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create a lender
        $lender = Lender::where('status', 'approved')->first();
        
        if (!$lender) {
            // Create a test lender if none exists
            $lender = Lender::create([
                'company_name' => 'Test Financial Services',
                'license_number' => 'LIC-' . rand(1000, 9999),
                'contact_person' => 'John Doe',
                'email' => 'testlender@example.com',
                'phone' => '+255712345678',
                'address' => '123 Main Street',
                'city' => 'Dar es Salaam',
                'region' => 'Dar es Salaam',
                'postal_code' => '11101',
                'description' => 'A test lending institution for development purposes',
                'status' => 'approved',
                'approved_at' => now(),
            ]);
        }

        // Get admin user for created_by/updated_by
        $adminUser = User::where('role', 'super_admin')->first() 
            ?? User::where('role', 'admin')->first()
            ?? User::first();

        // Get loan categories
        $personalCategory = LoanCategory::where('slug', 'personal')->first();
        $businessCategory = LoanCategory::where('slug', 'business')->first();
        $autoCategory = LoanCategory::where('slug', 'auto')->first();
        $homeCategory = LoanCategory::where('slug', 'home')->first();
        $educationCategory = LoanCategory::where('slug', 'education')->first();
        $emergencyCategory = LoanCategory::where('slug', 'emergency')->first();

        // Define loan products with realistic data
        $products = [
            // Personal Loans
            [
                'lender_id' => $lender->id,
                'created_by' => $adminUser?->id,
                'updated_by' => $adminUser?->id,
                'name' => 'Quick Personal Loan',
                'description' => 'Fast and flexible personal loans for your immediate needs. No collateral required.',
                'loan_category_id' => $personalCategory?->id,
                'loan_type' => 'unsecured',
                'min_amount' => 500000,
                'max_amount' => 5000000,
                'min_tenure_months' => 3,
                'max_tenure_months' => 24,
                'interest_rate_min' => 12.0,
                'interest_rate_max' => 18.0,
                'interest_type' => 'reducing',
                'employment_requirement' => 'all',
                'min_age' => 18,
                'max_age' => 65,
                'min_monthly_income' => 500000,
                'minimum_dsr' => 40,
                'processing_fee_percentage' => 2.5,
                'processing_fee_fixed' => 0,
                'late_payment_fee' => 5000,
                'early_repayment_fee_percentage' => 0,
                'requires_collateral' => false,
                'requires_guarantor' => false,
                'required_documents' => ['national_id', 'proof_of_income', 'bank_statement'],
                'approval_time_days' => 3,
                'disbursement_time_days' => 1,
                'disbursement_methods' => ['bank_transfer', 'mobile_money'],
                'is_active' => true,
                'status' => 'active',
                'promotional_tag' => 'Fast Approval',
                'key_features' => ['No collateral required', 'Quick approval', 'Flexible repayment'],
                'terms_and_conditions' => 'Standard terms and conditions apply. Interest rates subject to credit assessment.',
                'eligibility_criteria' => 'Minimum age 18, maximum age 65. Minimum monthly income TSh 500,000.',
            ],
            [
                'lender_id' => $lender->id,
                'created_by' => $adminUser?->id,
                'updated_by' => $adminUser?->id,
                'name' => 'Premium Personal Loan',
                'description' => 'Higher loan amounts with competitive rates for established borrowers.',
                'loan_category_id' => $personalCategory?->id,
                'loan_type' => 'unsecured',
                'min_amount' => 2000000,
                'max_amount' => 20000000,
                'min_tenure_months' => 6,
                'max_tenure_months' => 36,
                'interest_rate_min' => 10.0,
                'interest_rate_max' => 15.0,
                'interest_type' => 'reducing',
                'employment_requirement' => 'employed',
                'min_age' => 21,
                'max_age' => 60,
                'min_monthly_income' => 1500000,
                'minimum_dsr' => 35,
                'processing_fee_percentage' => 1.5,
                'processing_fee_fixed' => 50000,
                'late_payment_fee' => 10000,
                'early_repayment_fee_percentage' => 1,
                'requires_collateral' => false,
                'requires_guarantor' => false,
                'required_documents' => ['national_id', 'proof_of_employment', 'salary_slip', 'bank_statement'],
                'approval_time_days' => 5,
                'disbursement_time_days' => 2,
                'disbursement_methods' => ['bank_transfer'],
                'is_active' => true,
                'status' => 'active',
                'promotional_tag' => 'Best Rate',
                'key_features' => ['Competitive rates', 'Higher loan amounts', 'Longer repayment terms'],
                'terms_and_conditions' => 'Premium terms apply. Credit check required.',
                'eligibility_criteria' => 'Minimum age 21, employed with minimum 6 months tenure. Minimum monthly income TSh 1,500,000.',
            ],
            // Business Loans
            [
                'lender_id' => $lender->id,
                'created_by' => $adminUser?->id,
                'updated_by' => $adminUser?->id,
                'name' => 'Small Business Loan',
                'description' => 'Supporting small businesses with working capital and expansion financing.',
                'loan_category_id' => $businessCategory?->id,
                'loan_type' => 'unsecured',
                'min_amount' => 1000000,
                'max_amount' => 10000000,
                'min_tenure_months' => 6,
                'max_tenure_months' => 48,
                'interest_rate_min' => 14.0,
                'interest_rate_max' => 20.0,
                'interest_type' => 'reducing',
                'employment_requirement' => 'business',
                'min_age' => 18,
                'max_age' => 70,
                'min_monthly_income' => 1000000,
                'minimum_dsr' => 45,
                'processing_fee_percentage' => 3.0,
                'processing_fee_fixed' => 0,
                'late_payment_fee' => 10000,
                'early_repayment_fee_percentage' => 2,
                'requires_collateral' => false,
                'requires_guarantor' => true,
                'min_guarantors' => 1,
                'required_documents' => ['business_license', 'tax_certificate', 'bank_statement', 'business_plan'],
                'approval_time_days' => 7,
                'disbursement_time_days' => 3,
                'disbursement_methods' => ['bank_transfer'],
                'is_active' => true,
                'status' => 'active',
                'promotional_tag' => 'Business Growth',
                'key_features' => ['Working capital support', 'Flexible terms', 'Business advisory'],
                'terms_and_conditions' => 'Business loans subject to business verification and guarantor requirements.',
                'eligibility_criteria' => 'Registered business with valid license. Minimum 6 months operation. Minimum monthly revenue TSh 1,000,000.',
            ],
            [
                'lender_id' => $lender->id,
                'created_by' => $adminUser?->id,
                'updated_by' => $adminUser?->id,
                'name' => 'Business Expansion Loan',
                'description' => 'Larger amounts for business expansion, equipment purchase, and major investments.',
                'loan_category_id' => $businessCategory?->id,
                'loan_type' => 'secured',
                'min_amount' => 5000000,
                'max_amount' => 50000000,
                'min_tenure_months' => 12,
                'max_tenure_months' => 60,
                'interest_rate_min' => 12.0,
                'interest_rate_max' => 18.0,
                'interest_type' => 'reducing',
                'employment_requirement' => 'business',
                'min_age' => 21,
                'max_age' => 65,
                'min_monthly_income' => 3000000,
                'minimum_dsr' => 40,
                'processing_fee_percentage' => 2.0,
                'processing_fee_fixed' => 100000,
                'late_payment_fee' => 20000,
                'early_repayment_fee_percentage' => 2.5,
                'requires_collateral' => true,
                'collateral_types' => ['property', 'equipment', 'inventory'],
                'requires_guarantor' => true,
                'min_guarantors' => 2,
                'required_documents' => ['business_license', 'tax_certificate', 'financial_statements', 'collateral_documents'],
                'approval_time_days' => 14,
                'disbursement_time_days' => 5,
                'disbursement_methods' => ['bank_transfer'],
                'is_active' => true,
                'status' => 'active',
                'promotional_tag' => 'Growth Capital',
                'key_features' => ['Large amounts', 'Long repayment terms', 'Collateral-based'],
                'terms_and_conditions' => 'Collateral required. Business must be operational for minimum 2 years.',
                'eligibility_criteria' => 'Established business with 2+ years operation. Minimum monthly revenue TSh 3,000,000. Collateral required.',
            ],
            // Auto Loans
            [
                'lender_id' => $lender->id,
                'created_by' => $adminUser?->id,
                'updated_by' => $adminUser?->id,
                'name' => 'Vehicle Finance',
                'description' => 'Finance your car, motorcycle, or commercial vehicle purchase.',
                'loan_category_id' => $autoCategory?->id,
                'loan_type' => 'secured',
                'min_amount' => 2000000,
                'max_amount' => 50000000,
                'min_tenure_months' => 12,
                'max_tenure_months' => 60,
                'interest_rate_min' => 11.0,
                'interest_rate_max' => 16.0,
                'interest_type' => 'reducing',
                'employment_requirement' => 'all',
                'min_age' => 18,
                'max_age' => 65,
                'min_monthly_income' => 800000,
                'minimum_dsr' => 40,
                'processing_fee_percentage' => 2.0,
                'processing_fee_fixed' => 50000,
                'late_payment_fee' => 10000,
                'early_repayment_fee_percentage' => 1.5,
                'requires_collateral' => true,
                'collateral_types' => ['vehicle'],
                'requires_guarantor' => false,
                'required_documents' => ['national_id', 'proof_of_income', 'vehicle_quotation', 'driving_license'],
                'approval_time_days' => 5,
                'disbursement_time_days' => 2,
                'disbursement_methods' => ['bank_transfer'],
                'is_active' => true,
                'status' => 'active',
                'promotional_tag' => 'Drive Today',
                'key_features' => ['Vehicle as collateral', 'Competitive rates', 'Quick processing'],
                'terms_and_conditions' => 'Vehicle will be registered as collateral. Insurance required.',
                'eligibility_criteria' => 'Valid driving license. Vehicle must be new or used (max 5 years old). Minimum monthly income TSh 800,000.',
            ],
            // Home Loans
            [
                'lender_id' => $lender->id,
                'created_by' => $adminUser?->id,
                'updated_by' => $adminUser?->id,
                'name' => 'Home Purchase Loan',
                'description' => 'Mortgage financing for home purchase with flexible repayment options.',
                'loan_category_id' => $homeCategory?->id,
                'loan_type' => 'secured',
                'min_amount' => 10000000,
                'max_amount' => 200000000,
                'min_tenure_months' => 60,
                'max_tenure_months' => 240,
                'interest_rate_min' => 9.0,
                'interest_rate_max' => 14.0,
                'interest_type' => 'reducing',
                'employment_requirement' => 'employed',
                'min_age' => 21,
                'max_age' => 60,
                'min_monthly_income' => 2000000,
                'minimum_dsr' => 35,
                'processing_fee_percentage' => 1.0,
                'processing_fee_fixed' => 200000,
                'late_payment_fee' => 25000,
                'early_repayment_fee_percentage' => 3,
                'requires_collateral' => true,
                'collateral_types' => ['property'],
                'requires_guarantor' => true,
                'min_guarantors' => 1,
                'required_documents' => ['national_id', 'proof_of_employment', 'property_valuation', 'title_deed'],
                'approval_time_days' => 21,
                'disbursement_time_days' => 7,
                'disbursement_methods' => ['bank_transfer'],
                'is_active' => true,
                'status' => 'active',
                'promotional_tag' => 'Own Your Home',
                'key_features' => ['Long repayment terms', 'Property as collateral', 'Low interest rates'],
                'terms_and_conditions' => 'Property will be registered as collateral. Property valuation required.',
                'eligibility_criteria' => 'Minimum age 21, employed with stable income. Minimum monthly income TSh 2,000,000. Property must be valued.',
            ],
            // Education Loans
            [
                'lender_id' => $lender->id,
                'created_by' => $adminUser?->id,
                'updated_by' => $adminUser?->id,
                'name' => 'Education Finance',
                'description' => 'Finance your education, training, or academic expenses with flexible repayment.',
                'loan_category_id' => $educationCategory?->id,
                'loan_type' => 'unsecured',
                'min_amount' => 500000,
                'max_amount' => 10000000,
                'min_tenure_months' => 6,
                'max_tenure_months' => 48,
                'interest_rate_min' => 10.0,
                'interest_rate_max' => 15.0,
                'interest_type' => 'reducing',
                'employment_requirement' => 'all',
                'min_age' => 18,
                'max_age' => 50,
                'min_monthly_income' => 500000,
                'minimum_dsr' => 40,
                'processing_fee_percentage' => 2.0,
                'processing_fee_fixed' => 0,
                'late_payment_fee' => 5000,
                'early_repayment_fee_percentage' => 0,
                'requires_collateral' => false,
                'requires_guarantor' => true,
                'min_guarantors' => 1,
                'required_documents' => ['national_id', 'admission_letter', 'fee_structure', 'guarantor_documents'],
                'approval_time_days' => 5,
                'disbursement_time_days' => 2,
                'disbursement_methods' => ['bank_transfer', 'mobile_money'],
                'is_active' => true,
                'status' => 'active',
                'promotional_tag' => 'Invest in Education',
                'key_features' => ['No collateral', 'Flexible repayment', 'Quick approval'],
                'terms_and_conditions' => 'Education loans require guarantor. Repayment starts after completion.',
                'eligibility_criteria' => 'Admission to recognized institution. Guarantor required. Minimum monthly income TSh 500,000.',
            ],
            // Emergency Loans
            [
                'lender_id' => $lender->id,
                'created_by' => $adminUser?->id,
                'updated_by' => $adminUser?->id,
                'name' => 'Emergency Quick Loan',
                'description' => 'Fast access to funds for urgent financial needs. Same-day approval possible.',
                'loan_category_id' => $emergencyCategory?->id,
                'loan_type' => 'unsecured',
                'min_amount' => 200000,
                'max_amount' => 3000000,
                'min_tenure_months' => 1,
                'max_tenure_months' => 12,
                'interest_rate_min' => 15.0,
                'interest_rate_max' => 24.0,
                'interest_type' => 'reducing',
                'employment_requirement' => 'all',
                'min_age' => 18,
                'max_age' => 65,
                'min_monthly_income' => 300000,
                'minimum_dsr' => 50,
                'processing_fee_percentage' => 5.0,
                'processing_fee_fixed' => 0,
                'late_payment_fee' => 10000,
                'early_repayment_fee_percentage' => 0,
                'requires_collateral' => false,
                'requires_guarantor' => false,
                'required_documents' => ['national_id', 'proof_of_income'],
                'approval_time_days' => 1,
                'disbursement_time_days' => 1,
                'disbursement_methods' => ['bank_transfer', 'mobile_money'],
                'is_active' => true,
                'status' => 'active',
                'promotional_tag' => 'Same Day Cash',
                'key_features' => ['Same-day approval', 'Quick disbursement', 'No collateral'],
                'terms_and_conditions' => 'Emergency loans have higher interest rates. Repayment must be completed within 12 months.',
                'eligibility_criteria' => 'Minimum age 18. Minimum monthly income TSh 300,000. Quick credit check required.',
            ],
        ];

        // Create loan products
        foreach ($products as $productData) {
            // Skip if product with same name already exists
            $existing = LoanProduct::where('name', $productData['name'])
                ->where('lender_id', $lender->id)
                ->first();
            
            if (!$existing) {
                LoanProduct::create($productData);
                $this->command->info("Created loan product: {$productData['name']}");
            } else {
                $this->command->warn("Loan product already exists: {$productData['name']}");
            }
        }

        $this->command->info('Loan products seeded successfully!');
    }
}

