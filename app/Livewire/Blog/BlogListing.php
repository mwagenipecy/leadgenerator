<?php

namespace App\Livewire\Blog;

use App\Models\BlogPost;
use Livewire\Component;
use Livewire\WithPagination;

class BlogListing extends Component
{
    use WithPagination;

    public $search = '';
    public $featuredOnly = false;

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFeaturedOnly()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = BlogPost::with(['author'])
            ->published()
            ->orderBy('published_at', 'desc');

        if ($this->featuredOnly) {
            $query->featured();
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $this->search . '%')
                  ->orWhere('content', 'like', '%' . $this->search . '%');
            });
        }

        $posts = $query->paginate(12);

        // Get featured posts for sidebar
        $featuredPosts = BlogPost::with('author')
            ->published()
            ->featured()
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        return view('livewire.blog.blog-listing', [
            'posts' => $posts,
            'featuredPosts' => $featuredPosts,
        ]);
    }
}
