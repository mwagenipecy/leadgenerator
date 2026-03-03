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
        Schema::create('credit_info_alert_settings', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id')->unique();
            $table->boolean('alert_when_credit_info_searched')->default(false);
            $table->boolean('alert_when_score_changed')->default(false);
            $table->boolean('alert_when_report_retrieved')->default(false);
            $table->boolean('alert_when_lender_can_find_loan')->default(false);
            $table->boolean('alert_when_reach_visible_notify_email')->default(false);
            $table->boolean('notify_via_email')->default(true);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_info_alert_settings');
    }
};
