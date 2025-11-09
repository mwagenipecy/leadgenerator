<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('registration_type', ['individual','company'])->default('individual')->after('verification_status');
            $table->string('company_name')->nullable()->after('registration_type');
            $table->string('company_tin')->nullable()->after('company_name');
            $table->string('company_contact_nida', 20)->nullable()->after('company_tin');
            $table->timestamp('nida_verified_at')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['registration_type','company_name','company_tin','company_contact_nida']);
        });
    }
};


