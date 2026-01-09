<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add temporary UUID column
        Schema::table('application_documents', function (Blueprint $table) {
            $table->uuid('id_new')->nullable();
        });

        // Populate id_new with UUIDs
        DB::statement("UPDATE application_documents SET id_new = gen_random_uuid()");

        // Drop the old primary key
        Schema::table('application_documents', function (Blueprint $table) {
            $table->dropPrimary();
            $table->dropColumn('id');
        });

        // Rename the new column to id and make it primary key
        Schema::table('application_documents', function (Blueprint $table) {
            $table->renameColumn('id_new', 'id');
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add temporary integer column
        Schema::table('application_documents', function (Blueprint $table) {
            $table->unsignedBigInteger('id_old');
        });

        // Generate sequential IDs for existing documents
        DB::table('application_documents')->orderBy('created_at')->get()->each(function ($document, $index) {
            DB::table('application_documents')
                ->where('id', $document->id)
                ->update(['id_old' => $index + 1]);
        });

        // Drop the current primary key
        Schema::table('application_documents', function (Blueprint $table) {
            $table->dropPrimary();
            $table->dropColumn('id');
        });

        // Rename the old column to id and make it primary key
        Schema::table('application_documents', function (Blueprint $table) {
            $table->renameColumn('id_old', 'id');
            $table->primary('id');
        });
    }
};
