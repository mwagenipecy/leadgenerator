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
        Schema::create('commission_bills', function (Blueprint $table) {
            $table->id();
            $table->string('bill_number')->unique();
            $table->foreignId('application_id')->constrained('applications')->onDelete('cascade');
            $table->foreignId('lender_id')->constrained('lenders')->onDelete('cascade');
            $table->enum('commission_type', ['percentage', 'fixed']);
            $table->decimal('commission_rate', 8, 2)->nullable(); // percentage or fixed amount
            $table->decimal('loan_amount', 15, 2);
            $table->decimal('commission_amount', 12, 2);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->enum('status', ['pending', 'sent', 'paid', 'overdue', 'cancelled'])->default('pending');
            $table->date('due_date');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();

            $table->index(['status', 'due_date']);
            $table->index(['lender_id', 'status']);
            $table->index('bill_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_bills');
    }
};
