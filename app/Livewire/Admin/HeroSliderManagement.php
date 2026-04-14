<?php

namespace App\Livewire\Admin;

use App\Models\HeroSlider;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    protected function imageRules(bool $required = false): array
    {
        return [
            'image',
            'max:5120',
            Rule::mimes(['jpg', 'jpeg', 'png', 'webp']),
            Rule::dimensions()->maxWidth(6000)->maxHeight(6000),
            $required ? 'required' : 'nullable',
        ];
    }

    protected function rules(): array
    {
        return [
            'image' => $this->imageRules(),
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ];
    }

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
            'image' => $this->imageRules(true),
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);
        $this->assertUploadedImageIsSafe();

        $imagePath = $this->storeOptimizedImage($this->image);

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
            'image' => $this->imageRules(),
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);
        $this->assertUploadedImageIsSafe();

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
            $data['image_path'] = $this->storeOptimizedImage($this->image);
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
            $this->assertUploadedImageIsSafe();
            $this->imagePreview = $this->image->temporaryUrl();
        }
    }

    protected function assertUploadedImageIsSafe(): void
    {
        if (!$this->image) {
            return;
        }

        $path = $this->image->getRealPath();
        if (!$path || @getimagesize($path) === false) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'image' => 'Invalid image file.',
            ]);
        }
    }

    protected function storeOptimizedImage($uploadedFile): string
    {
        $sourcePath = $uploadedFile->getRealPath();
        $imageInfo = @getimagesize($sourcePath);

        if (!$sourcePath || $imageInfo === false) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'image' => 'Unable to process image file.',
            ]);
        }

        $sourceData = @file_get_contents($sourcePath);
        $sourceImage = $sourceData ? @imagecreatefromstring($sourceData) : false;
        if ($sourceImage === false) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'image' => 'Unsupported image format.',
            ]);
        }

        $originalWidth = (int) $imageInfo[0];
        $originalHeight = (int) $imageInfo[1];
        $maxWidth = 1920;

        $targetWidth = $originalWidth > $maxWidth ? $maxWidth : $originalWidth;
        $targetHeight = (int) round(($targetWidth / max($originalWidth, 1)) * $originalHeight);

        $targetImage = imagecreatetruecolor($targetWidth, $targetHeight);
        $white = imagecolorallocate($targetImage, 255, 255, 255);
        imagefill($targetImage, 0, 0, $white);
        imagecopyresampled(
            $targetImage,
            $sourceImage,
            0,
            0,
            0,
            0,
            $targetWidth,
            $targetHeight,
            $originalWidth,
            $originalHeight
        );

        ob_start();
        $storedExtension = 'webp';
        if (function_exists('imagewebp')) {
            imagewebp($targetImage, null, 80);
        } else {
            $storedExtension = 'jpg';
            imagejpeg($targetImage, null, 82);
        }
        $optimizedBinary = ob_get_clean();

        imagedestroy($sourceImage);
        imagedestroy($targetImage);

        if (!$optimizedBinary) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'image' => 'Unable to optimize image file.',
            ]);
        }

        $storagePath = 'hero-slider/' . Str::uuid() . '.' . $storedExtension;
        Storage::disk('public')->put($storagePath, $optimizedBinary);

        return $storagePath;
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
