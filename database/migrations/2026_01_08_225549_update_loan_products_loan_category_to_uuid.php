<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Rename existing loan_category column to loan_category_name
        Schema::table('loan_products', function (Blueprint $table) {
            $table->renameColumn('loan_category', 'loan_category_name');
        });

        // Step 2: Add new loan_category_id column as UUID
        Schema::table('loan_products', function (Blueprint $table) {
            $table->uuid('loan_category_id')->nullable()->after('loan_category_name');
        });

        // Step 3: Migrate existing data: match category names to IDs
        DB::statement('
            UPDATE loan_products 
            SET loan_category_id = (
                SELECT id 
                FROM loan_categories 
                WHERE loan_categories.name = loan_products.loan_category_name
                LIMIT 1
            )
            WHERE loan_category_name IS NOT NULL
        ');

        // Step 4: Drop the old loan_category_name column
        Schema::table('loan_products', function (Blueprint $table) {
            $table->dropColumn('loan_category_name');
        });

        // Step 5: Add foreign key constraint
        Schema::table('loan_products', function (Blueprint $table) {
            $table->foreign('loan_category_id')
                  ->references('id')
                  ->on('loan_categories')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Step 1: Drop foreign key constraint
        Schema::table('loan_products', function (Blueprint $table) {
            $table->dropForeign(['loan_category_id']);
        });

        // Step 2: Add loan_category_name column
        Schema::table('loan_products', function (Blueprint $table) {
            $table->string('loan_category_name')->nullable()->after('loan_category_id');
        });

        // Step 3: Migrate data back: match IDs to names
        DB::statement('
            UPDATE loan_products 
            SET loan_category_name = (
                SELECT name 
                FROM loan_categories 
                WHERE loan_categories.id = loan_products.loan_category_id
                LIMIT 1
            )
            WHERE loan_category_id IS NOT NULL
        ');

        // Step 4: Drop loan_category_id column
        Schema::table('loan_products', function (Blueprint $table) {
            $table->dropColumn('loan_category_id');
        });

        // Step 5: Rename loan_category_name back to loan_category
        Schema::table('loan_products', function (Blueprint $table) {
            $table->renameColumn('loan_category_name', 'loan_category');
        });
    }
};
