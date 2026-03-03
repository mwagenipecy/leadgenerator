<?php

use App\Models\SidebarMenuItem;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        SidebarMenuItem::firstOrCreate(
            ['key' => 'regions'],
            [
                'label_key' => 'navigation.regions',
                'route' => 'admin.regions.index',
                'icon' => 'map',
                'roles' => ['super_admin'],
                'parent_id' => null,
                'is_visible' => true,
                'is_enabled' => true,
                'sort_order' => 185,
            ]
        );
        SidebarMenuItem::clearMenuCache();
    }

    public function down(): void
    {
        SidebarMenuItem::where('key', 'regions')->delete();
        SidebarMenuItem::clearMenuCache();
    }
};
