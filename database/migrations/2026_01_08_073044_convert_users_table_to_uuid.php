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
        $connection = Schema::getConnection();
        $driverName = $connection->getDriverName();
        
        if ($driverName === 'pgsql') {
            // For PostgreSQL, find the constraint name dynamically
            $constraint = DB::selectOne("
                SELECT tc.constraint_name
                FROM information_schema.table_constraints AS tc
                JOIN information_schema.key_column_usage AS kcu
                    ON tc.constraint_name = kcu.constraint_name
                    AND tc.table_schema = kcu.table_schema
                WHERE tc.constraint_type = 'FOREIGN KEY'
                    AND tc.table_name = ?
                    AND kcu.column_name = ?
            ", [$table, $column]);
            
            if ($constraint && isset($constraint->constraint_name)) {
                DB::statement("ALTER TABLE \"{$table}\" DROP CONSTRAINT IF EXISTS \"{$constraint->constraint_name}\"");
            }
        } else {
            // For other databases, try to drop and catch exception
            try {
                Schema::table($table, function (Blueprint $table) use ($column) {
                    $table->dropForeign([$column]);
                });
            } catch (\Illuminate\Database\QueryException $e) {
                // Constraint doesn't exist, ignore
            }
        }
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop foreign keys that reference users table
        $this->dropForeignKeyIfExists('applications', 'user_id');
        $this->dropForeignKeyIfExists('applications', 'reviewed_by');

        $this->dropForeignKeyIfExists('nida_verifications', 'user_id');
        $this->dropForeignKeyIfExists('application_documents', 'verified_by');
        $this->dropForeignKeyIfExists('user_otps', 'user_id');
        $this->dropForeignKeyIfExists('user_profiles', 'user_id');
        $this->dropForeignKeyIfExists('user_roles', 'user_id');
        $this->dropForeignKeyIfExists('user_roles', 'assigned_by');
        $this->dropForeignKeyIfExists('user_permissions', 'user_id');
        $this->dropForeignKeyIfExists('user_permissions', 'assigned_by');
        $this->dropForeignKeyIfExists('company_verification_documents', 'user_id');
        $this->dropForeignKeyIfExists('company_verification_documents', 'verified_by');
        $this->dropForeignKeyIfExists('system_logs', 'user_id');
        $this->dropForeignKeyIfExists('report_logs', 'user_id');
        $this->dropForeignKeyIfExists('credit_report_search_logs', 'user_id');
        $this->dropForeignKeyIfExists('lenders', 'approved_by');
        $this->dropForeignKeyIfExists('lenders', 'user_id');
        $this->dropForeignKeyIfExists('integrations', 'user_id');
        $this->dropForeignKeyIfExists('commission_transactions', 'created_by');
        $this->dropForeignKeyIfExists('lender_commission_settings', 'updated_by');
        $this->dropForeignKeyIfExists('system_settings', 'updated_by');
        $this->dropForeignKeyIfExists('commission_bills', 'created_by');
        $this->dropForeignKeyIfExists('commission_bills', 'updated_by');
        $this->dropForeignKeyIfExists('commission_payments', 'recorded_by');
        $this->dropForeignKeyIfExists('application_lender_submissions', 'user_id');
        $this->dropForeignKeyIfExists('users', 'company_verified_by');
        $this->dropForeignKeyIfExists('sessions', 'user_id');

        // Convert users table to UUID
        // Step 1: Add a temporary UUID column
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('id_new')->after('id');
        });

        // Step 2: Generate UUIDs for existing users and populate the new column
        DB::table('users')->get()->each(function ($user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['id_new' => (string) Str::uuid()]);
        });

        // Step 3: Convert foreign key columns to text first (to allow UUID strings)
        // Then update with new UUIDs, then convert to UUID type
        $userMappings = DB::table('users')->pluck('id_new', 'id')->toArray();
        
        // Convert columns to text/varchar first, update values, then convert to UUID
        $tablesToUpdate = [
            'applications' => ['user_id', 'reviewed_by'],
            'nida_verifications' => ['user_id'],
            'application_documents' => ['verified_by'],
            'user_otps' => ['user_id'],
            'user_profiles' => ['user_id'],
            'user_roles' => ['user_id', 'assigned_by'],
            'user_permissions' => ['user_id', 'assigned_by'],
            'company_verification_documents' => ['user_id', 'verified_by'],
            'system_logs' => ['user_id'],
            'report_logs' => ['user_id'],
            'credit_report_search_logs' => ['user_id'],
            'lenders' => ['approved_by', 'user_id'],
            'integrations' => ['user_id'],
            'commission_transactions' => ['created_by'],
            'lender_commission_settings' => ['updated_by'],
            'system_settings' => ['updated_by'],
            'commission_bills' => ['created_by', 'updated_by'],
            'commission_payments' => ['recorded_by'],
            'application_lender_submissions' => ['user_id'],
            'users' => ['company_verified_by'],
            'sessions' => ['user_id'],
        ];

        foreach ($tablesToUpdate as $table => $columns) {
            foreach ($columns as $column) {
                // Convert to text first
                DB::statement("ALTER TABLE \"{$table}\" ALTER COLUMN \"{$column}\" TYPE text USING \"{$column}\"::text");
                
                // Update values with new UUIDs
                foreach ($userMappings as $oldId => $newUuid) {
                    DB::table($table)
                        ->where($column, (string)$oldId)
                        ->update([$column => $newUuid]);
                }
                
                // Convert to UUID type
                // Check if column should be nullable
                $nullableColumns = [
                    'applications' => ['reviewed_by'],
                    'application_documents' => ['verified_by'],
                    'users' => ['company_verified_by'],
                    'sessions' => ['user_id'],
                    'system_logs' => ['user_id'],
                    'report_logs' => ['user_id'],
                    'credit_report_search_logs' => ['user_id'],
                    'lenders' => ['approved_by', 'user_id'],
                    'commission_transactions' => ['created_by'],
                    'lender_commission_settings' => ['updated_by'],
                    'system_settings' => ['updated_by'],
                    'commission_bills' => ['updated_by'],
                    'user_roles' => ['assigned_by'],
                    'user_permissions' => ['assigned_by'],
                    'company_verification_documents' => ['verified_by'],
                ];
                
                $isNullable = isset($nullableColumns[$table]) && in_array($column, $nullableColumns[$table]);
                
                DB::statement("ALTER TABLE \"{$table}\" ALTER COLUMN \"{$column}\" TYPE uuid USING \"{$column}\"::uuid");
                
                if ($isNullable) {
                    DB::statement("ALTER TABLE \"{$table}\" ALTER COLUMN \"{$column}\" DROP NOT NULL");
                }
            }
        }

        // Step 4: Drop the old id column and rename id_new to id
        Schema::table('users', function (Blueprint $table) {
            // Drop the old primary key constraint
            $table->dropPrimary();
            
            // Drop the old id column
            $table->dropColumn('id');
        });

        // Step 5: Rename id_new to id and set as primary key
        DB::statement('ALTER TABLE users RENAME COLUMN id_new TO id');
        
        Schema::table('users', function (Blueprint $table) {
            $table->primary('id');
        });

        // Foreign key columns are already converted to UUID in Step 3 above

        // Recreate foreign keys
        Schema::table('applications', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('reviewed_by')->references('id')->on('users');
        });

        Schema::table('nida_verifications', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('application_documents', function (Blueprint $table) {
            $table->foreign('verified_by')->references('id')->on('users');
        });

        Schema::table('user_otps', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('user_profiles', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('user_roles', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('assigned_by')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('user_permissions', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('assigned_by')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('company_verification_documents', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('verified_by')->references('id')->on('users');
        });

        Schema::table('system_logs', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('report_logs', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('credit_report_search_logs', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('lenders', function (Blueprint $table) {
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::table('integrations', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('commission_transactions', function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('lender_commission_settings', function (Blueprint $table) {
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('system_settings', function (Blueprint $table) {
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('commission_bills', function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });

        Schema::table('commission_payments', function (Blueprint $table) {
            $table->foreign('recorded_by')->references('id')->on('users');
        });

        Schema::table('application_lender_submissions', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('company_verified_by')->references('id')->on('users');
        });

        Schema::table('sessions', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
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
