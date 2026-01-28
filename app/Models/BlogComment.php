<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogComment extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'blog_post_id',
        'user_id',
        'parent_id',
        'content',
        'is_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    /**
     * Get the blog post that owns the comment.
     */
    public function blogPost()
    {
        return $this->belongsTo(BlogPost::class, 'blog_post_id');
    }

    /**
     * Get the user who made the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the parent comment (for nested comments).
     */
    public function parent()
    {
        return $this->belongsTo(BlogComment::class, 'parent_id');
    }

    /**
     * Get all replies to this comment.
     */
    public function replies()
    {
        return $this->hasMany(BlogComment::class, 'parent_id')->orderBy('created_at', 'asc');
    }

    /**
     * Scope for approved comments.
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }
}
