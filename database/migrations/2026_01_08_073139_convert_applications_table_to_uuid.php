<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Safely drop a foreign key constraint if it exists
     */
    protected function dropForeignKeyIfExists(string $table, string $column): void
    {
        try {
            Schema::table($table, function (Blueprint $table) use ($column) {
                $table->dropForeign([$column]);
            });
        } catch (\Illuminate\Database\QueryException $e) {
            // Constraint doesn't exist or error occurred, ignore
        }
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop foreign keys that reference applications table
        $this->dropForeignKeyIfExists('credit_info_requests', 'loan_id');
        $this->dropForeignKeyIfExists('commission_bills', 'application_id');
        $this->dropForeignKeyIfExists('application_lender_submissions', 'application_id');
        $this->dropForeignKeyIfExists('integration_logs', 'application_id');
        $this->dropForeignKeyIfExists('commission_transactions', 'application_id');

        // Convert applications table to UUID
        // Step 1: Add a temporary UUID column
        Schema::table('applications', function (Blueprint $table) {
            $table->uuid('id_new')->after('id');
        });

        // Step 2: Generate UUIDs for existing applications and populate the new column
        DB::table('applications')->get()->each(function ($application) {
            DB::table('applications')
                ->where('id', $application->id)
                ->update(['id_new' => (string) Str::uuid()]);
        });

        // Step 3: Convert foreign key columns to text first, then update with new UUIDs, then convert to UUID type
        $applicationMappings = DB::table('applications')->pluck('id_new', 'id')->toArray();
        
        $tablesToUpdate = [
            'credit_info_requests' => ['loan_id'],
            'commission_bills' => ['application_id'],
            'application_lender_submissions' => ['application_id'],
            'integration_logs' => ['application_id'],
            'commission_transactions' => ['application_id'],
        ];

        foreach ($tablesToUpdate as $table => $columns) {
            foreach ($columns as $column) {
                // Convert to text first
                DB::statement("ALTER TABLE \"{$table}\" ALTER COLUMN \"{$column}\" TYPE text USING \"{$column}\"::text");
                
                // Update values with new UUIDs
                foreach ($applicationMappings as $oldId => $newUuid) {
                    DB::table($table)
                        ->where($column, (string)$oldId)
                        ->update([$column => $newUuid]);
                }
                
                // Convert to UUID type
                $nullableColumns = [
                    'credit_info_requests' => ['loan_id'],
                ];
                
                $isNullable = isset($nullableColumns[$table]) && in_array($column, $nullableColumns[$table]);
                
                DB::statement("ALTER TABLE \"{$table}\" ALTER COLUMN \"{$column}\" TYPE uuid USING \"{$column}\"::uuid");
                
                if ($isNullable) {
                    DB::statement("ALTER TABLE \"{$table}\" ALTER COLUMN \"{$column}\" DROP NOT NULL");
                }
            }
        }

        // Step 4: Drop the old id column and rename id_new to id
        Schema::table('applications', function (Blueprint $table) {
            // Drop the old primary key constraint
            $table->dropPrimary();
            
            // Drop the old id column
            $table->dropColumn('id');
        });

        // Step 5: Rename id_new to id and set as primary key
        DB::statement('ALTER TABLE applications RENAME COLUMN id_new TO id');
        
        Schema::table('applications', function (Blueprint $table) {
            $table->primary('id');
        });

        // Recreate foreign keys
        Schema::table('credit_info_requests', function (Blueprint $table) {
            $table->foreign('loan_id')->references('id')->on('applications')->onDelete('set null');
        });

        Schema::table('commission_bills', function (Blueprint $table) {
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');
        });

        Schema::table('application_lender_submissions', function (Blueprint $table) {
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');
        });

        Schema::table('integration_logs', function (Blueprint $table) {
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');
        });

        Schema::table('commission_transactions', function (Blueprint $table) {
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a complex migration to reverse. For safety, we'll leave it empty
        // and suggest restoring from backup if rollback is needed.
        throw new Exception('This migration cannot be reversed. Please restore from backup if needed.');
    }
};
