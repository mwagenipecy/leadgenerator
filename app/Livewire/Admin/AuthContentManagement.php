<?php

namespace App\Livewire\Admin;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class AuthContentManagement extends Component
{
    use WithFileUploads;

    public $imageEnglish;
    public $imageSwahili;
    public ?string $selectedPage = null;
    public bool $showCreateModal = false;
    public bool $showEditModal = false;
    public array $pages = [];

    public array $currentImages = [];

    protected array $rules = [
        'selectedPage' => 'required|in:login,register,forgot_password,otp',
        'imageEnglish' => 'nullable|image|max:4096',
        'imageSwahili' => 'nullable|image|max:4096',
    ];

    public function mount(): void
    {
        $this->pages = [
            'login' => __('admin.login_page_images'),
            'register' => __('admin.register_page_images'),
            'forgot_password' => __('admin.forgot_password_page_images'),
            'otp' => __('admin.otp_page_images'),
        ];
        $this->refreshCurrentImages();
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function openEditModal(string $page): void
    {
        if (!array_key_exists($page, $this->pages)) {
            return;
        }

        $this->resetForm();
        $this->selectedPage = $page;
        $this->showEditModal = true;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->resetForm();
    }

    public function store(): void
    {
        $this->validate();
        $this->persistSelectedPageImages();
        $this->refreshCurrentImages();
        $this->closeCreateModal();
        session()->flash('success', 'Auth page images created successfully.');
    }

    public function update(): void
    {
        $this->validate();
        $this->persistSelectedPageImages();
        $this->refreshCurrentImages();
        $this->closeEditModal();
        session()->flash('success', 'Auth page images updated successfully.');
    }

    public function resetToDefault(string $settingKey): void
    {
        $setting = SystemSetting::where('key', $settingKey)->first();

        if ($setting && $setting->value) {
            $this->deleteStoredFileFromUrl($setting->value);
            $setting->delete();
        }

        $this->refreshCurrentImages();
        session()->flash('success', 'Selected language image reset to default.');
    }

    public function hasUploadedImageForPage(string $page): bool
    {
        $keys = $this->settingKeysForPage($page);
        return !empty($this->currentImages[$keys['en']]) || !empty($this->currentImages[$keys['sw']]);
    }

    public function getDisplayImage(string $page, string $locale): string
    {
        $keys = $this->settingKeysForPage($page);
        $specificKey = $locale === 'sw' ? $keys['sw'] : $keys['en'];
        $fallbackSpecificEn = $keys['en'];
        $genericLocaleKey = "auth_side_image_{$locale}";
        $genericEnKey = 'auth_side_image_en';

        $storedValue = $this->currentImages[$specificKey]
            ?? $this->currentImages[$fallbackSpecificEn]
            ?? $this->currentImages[$genericLocaleKey]
            ?? $this->currentImages[$genericEnKey]
            ?? null;

        return $this->resolveImageUrl($storedValue);
    }

    private function replaceImage(string $key, $image, string $description): void
    {
        $existingUrl = SystemSetting::getValue($key);
        if ($existingUrl) {
            $this->deleteStoredFileFromUrl($existingUrl);
        }

        $path = $image->store('auth-content', 'public');
        // Persist relative path to avoid environment-specific URL issues.
        $url = $path;

        SystemSetting::updateOrCreate(
            ['key' => $key],
            [
                'value' => $url,
                'description' => $description,
                'type' => 'image',
                'updated_by' => Auth::id(),
            ]
        );
    }

    private function deleteStoredFileFromUrl(string $url): void
    {
        $storagePath = $this->extractStoragePathFromValue($url);
        if ($storagePath !== '' && Storage::disk('public')->exists($storagePath)) {
            Storage::disk('public')->delete($storagePath);
        }
    }

    private function refreshCurrentImages(): void
    {
        $this->currentImages = [];

        $settingKeys = [
            'auth_side_image_en',
            'auth_side_image_sw',
            'auth_side_image_login_en',
            'auth_side_image_login_sw',
            'auth_side_image_register_en',
            'auth_side_image_register_sw',
            'auth_side_image_forgot_password_en',
            'auth_side_image_forgot_password_sw',
            'auth_side_image_otp_en',
            'auth_side_image_otp_sw',
        ];

        foreach ($settingKeys as $settingKey) {
            $this->currentImages[$settingKey] = SystemSetting::getValue($settingKey, '');
        }
    }

    private function persistSelectedPageImages(): void
    {
        $keys = $this->settingKeysForPage($this->selectedPage ?? '');

        if ($this->imageEnglish) {
            $this->replaceImage($keys['en'], $this->imageEnglish, "Auth side image: {$keys['en']}");
            $this->imageEnglish = null;
        }

        if ($this->imageSwahili) {
            $this->replaceImage($keys['sw'], $this->imageSwahili, "Auth side image: {$keys['sw']}");
            $this->imageSwahili = null;
        }
    }

    private function settingKeysForPage(string $page): array
    {
        return [
            'en' => "auth_side_image_{$page}_en",
            'sw' => "auth_side_image_{$page}_sw",
        ];
    }

    private function resetForm(): void
    {
        $this->selectedPage = null;
        $this->imageEnglish = null;
        $this->imageSwahili = null;
        $this->resetValidation();
    }

    private function resolveImageUrl(?string $value): string
    {
        if (empty($value)) {
            return asset('landing/register-login.jpg');
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        if (str_starts_with($value, '/storage/')) {
            return asset(ltrim($value, '/'));
        }

        if (str_starts_with($value, 'storage/')) {
            return asset($value);
        }

        return asset('storage/' . ltrim($value, '/'));
    }

    private function extractStoragePathFromValue(string $value): string
    {
        $path = parse_url($value, PHP_URL_PATH) ?: $value;
        $path = ltrim($path, '/');

        if (str_starts_with($path, 'storage/')) {
            return ltrim(substr($path, strlen('storage/')), '/');
        }

        if (str_starts_with($path, 'auth-content/')) {
            return $path;
        }

        return '';
    }

    public function render()
    {
        return view('livewire.admin.auth-content-management');
    }
}
