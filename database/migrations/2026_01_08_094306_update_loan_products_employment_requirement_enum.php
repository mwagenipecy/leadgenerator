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
        // For PostgreSQL, we need to alter the enum type
        if (DB::getDriverName() === 'pgsql') {
            // First, update existing 'unemployed' values to 'business'
            DB::statement("UPDATE loan_products SET employment_requirement = 'business' WHERE employment_requirement = 'unemployed'");
            
            // Drop the old enum type and create a new one
            DB::statement("ALTER TABLE loan_products DROP CONSTRAINT IF EXISTS loan_products_employment_requirement_check");
            DB::statement("ALTER TABLE loan_products ADD CONSTRAINT loan_products_employment_requirement_check CHECK (employment_requirement IN ('employed', 'business', 'all'))");
        } else {
            // For MySQL, update existing values first
            DB::statement("UPDATE loan_products SET employment_requirement = 'business' WHERE employment_requirement = 'unemployed'");
            
            // Alter the enum
            DB::statement("ALTER TABLE loan_products MODIFY COLUMN employment_requirement ENUM('employed', 'business', 'all') DEFAULT 'all'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            // Update 'business' back to 'unemployed'
            DB::statement("UPDATE loan_products SET employment_requirement = 'unemployed' WHERE employment_requirement = 'business'");
            
            // Restore the old enum
            DB::statement("ALTER TABLE loan_products DROP CONSTRAINT IF EXISTS loan_products_employment_requirement_check");
            DB::statement("ALTER TABLE loan_products ADD CONSTRAINT loan_products_employment_requirement_check CHECK (employment_requirement IN ('employed', 'unemployed', 'all'))");
        } else {
            // For MySQL
            DB::statement("UPDATE loan_products SET employment_requirement = 'unemployed' WHERE employment_requirement = 'business'");
            DB::statement("ALTER TABLE loan_products MODIFY COLUMN employment_requirement ENUM('employed', 'unemployed', 'all') DEFAULT 'all'");
        }
    }
};
