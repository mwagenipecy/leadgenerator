<?php

namespace Database\Seeders;

use App\Models\LoanCategory;
use Illuminate\Database\Seeder;

class LoanCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Personal',
                'slug' => 'personal',
                'description' => 'General purpose loans for personal expenses, emergencies, or other individual needs.',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'description' => 'Loans to support business operations, expansion, or equipment purchase.',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Auto',
                'slug' => 'auto',
                'description' => 'Financing for vehicle purchases including cars, motorcycles, and commercial vehicles.',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Home',
                'slug' => 'home',
                'description' => 'Mortgage and home improvement loans for property purchase or renovation.',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Education',
                'slug' => 'education',
                'description' => 'Educational loans for tuition fees, training, and academic expenses.',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Agriculture',
                'slug' => 'agriculture',
                'description' => 'Specialized loans for farming equipment, seeds, livestock, and agricultural development.',
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Emergency',
                'slug' => 'emergency',
                'description' => 'Quick access loans for urgent financial needs and unexpected expenses.',
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'Debt Consolidation',
                'slug' => 'debt_consolidation',
                'description' => 'Loans to combine multiple debts into a single payment with better terms.',
                'is_active' => true,
                'sort_order' => 8,
            ],
        ];

        foreach ($categories as $category) {
            LoanCategory::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
