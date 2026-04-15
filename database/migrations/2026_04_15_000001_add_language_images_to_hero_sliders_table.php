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
        Schema::table('hero_sliders', function (Blueprint $table) {
            $table->string('image_path_en')->nullable()->after('image_path');
            $table->string('image_path_sw')->nullable()->after('image_path_en');
        });

        DB::table('hero_sliders')
            ->whereNull('image_path_en')
            ->update(['image_path_en' => DB::raw('image_path')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hero_sliders', function (Blueprint $table) {
            $table->dropColumn(['image_path_en', 'image_path_sw']);
        });
    }
};
