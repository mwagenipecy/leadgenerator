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
        Schema::table('credit_info_services', function (Blueprint $table) {
            $table->string('price_interval', 20)->default('monthly')->after('currency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('credit_info_services', function (Blueprint $table) {
            $table->dropColumn('price_interval');
        });
    }
};
