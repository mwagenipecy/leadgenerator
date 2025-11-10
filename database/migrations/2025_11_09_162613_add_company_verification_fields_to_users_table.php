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
            $table->string('country')->nullable()->after('company_contact_nida');
            $table->string('passport_number')->nullable()->after('country');
            $table->enum('company_verification_status', ['pending', 'verified', 'rejected'])->default('pending')->after('verification_status');
            $table->timestamp('company_verified_at')->nullable()->after('company_verification_status');
            $table->foreignId('company_verified_by')->nullable()->constrained('users')->after('company_verified_at');
            $table->text('company_verification_notes')->nullable()->after('company_verified_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['company_verified_by']);
            $table->dropColumn([
                'country',
                'passport_number',
                'company_verification_status',
                'company_verified_at',
                'company_verified_by',
                'company_verification_notes'
            ]);
        });
    }
};
