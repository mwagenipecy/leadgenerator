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
        // Helper function to drop foreign key if exists
        $dropForeignKeyIfExists = function ($table, $column) {
            try {
                if (DB::getDriverName() === 'pgsql') {
                    // PostgreSQL: Get constraint name from information_schema
                    $constraint = DB::selectOne("
                        SELECT constraint_name 
                        FROM information_schema.table_constraints 
                        WHERE table_name = ? 
                        AND constraint_type = 'FOREIGN KEY'
                        AND constraint_name LIKE ?
                    ", [$table, "%{$column}_foreign"]);
                    
                    if ($constraint) {
                        DB::statement("ALTER TABLE {$table} DROP CONSTRAINT IF EXISTS {$constraint->constraint_name}");
                    }
                } else {
                    // MySQL/MariaDB
                    $foreignKeys = DB::select("
                        SELECT CONSTRAINT_NAME 
                        FROM information_schema.KEY_COLUMN_USAGE 
                        WHERE TABLE_SCHEMA = DATABASE() 
                        AND TABLE_NAME = ? 
                        AND COLUMN_NAME = ? 
                        AND REFERENCED_TABLE_NAME IS NOT NULL
                    ", [$table, $column]);
                    
                    foreach ($foreignKeys as $fk) {
                        DB::statement("ALTER TABLE {$table} DROP FOREIGN KEY {$fk->CONSTRAINT_NAME}");
                    }
                }
            } catch (Exception $e) {
                // Ignore if constraint doesn't exist
            }
        };

        // Drop foreign keys that reference loan_products.id
        $dropForeignKeyIfExists('applications', 'loan_product_id');
        $dropForeignKeyIfExists('application_lender_submissions', 'loan_product_id');

        // Add temporary UUID column
        Schema::table('loan_products', function (Blueprint $table) {
            $table->uuid('id_new')->nullable()->after('id');
        });

        // Populate id_new with UUIDs
        DB::statement("UPDATE loan_products SET id_new = gen_random_uuid()");

        // Update foreign key columns in related tables to text first, then UUID
        // Convert loan_product_id to text first
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE applications ALTER COLUMN loan_product_id TYPE text USING loan_product_id::text");
            DB::statement("ALTER TABLE application_lender_submissions ALTER COLUMN loan_product_id TYPE text USING loan_product_id::text");
        } else {
            Schema::table('applications', function (Blueprint $table) {
                $table->string('loan_product_id')->nullable()->change();
            });
            Schema::table('application_lender_submissions', function (Blueprint $table) {
                $table->string('loan_product_id')->nullable()->change();
            });
        }

        // Update foreign key references to point to new UUIDs
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("
                UPDATE applications a
                SET loan_product_id = lp.id_new::text
                FROM loan_products lp
                WHERE a.loan_product_id::text = lp.id::text
            ");

            DB::statement("
                UPDATE application_lender_submissions als
                SET loan_product_id = lp.id_new::text
                FROM loan_products lp
                WHERE als.loan_product_id::text = lp.id::text
            ");
        } else {
            $loanProductMappings = DB::table('loan_products')->pluck('id_new', 'id')->toArray();
            
            foreach ($loanProductMappings as $oldId => $newUuid) {
                DB::table('applications')
                    ->where('loan_product_id', (string)$oldId)
                    ->update(['loan_product_id' => $newUuid]);
                    
                DB::table('application_lender_submissions')
                    ->where('loan_product_id', (string)$oldId)
                    ->update(['loan_product_id' => $newUuid]);
            }
        }

        // Drop old primary key and id column
        Schema::table('loan_products', function (Blueprint $table) {
            $table->dropPrimary(['id']);
        });

        Schema::table('loan_products', function (Blueprint $table) {
            $table->dropColumn('id');
        });

        // Rename id_new to id and set as primary key
        Schema::table('loan_products', function (Blueprint $table) {
            $table->renameColumn('id_new', 'id');
        });

        Schema::table('loan_products', function (Blueprint $table) {
            $table->uuid('id')->primary()->change();
        });

        // Convert foreign key columns to UUID
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE applications ALTER COLUMN loan_product_id TYPE uuid USING loan_product_id::uuid");
            DB::statement("ALTER TABLE applications ALTER COLUMN loan_product_id DROP NOT NULL");
            DB::statement("ALTER TABLE application_lender_submissions ALTER COLUMN loan_product_id TYPE uuid USING loan_product_id::uuid");
            DB::statement("ALTER TABLE application_lender_submissions ALTER COLUMN loan_product_id DROP NOT NULL");
        } else {
            Schema::table('applications', function (Blueprint $table) {
                $table->uuid('loan_product_id')->nullable()->change();
            });
            Schema::table('application_lender_submissions', function (Blueprint $table) {
                $table->uuid('loan_product_id')->nullable()->change();
            });
        }

        // Recreate foreign keys
        Schema::table('applications', function (Blueprint $table) {
            $table->foreign('loan_product_id')->references('id')->on('loan_products')->onDelete('set null');
        });

        Schema::table('application_lender_submissions', function (Blueprint $table) {
            $table->foreign('loan_product_id')->references('id')->on('loan_products')->onDelete('set null');
        });

        // Add status field for soft delete
        Schema::table('loan_products', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive', 'deleted'])->default('active')->after('is_active');
        });

        // Migrate is_active to status
        DB::statement("UPDATE loan_products SET status = CASE WHEN is_active = true THEN 'active' ELSE 'inactive' END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a complex migration, rollback would require similar complexity
        // For now, we'll just note that rollback is not fully supported
        throw new Exception('Rollback not fully supported for UUID conversion');
    }
};
