<?php

namespace App\Livewire\Blog;

use App\Models\BlogPost;
use App\Models\BlogComment;
use App\Models\BlogLike;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class BlogDetail extends Component
{
    public $post;
    public $slug;
    public $commentContent = '';
    public $parentCommentId = null;
    public $replyContent = '';

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->loadPost();
    }

    public function loadPost()
    {
        $this->post = BlogPost::with(['author', 'comments.user', 'comments.replies.user'])
            ->where('slug', $this->slug)
            ->published()
            ->firstOrFail();

        // Increment views
        $this->post->incrementViews();
    }

    public function toggleLike()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Please login to like posts.');
            return redirect()->route('login');
        }

        $like = BlogLike::where('blog_post_id', $this->post->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($like) {
            $like->delete();
            $this->post->decrement('likes_count');
            session()->flash('success', 'Post unliked.');
        } else {
            BlogLike::create([
                'blog_post_id' => $this->post->id,
                'user_id' => Auth::id(),
            ]);
            $this->post->increment('likes_count');
            session()->flash('success', 'Post liked!');
        }

        $this->loadPost();
    }

    public function addComment()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Please login to comment.');
            return redirect()->route('login');
        }

        $this->validate([
            'commentContent' => 'required|string|min:3|max:1000',
        ]);

        BlogComment::create([
            'blog_post_id' => $this->post->id,
            'user_id' => Auth::id(),
            'content' => $this->commentContent,
            'is_approved' => true,
        ]);

        $this->post->increment('comments_count');
        $this->commentContent = '';
        $this->loadPost();
        session()->flash('success', 'Comment added successfully!');
    }

    public function replyToComment($commentId)
    {
        if (!Auth::check()) {
            session()->flash('error', 'Please login to reply.');
            return redirect()->route('login');
        }

        $this->validate([
            'replyContent' => 'required|string|min:3|max:1000',
        ]);

        BlogComment::create([
            'blog_post_id' => $this->post->id,
            'user_id' => Auth::id(),
            'parent_id' => $commentId,
            'content' => $this->replyContent,
            'is_approved' => true,
        ]);

        $this->post->increment('comments_count');
        $this->replyContent = '';
        $this->parentCommentId = null;
        $this->loadPost();
        session()->flash('success', 'Reply added successfully!');
    }

    public function setReplyMode($commentId)
    {
        $this->parentCommentId = $commentId;
        $this->replyContent = '';
    }

    public function cancelReply()
    {
        $this->parentCommentId = null;
        $this->replyContent = '';
    }

    public function render()
    {
        $isLiked = Auth::check() && $this->post->isLikedBy(Auth::id());

        // Get related posts
        $relatedPosts = BlogPost::with('author')
            ->published()
            ->where('id', '!=', $this->post->id)
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        return view('livewire.blog.blog-detail', [
            'isLiked' => $isLiked,
            'relatedPosts' => $relatedPosts,
        ]);
    }
}
