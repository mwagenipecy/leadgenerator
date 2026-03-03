<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('credit_info_services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique(); // maps to credit_info_alert_settings column
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->string('currency', 3)->default('TZS');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['is_active', 'sort_order']);
        });

        $this->seedDefaultServices();
    }

    private function seedDefaultServices(): void
    {
        $services = [
            ['name' => 'Credit info search alert', 'slug' => 'alert_when_credit_info_searched', 'description' => 'Notify when someone searches for credit info', 'price' => 500, 'sort_order' => 1],
            ['name' => 'Score change alert', 'slug' => 'alert_when_score_changed', 'description' => 'Notify when score is down or changed', 'price' => 1000, 'sort_order' => 2],
            ['name' => 'Report retrieved alert', 'slug' => 'alert_when_report_retrieved', 'description' => 'Notify when report is retrieved', 'price' => 750, 'sort_order' => 3],
            ['name' => 'Lender can find loan', 'slug' => 'alert_when_lender_can_find_loan', 'description' => 'Alert when lender with submitted data can find loan', 'price' => 1500, 'sort_order' => 4],
            ['name' => 'Reach visible & email notify', 'slug' => 'alert_when_reach_visible_notify_email', 'description' => 'When reach can see and get notified via email', 'price' => 500, 'sort_order' => 5],
        ];
        $now = now();
        foreach ($services as $s) {
            DB::table('credit_info_services')->insert(array_merge($s, [
                'currency' => 'TZS',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_info_services');
    }
};
