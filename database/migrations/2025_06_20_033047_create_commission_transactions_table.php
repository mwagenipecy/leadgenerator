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
        Schema::create('commission_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number')->unique();
            $table->unsignedBigInteger('application_id');
            $table->unsignedBigInteger('lender_id');
            $table->decimal('loan_amount', 15, 2);
            $table->decimal('commission_amount', 15, 2);
            $table->string('commission_type'); // percentage or fixed
            $table->decimal('commission_rate', 5, 2)->nullable(); // If percentage
            $table->enum('status', ['pending', 'paid', 'overdue', 'cancelled'])->default('pending');
            $table->date('due_date');
            $table->date('paid_date')->nullable();
            $table->decimal('penalty_amount', 15, 2)->default(0);
            $table->text('payment_method')->nullable();
            $table->text('payment_reference')->nullable();
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable(); // For additional transaction data
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');
            $table->foreign('lender_id')->references('id')->on('lenders')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            
            $table->index(['lender_id', 'status']);
            $table->index(['due_date', 'status']);
            $table->index('transaction_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_transactions');
    }
};
