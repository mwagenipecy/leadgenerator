<?php

namespace App\Livewire\Admin;

use App\Models\HeroSlider;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HeroSliderManagement extends Component
{
    use WithFileUploads, WithPagination;

    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $selectedSlider = null;

    // Create/Edit properties
    public $image = null;
    public $title = '';
    public $description = '';
    public $order = 0;
    public $is_active = true;
    public $imagePreview = null;

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'image' => 'nullable|image|max:5120', // 5MB max
        'title' => 'nullable|string|max:255',
        'description' => 'nullable|string|max:1000',
        'order' => 'required|integer|min:0',
        'is_active' => 'boolean',
    ];

    public function mount()
    {
        // Check if user is authenticated and admin
        if (!Auth::check() || Auth::user()->role !== 'super_admin') {
            abort(403, 'Access denied. Admin access required.');
        }
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function openEditModal($sliderId)
    {
        $this->selectedSlider = HeroSlider::findOrFail($sliderId);
        $this->title = $this->selectedSlider->title;
        $this->description = $this->selectedSlider->description;
        $this->order = $this->selectedSlider->order;
        $this->is_active = $this->selectedSlider->is_active;
        $this->imagePreview = $this->selectedSlider->image_path;
        $this->image = null;
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->selectedSlider = null;
        $this->resetForm();
    }

    public function openDeleteModal($sliderId)
    {
        $this->selectedSlider = HeroSlider::findOrFail($sliderId);
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->selectedSlider = null;
    }

    public function resetForm()
    {
        $this->image = null;
        $this->title = '';
        $this->description = '';
        $this->order = HeroSlider::max('order') + 1 ?? 0;
        $this->is_active = true;
        $this->imagePreview = null;
    }

    public function store()
    {
        $this->validate([
            'image' => 'required|image|max:5120',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $imagePath = $this->image->store('hero-slider', 'public');

        HeroSlider::create([
            'image_path' => $imagePath,
            'title' => $this->title,
            'description' => $this->description,
            'order' => $this->order,
            'is_active' => $this->is_active,
        ]);

        session()->flash('success', 'Hero slider image added successfully!');
        $this->closeCreateModal();
    }

    public function update()
    {
        $this->validate([
            'image' => 'nullable|image|max:5120',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'order' => $this->order,
            'is_active' => $this->is_active,
        ];

        if ($this->image) {
            // Delete old image
            if ($this->selectedSlider->image_path) {
                Storage::disk('public')->delete($this->selectedSlider->image_path);
            }
            $data['image_path'] = $this->image->store('hero-slider', 'public');
        }

        $this->selectedSlider->update($data);

        session()->flash('success', 'Hero slider updated successfully!');
        $this->closeEditModal();
    }

    public function delete()
    {
        if ($this->selectedSlider) {
            // Delete image file
            if ($this->selectedSlider->image_path) {
                Storage::disk('public')->delete($this->selectedSlider->image_path);
            }
            $this->selectedSlider->delete();
            session()->flash('success', 'Hero slider deleted successfully!');
        }
        $this->closeDeleteModal();
    }

    public function updatedImage()
    {
        $this->validateOnly('image');
        if ($this->image) {
            $this->imagePreview = $this->image->temporaryUrl();
        }
    }

    public function render()
    {
        $sliders = HeroSlider::orderBy('order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.hero-slider-management', [
            'sliders' => $sliders,
        ]);
    }
}
