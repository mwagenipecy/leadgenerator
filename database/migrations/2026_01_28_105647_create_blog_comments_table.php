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
        Schema::create('blog_comments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('blog_post_id'); // UUID foreign key to blog_posts
            $table->uuid('user_id'); // UUID foreign key to users
            $table->uuid('parent_id')->nullable(); // For nested comments/replies
            $table->text('content');
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('blog_post_id')->references('id')->on('blog_posts')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('blog_post_id');
            $table->index('user_id');
            $table->index('parent_id');
        });

        // Add self-referencing foreign key after table creation
        Schema::table('blog_comments', function (Blueprint $table) {
            $table->foreign('parent_id')->references('id')->on('blog_comments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_comments');
    }
};
