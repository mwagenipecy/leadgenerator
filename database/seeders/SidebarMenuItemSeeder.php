<?php

namespace Database\Seeders;

use App\Models\SidebarMenuItem;
use Illuminate\Database\Seeder;

class SidebarMenuItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['key' => 'dashboard', 'label_key' => 'navigation.dashboard', 'route' => 'dashboard', 'icon' => 'dashboard', 'roles' => null, 'parent_key' => null, 'sort_order' => 10],
            ['key' => 'lead_management', 'label_key' => 'navigation.lead_management', 'route' => 'application.list', 'icon' => 'users', 'roles' => ['lender'], 'parent_key' => null, 'sort_order' => 20],
            ['key' => 'reports', 'label_key' => 'navigation.reports', 'route' => 'reports.booking', 'icon' => 'chart-bar', 'roles' => ['lender', 'super_admin'], 'parent_key' => null, 'sort_order' => 30],
            ['key' => 'user_profile', 'label_key' => 'navigation.user_profile', 'route' => 'loan-application.profile', 'icon' => 'user', 'roles' => ['borrower'], 'parent_key' => null, 'sort_order' => 40],
            ['key' => 'loan_applications', 'label_key' => 'navigation.loan_applications', 'route' => 'user.loan.application', 'icon' => 'document-text', 'roles' => ['borrower'], 'parent_key' => null, 'sort_order' => 50],
            ['key' => 'self_services', 'label_key' => 'navigation.self_services', 'route' => null, 'icon' => 'chart-bar', 'roles' => ['borrower'], 'parent_key' => null, 'sort_order' => 60],
            ['key' => 'admin_manager', 'label_key' => 'navigation.admin_manager', 'route' => null, 'icon' => 'users', 'roles' => ['super_admin'], 'parent_key' => null, 'sort_order' => 70],
            ['key' => 'company_verification', 'label_key' => 'navigation.company_verification', 'route' => 'admin.company.verification', 'icon' => 'check-circle', 'roles' => ['super_admin'], 'parent_key' => null, 'sort_order' => 80],
            ['key' => 'lender_management', 'label_key' => 'navigation.lender_management', 'route' => 'lenders.index', 'icon' => 'building', 'roles' => ['super_admin'], 'parent_key' => null, 'sort_order' => 90],
            ['key' => 'loan_products', 'label_key' => 'navigation.loan_products', 'route' => 'loan.product.index', 'icon' => 'star', 'roles' => ['lender'], 'parent_key' => null, 'sort_order' => 100],
            ['key' => 'blog', 'label_key' => 'landing.blog', 'route' => 'blog.index', 'icon' => 'newspaper', 'roles' => null, 'parent_key' => null, 'sort_order' => 110],
            ['key' => 'creditinfo_alert', 'label_key' => 'navigation.creditinfo_alert', 'route' => 'creditinfo.alert', 'icon' => 'bell', 'roles' => null, 'parent_key' => null, 'sort_order' => 120],
            ['key' => 'subscribe_to_services', 'label_key' => 'navigation.subscribe_to_services', 'route' => 'creditinfo.pricing', 'icon' => 'credit-card', 'roles' => null, 'parent_key' => null, 'sort_order' => 130],
            ['key' => 'integrations', 'label_key' => 'navigation.integrations', 'route' => 'webhook.integration', 'icon' => 'puzzle', 'roles' => ['lender', 'super_admin'], 'parent_key' => null, 'sort_order' => 140],
            ['key' => 'settings', 'label_key' => 'navigation.settings', 'route' => 'system.settings', 'icon' => 'cog', 'roles' => ['super_admin'], 'parent_key' => null, 'sort_order' => 150],
            ['key' => 'billing', 'label_key' => 'navigation.billing', 'route' => 'billing.section', 'icon' => 'credit-card', 'roles' => ['super_admin'], 'parent_key' => null, 'sort_order' => 160],
            ['key' => 'system_logs', 'label_key' => 'navigation.system_logs', 'route' => 'system.logs', 'icon' => 'document-text', 'roles' => ['super_admin'], 'parent_key' => null, 'sort_order' => 170],
            ['key' => 'loan_categories', 'label_key' => 'navigation.loan_categories', 'route' => 'admin.loan-categories.index', 'icon' => 'tag', 'roles' => ['super_admin'], 'parent_key' => null, 'sort_order' => 180],
            ['key' => 'regions', 'label_key' => 'navigation.regions', 'route' => 'admin.regions.index', 'icon' => 'map', 'roles' => ['super_admin'], 'parent_key' => null, 'sort_order' => 185],
            ['key' => 'blog_management', 'label_key' => 'navigation.blog_management', 'route' => 'admin.blog.management', 'icon' => 'pencil', 'roles' => ['super_admin'], 'parent_key' => null, 'sort_order' => 190],
            ['key' => 'hero_slider', 'label_key' => 'navigation.hero_slider', 'route' => 'admin.hero-slider.management', 'icon' => 'photo', 'roles' => ['super_admin'], 'parent_key' => null, 'sort_order' => 200],
            ['key' => 'promotions', 'label_key' => 'navigation.promotions', 'route' => 'admin.promotion.management', 'icon' => 'megaphone', 'roles' => ['super_admin'], 'parent_key' => null, 'sort_order' => 210],
            // Self services children
            ['key' => 'verify_tin', 'label_key' => 'verification.verify_tin_number', 'route' => 'taxpayer.verification', 'icon' => 'document-text', 'roles' => ['borrower'], 'parent_key' => 'self_services', 'sort_order' => 1],
            ['key' => 'verify_license', 'label_key' => 'verification.verify_license', 'route' => 'lincense.verification', 'icon' => 'identification', 'roles' => ['borrower'], 'parent_key' => 'self_services', 'sort_order' => 2],
            ['key' => 'verify_vehicle', 'label_key' => 'verification.verify_vehicle', 'route' => 'motor.vehicle.verification', 'icon' => 'truck', 'roles' => ['borrower'], 'parent_key' => 'self_services', 'sort_order' => 3],
            ['key' => 'credit_report', 'label_key' => 'verification.credit_report', 'route' => 'credit.report', 'icon' => 'document-text', 'roles' => ['borrower'], 'parent_key' => 'self_services', 'sort_order' => 4],
            // Admin manager children
            ['key' => 'user_management', 'label_key' => 'navigation.user_management', 'route' => 'user.management', 'icon' => 'users', 'roles' => ['super_admin'], 'parent_key' => 'admin_manager', 'sort_order' => 1],
            ['key' => 'roles', 'label_key' => 'navigation.roles', 'route' => 'user.management.roles', 'icon' => 'shield', 'roles' => ['super_admin'], 'parent_key' => 'admin_manager', 'sort_order' => 2],
            ['key' => 'permissions', 'label_key' => 'navigation.permissions', 'route' => 'user.management.permissions', 'icon' => 'lock', 'roles' => ['super_admin'], 'parent_key' => 'admin_manager', 'sort_order' => 3],
            ['key' => 'language_management', 'label_key' => 'admin.language_management', 'route' => 'admin.language.management', 'icon' => 'language', 'roles' => ['super_admin'], 'parent_key' => 'admin_manager', 'sort_order' => 4],
            ['key' => 'menu_management', 'label_key' => 'navigation.menu_management', 'route' => 'admin.menu.management', 'icon' => 'bars-3', 'roles' => ['super_admin'], 'parent_key' => 'admin_manager', 'sort_order' => 5],
        ];

        $byKey = [];
        foreach ($items as $item) {
            $parentKey = $item['parent_key'] ?? null;
            unset($item['parent_key']);
            $parentId = $parentKey && isset($byKey[$parentKey]) ? $byKey[$parentKey]->id : null;
            $model = SidebarMenuItem::updateOrCreate(
                ['key' => $item['key']],
                [
                    'label_key' => $item['label_key'],
                    'route' => $item['route'],
                    'icon' => $item['icon'],
                    'roles' => $item['roles'],
                    'parent_id' => $parentId,
                    'is_visible' => true,
                    'is_enabled' => true,
                    'sort_order' => $item['sort_order'],
                ]
            );
            $byKey[$item['key']] = $model;
        }

        SidebarMenuItem::clearMenuCache();
    }
}
