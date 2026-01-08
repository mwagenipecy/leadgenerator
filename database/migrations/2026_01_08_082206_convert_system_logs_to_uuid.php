<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
        // Drop foreign key
        $this->dropForeignKeyIfExists('system_logs', 'user_id');

        // Convert user_id to text first
        DB::statement('ALTER TABLE system_logs ALTER COLUMN user_id TYPE text USING user_id::text');
        
        // Clear any existing integer user_ids (they won't match UUID users anyway)
        DB::table('system_logs')->whereRaw("user_id !~ '^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$'")->update(['user_id' => null]);
        
        // Convert to UUID type
        DB::statement('ALTER TABLE system_logs ALTER COLUMN user_id TYPE uuid USING user_id::uuid');
        DB::statement('ALTER TABLE system_logs ALTER COLUMN user_id DROP NOT NULL');

        // Convert model_id to string to handle both UUIDs and integers (polymorphic)
        DB::statement('ALTER TABLE system_logs ALTER COLUMN model_id TYPE text USING model_id::text');

        // Recreate foreign key
        Schema::table('system_logs', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
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
