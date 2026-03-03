<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sidebar_menu_items', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('label_key');
            $table->string('route')->nullable();
            $table->string('icon')->nullable();
            $table->json('roles')->nullable(); // null or [] = all authenticated; ['lender','super_admin'] = only those roles
            $table->foreignId('parent_id')->nullable()->constrained('sidebar_menu_items')->nullOnDelete();
            $table->boolean('is_visible')->default(true);
            $table->boolean('is_enabled')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sidebar_menu_items');
    }
};
