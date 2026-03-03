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
        Schema::table('credit_info_alert_settings', function (Blueprint $table) {
            $table->json('subscription_periods')->nullable()->after('notify_via_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('credit_info_alert_settings', function (Blueprint $table) {
            $table->dropColumn('subscription_periods');
        });
    }
};
