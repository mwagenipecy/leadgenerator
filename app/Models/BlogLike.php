<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogLike extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'blog_post_id',
        'user_id',
    ];

    /**
     * Get the blog post that was liked.
     */
    public function blogPost()
    {
        return $this->belongsTo(BlogPost::class, 'blog_post_id');
    }

    /**
     * Get the user who liked the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
