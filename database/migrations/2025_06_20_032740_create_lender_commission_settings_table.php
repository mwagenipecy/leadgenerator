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
        Schema::create('lender_commission_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lender_id');
            $table->enum('commission_type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('commission_percentage', 5, 2)->default(0); // 0.00 to 999.99
            $table->decimal('commission_fixed_amount', 15, 2)->default(0); // Up to 999,999,999,999.99
            $table->decimal('minimum_amount', 15, 2)->default(0);
            $table->decimal('maximum_amount', 15, 2)->nullable();
            $table->text('special_terms')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('lender_id')->references('id')->on('lenders')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->unique('lender_id'); // One setting per lender
            $table->index(['lender_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lender_commission_settings');
    }
};
