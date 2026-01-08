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

    public function up(): void
    {
        // Drop foreign keys
        $this->dropForeignKeyIfExists('lenders', 'approved_by');
        $this->dropForeignKeyIfExists('lenders', 'user_id');

        // Convert approved_by to text first, then to UUID
        DB::statement('ALTER TABLE lenders ALTER COLUMN approved_by TYPE text USING approved_by::text');
        DB::statement('ALTER TABLE lenders ALTER COLUMN approved_by TYPE uuid USING CASE WHEN approved_by ~ \'^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$\' THEN approved_by::uuid ELSE NULL END');

        // Convert user_id to text first, then to UUID
        DB::statement('ALTER TABLE lenders ALTER COLUMN user_id TYPE text USING user_id::text');
        DB::statement('ALTER TABLE lenders ALTER COLUMN user_id TYPE uuid USING CASE WHEN user_id ~ \'^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$\' THEN user_id::uuid ELSE NULL END');

        // Recreate foreign keys
        Schema::table('lenders', function (Blueprint $table) {
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
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
