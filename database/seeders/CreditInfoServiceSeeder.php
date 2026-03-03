<?php

namespace Database\Seeders;

use App\Models\CreditInfoService;
use Illuminate\Database\Seeder;

class CreditInfoServiceSeeder extends Seeder
{
    /**
     * Default CreditInfo alert services (monthly pricing).
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Credit info search alert',
                'slug' => 'alert_when_credit_info_searched',
                'description' => 'Get notified when someone searches for credit information linked to your profile.',
                'price' => 500,
                'currency' => 'TZS',
                'price_interval' => 'monthly',
                'sort_order' => 1,
            ],
            [
                'name' => 'Score change alert',
                'slug' => 'alert_when_score_changed',
                'description' => 'Receive alerts when your credit score goes down or changes.',
                'price' => 1000,
                'currency' => 'TZS',
                'price_interval' => 'monthly',
                'sort_order' => 2,
            ],
            [
                'name' => 'Report retrieved alert',
                'slug' => 'alert_when_report_retrieved',
                'description' => 'Notify when your credit report is retrieved by a lender or system.',
                'price' => 750,
                'currency' => 'TZS',
                'price_interval' => 'monthly',
                'sort_order' => 3,
            ],
            [
                'name' => 'Lender can find loan',
                'slug' => 'alert_when_lender_can_find_loan',
                'description' => 'Alert when a lender with your submitted data can match you to a loan product.',
                'price' => 1500,
                'currency' => 'TZS',
                'price_interval' => 'monthly',
                'sort_order' => 4,
            ],
            [
                'name' => 'Reach visible & email notify',
                'slug' => 'alert_when_reach_visible_notify_email',
                'description' => 'When your reach/visibility is updated and you get notified via email.',
                'price' => 500,
                'currency' => 'TZS',
                'price_interval' => 'monthly',
                'sort_order' => 5,
            ],
        ];

        foreach ($services as $data) {
            CreditInfoService::updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, ['is_active' => true])
            );
        }
    }
}
