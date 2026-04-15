<?php

namespace App\Livewire\Admin;

use App\Models\HeroSlider;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
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
    public $imageEnglish = null;
    public $imageSwahili = null;
    public $title = '';
    public $description = '';
    public $order = 0;
    public $is_active = true;
    public $imagePreviewEnglish = null;
    public $imagePreviewSwahili = null;

    protected $paginationTheme = 'tailwind';

    protected function imageRules(bool $required = false): array
    {
        return [
            'image',
            'mimes:jpg,jpeg,png,webp',
            'max:5120',
            'dimensions:max_width=6000,max_height=6000',
            $required ? 'required' : 'nullable',
        ];
    }

    protected function rules(): array
    {
        return [
            'imageEnglish' => $this->imageRules(),
            'imageSwahili' => $this->imageRules(),
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
        $this->imagePreviewEnglish = $this->selectedSlider->image_path_en ?? $this->selectedSlider->image_path;
        $this->imagePreviewSwahili = $this->selectedSlider->image_path_sw;
        $this->imageEnglish = null;
        $this->imageSwahili = null;
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
        $this->imageEnglish = null;
        $this->imageSwahili = null;
        $this->title = '';
        $this->description = '';
        $this->order = HeroSlider::max('order') + 1 ?? 0;
        $this->is_active = true;
        $this->imagePreviewEnglish = null;
        $this->imagePreviewSwahili = null;
    }

    public function store()
    {
        $this->validate([
            'imageEnglish' => $this->imageRules(true),
            'imageSwahili' => $this->imageRules(true),
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);
        $this->assertUploadedImageIsSafe($this->imageEnglish, 'imageEnglish');
        $this->assertUploadedImageIsSafe($this->imageSwahili, 'imageSwahili');

        $imagePathEn = $this->storeOptimizedImage($this->imageEnglish);
        $imagePathSw = $this->storeOptimizedImage($this->imageSwahili);

        HeroSlider::create([
            'image_path' => $imagePathEn,
            'image_path_en' => $imagePathEn,
            'image_path_sw' => $imagePathSw,
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
            'imageEnglish' => $this->imageRules(),
            'imageSwahili' => $this->imageRules(),
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);
        $this->assertUploadedImageIsSafe($this->imageEnglish, 'imageEnglish');
        $this->assertUploadedImageIsSafe($this->imageSwahili, 'imageSwahili');

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'order' => $this->order,
            'is_active' => $this->is_active,
        ];

        if ($this->imageEnglish) {
            if ($this->selectedSlider->image_path_en) {
                Storage::disk('public')->delete($this->selectedSlider->image_path_en);
            } elseif ($this->selectedSlider->image_path) {
                Storage::disk('public')->delete($this->selectedSlider->image_path);
            }

            $data['image_path_en'] = $this->storeOptimizedImage($this->imageEnglish);
            $data['image_path'] = $data['image_path_en'];
        }

        if ($this->imageSwahili) {
            if ($this->selectedSlider->image_path_sw) {
                Storage::disk('public')->delete($this->selectedSlider->image_path_sw);
            }
            $data['image_path_sw'] = $this->storeOptimizedImage($this->imageSwahili);
        }

        $this->selectedSlider->update($data);

        session()->flash('success', 'Hero slider updated successfully!');
        $this->closeEditModal();
    }

    public function delete()
    {
        if ($this->selectedSlider) {
            // Delete image file
            if ($this->selectedSlider->image_path_en) {
                Storage::disk('public')->delete($this->selectedSlider->image_path_en);
            } elseif ($this->selectedSlider->image_path) {
                Storage::disk('public')->delete($this->selectedSlider->image_path);
            }

            if ($this->selectedSlider->image_path_sw) {
                Storage::disk('public')->delete($this->selectedSlider->image_path_sw);
            }
            $this->selectedSlider->delete();
            session()->flash('success', 'Hero slider deleted successfully!');
        }
        $this->closeDeleteModal();
    }

    public function updatedImageEnglish()
    {
        $this->validateOnly('imageEnglish');
        if ($this->imageEnglish) {
            $this->assertUploadedImageIsSafe($this->imageEnglish, 'imageEnglish');
            $this->imagePreviewEnglish = $this->imageEnglish->temporaryUrl();
        }
    }

    public function updatedImageSwahili()
    {
        $this->validateOnly('imageSwahili');
        if ($this->imageSwahili) {
            $this->assertUploadedImageIsSafe($this->imageSwahili, 'imageSwahili');
            $this->imagePreviewSwahili = $this->imageSwahili->temporaryUrl();
        }
    }

    protected function assertUploadedImageIsSafe($image, string $field): void
    {
        if (!$image) {
            return;
        }

        $path = $image->getRealPath();
        if (!$path || @getimagesize($path) === false) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                $field => 'Invalid image file.',
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
