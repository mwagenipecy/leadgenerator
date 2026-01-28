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
        Schema::create('blog_likes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('blog_post_id'); // UUID foreign key to blog_posts
            $table->uuid('user_id'); // UUID foreign key to users
            $table->timestamps();

            $table->foreign('blog_post_id')->references('id')->on('blog_posts')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['blog_post_id', 'user_id']); // Prevent duplicate likes
            $table->index('blog_post_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_likes');
    }
};
