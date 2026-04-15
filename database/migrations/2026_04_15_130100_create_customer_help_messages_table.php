<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_help_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_help_request_id')->constrained('customer_help_requests')->cascadeOnDelete();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('sender_type')->default('user');
            $table->text('message');
            $table->timestamps();
        });

        DB::table('customer_help_requests')
            ->select(['id', 'user_id', 'message', 'created_at', 'updated_at'])
            ->orderBy('id')
            ->chunkById(200, function ($requests) {
                $insert = [];
                foreach ($requests as $request) {
                    if (!$request->message) {
                        continue;
                    }

                    $insert[] = [
                        'customer_help_request_id' => $request->id,
                        'user_id' => $request->user_id,
                        'sender_type' => 'user',
                        'message' => $request->message,
                        'created_at' => $request->created_at,
                        'updated_at' => $request->updated_at,
                    ];
                }

                if (!empty($insert)) {
                    DB::table('customer_help_messages')->insert($insert);
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_help_messages');
    }
};
