<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('credit_score')->nullable()->after('nida_verified_at');
            $table->timestamp('credit_score_updated_at')->nullable()->after('credit_score');
            $table->string('credit_score_rating', 50)->nullable()->after('credit_score_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['credit_score', 'credit_score_updated_at', 'credit_score_rating']);
        });
    }
};
