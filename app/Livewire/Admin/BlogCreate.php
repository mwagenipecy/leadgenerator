<?php

namespace App\Livewire\Admin;

use App\Models\BlogPost;
use App\Services\LogService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogCreate extends Component
{
    use WithFileUploads;

    public $title = '';
    public $slug = '';
    public $excerpt = '';
    public $content = '';
    public $featured_image = null;
    public $meta_title = '';
    public $meta_description = '';
    public $status = 'draft';
    public $is_featured = false;
    public $published_at = '';

    protected $rules = [
        'title' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:blog_posts,slug',
        'excerpt' => 'nullable|string|max:500',
        'content' => 'required|string',
        'featured_image' => 'nullable|image|max:2048',
        'meta_title' => 'nullable|string|max:255',
        'meta_description' => 'nullable|string|max:500',
        'status' => 'required|in:draft,published,archived',
        'is_featured' => 'boolean',
        'published_at' => 'nullable|date',
    ];

    public function mount()
    {
        // Check if user is admin
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'Access denied. Admin access required.');
        }
    }

    public function generateSlug()
    {
        $this->slug = Str::slug($this->title);
    }

    public function store()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'status' => $this->status,
            'is_featured' => $this->is_featured,
            'author_id' => Auth::id(),
        ];

        if ($this->published_at) {
            $data['published_at'] = $this->published_at;
        } elseif ($this->status === 'published') {
            $data['published_at'] = now();
        }

        if ($this->featured_image) {
            $path = $this->featured_image->store('blog/featured-images', 'public');
            $data['featured_image'] = $path;
        }

        $post = BlogPost::create($data);

        // Log activity
        LogService::logBlogPostCreated($post);

        session()->flash('success', 'Blog post created successfully!');
        return redirect()->route('admin.blog.management');
    }

    public function render()
    {
        return view('livewire.admin.blog-create');
    }
}
