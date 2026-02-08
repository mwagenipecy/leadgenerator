<?php

namespace App\Livewire\Admin;

use App\Models\BlogPost;
use App\Services\LogService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogEdit extends Component
{
    use WithFileUploads;

    public $postId;
    public $post;
    public $title = '';
    public $slug = '';
    public $excerpt = '';
    public $content = '';
    public $featured_image = null;
    public $featured_image_preview = null;
    public $meta_title = '';
    public $meta_description = '';
    public $status = 'draft';
    public $is_featured = false;
    public $published_at = '';

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_posts,slug,' . $this->postId,
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
        ];
    }

    public function mount($id)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'Access denied. Admin access required.');
        }

        $this->postId = $id;
        $this->post = BlogPost::findOrFail($id);
        
        $this->title = $this->post->title;
        $this->slug = $this->post->slug;
        $this->excerpt = $this->post->excerpt;
        $this->content = $this->post->content;
        $this->featured_image_preview = $this->post->featured_image;
        $this->meta_title = $this->post->meta_title;
        $this->meta_description = $this->post->meta_description;
        $this->status = $this->post->status;
        $this->is_featured = $this->post->is_featured;
        $this->published_at = $this->post->published_at ? $this->post->published_at->format('Y-m-d\TH:i') : '';
    }

    public function generateSlug()
    {
        $this->slug = Str::slug($this->title);
    }

    public function update()
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
        ];

        if ($this->published_at) {
            $data['published_at'] = $this->published_at;
        } elseif ($this->status === 'published' && !$this->post->published_at) {
            $data['published_at'] = now();
        }

        if ($this->featured_image) {
            // Delete old image if exists
            if ($this->post->featured_image) {
                Storage::disk('public')->delete($this->post->featured_image);
            }
            $path = $this->featured_image->store('blog/featured-images', 'public');
            $data['featured_image'] = $path;
        }

        $oldValues = $this->post->toArray();
        $this->post->update($data);
        $newValues = $this->post->fresh()->toArray();

        // Log activity
        LogService::logBlogPostUpdated($this->post, $oldValues, $newValues);

        session()->flash('success', 'Blog post updated successfully!');
        return redirect()->route('admin.blog.management');
    }

    public function render()
    {
        return view('livewire.admin.blog-edit');
    }
}
