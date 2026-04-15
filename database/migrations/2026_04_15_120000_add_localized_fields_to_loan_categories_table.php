<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loan_categories', function (Blueprint $table) {
            $table->string('name_en')->nullable()->after('name');
            $table->string('name_sw')->nullable()->after('name_en');
            $table->text('description_en')->nullable()->after('description');
            $table->text('description_sw')->nullable()->after('description_en');
        });

        DB::table('loan_categories')->update([
            'name_en' => DB::raw('name'),
            'name_sw' => DB::raw('name'),
            'description_en' => DB::raw('description'),
            'description_sw' => DB::raw('description'),
        ]);

        Schema::table('loan_categories', function (Blueprint $table) {
            $table->unique('name_en');
            $table->unique('name_sw');
        });
    }

    public function down(): void
    {
        Schema::table('loan_categories', function (Blueprint $table) {
            $table->dropUnique(['name_en']);
            $table->dropUnique(['name_sw']);
            $table->dropColumn(['name_en', 'name_sw', 'description_en', 'description_sw']);
        });
    }
};
