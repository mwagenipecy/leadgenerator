<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'meta_title',
        'meta_description',
        'status',
        'is_featured',
        'views_count',
        'likes_count',
        'comments_count',
        'published_at',
        'author_id',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'views_count' => 'integer',
        'likes_count' => 'integer',
        'comments_count' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });

        static::updating(function ($post) {
            if ($post->isDirty('title') && empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });
    }

    /**
     * Get the author of the blog post.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get all comments for the blog post.
     */
    public function comments()
    {
        return $this->hasMany(BlogComment::class, 'blog_post_id')->whereNull('parent_id')->orderBy('created_at', 'desc');
    }

    /**
     * Get all comments including nested ones.
     */
    public function allComments()
    {
        return $this->hasMany(BlogComment::class, 'blog_post_id')->orderBy('created_at', 'desc');
    }

    /**
     * Get all likes for the blog post.
     */
    public function likes()
    {
        return $this->hasMany(BlogLike::class, 'blog_post_id');
    }

    /**
     * Check if a user has liked this post.
     */
    public function isLikedBy($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    /**
     * Scope for published posts.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now());
    }

    /**
     * Scope for featured posts.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Increment views count.
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }
}
