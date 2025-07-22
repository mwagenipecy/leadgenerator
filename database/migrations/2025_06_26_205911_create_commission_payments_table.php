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
        Schema::create('commission_payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number')->unique();
            $table->foreignId('commission_bill_id')->constrained('commission_bills')->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->string('payment_method');
            $table->string('payment_reference')->nullable();
            $table->date('payment_date');
            $table->text('notes')->nullable();
            $table->json('payment_details')->nullable(); // Store additional payment info
            $table->foreignId('recorded_by')->constrained('users');
            $table->timestamps();

            $table->index('payment_number');
            $table->index(['commission_bill_id', 'payment_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_payments');
    }
};
