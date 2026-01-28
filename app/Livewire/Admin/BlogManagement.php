<?php

namespace App\Livewire\Admin;

use App\Models\BlogPost;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BlogManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all';
    public $showDeleteModal = false;
    public $postToDelete = null;

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        // Check if user is authenticated and admin
        if (!Auth::check() || Auth::user()->role !== 'super_admin') {
            abort(403, 'Access denied. Admin access required.');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function openDeleteModal($postId)
    {
        $this->postToDelete = BlogPost::findOrFail($postId);
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->postToDelete = null;
    }

    public function delete()
    {
        if ($this->postToDelete) {
            // Delete featured image if exists
            if ($this->postToDelete->featured_image) {
                Storage::disk('public')->delete($this->postToDelete->featured_image);
            }
            $this->postToDelete->delete();
            session()->flash('success', 'Blog post deleted successfully!');
        }
        $this->closeDeleteModal();
    }

    public function render()
    {
        $query = BlogPost::with('author')
            ->orderBy('created_at', 'desc');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $this->search . '%')
                  ->orWhere('content', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $posts = $query->paginate(15);

        return view('livewire.admin.blog-management', [
            'posts' => $posts,
        ]);
    }
}
