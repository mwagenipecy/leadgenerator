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
        Schema::table('application_lender_submissions', function (Blueprint $table) {
            // Add new columns for booking functionality
            $table->decimal('booking_fee', 10, 2)->nullable();
            $table->timestamp('booked_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancellation_reason')->nullable();
            $table->text('notes')->nullable();

            // Add index for better query performance
          //  $table->index(['lender_id', 'status']);
           // $table->index(['application_id', 'status']);
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_lender_submissions', function (Blueprint $table) {
            //
        });
    }
};
