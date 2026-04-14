<?php

namespace App\Livewire\Admin;

use App\Models\Partner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class PartnerManagement extends Component
{
    use WithFileUploads, WithPagination;

    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $selectedPartner = null;

    public $name = '';
    public $website_url = '';
    public $logo = null;
    public $logoPreview = null;
    public $order = 0;
    public $is_active = true;

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        if (!Auth::check() || Auth::user()->role !== 'super_admin') {
            abort(403, 'Access denied. Admin access required.');
        }
    }

    protected function logoRules(bool $required = false): array
    {
        return [
            'file',
            'mimes:jpg,jpeg,png,webp,svg',
            'max:2048',
            $required ? 'required' : 'nullable',
        ];
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

    public function openEditModal($partnerId)
    {
        $this->selectedPartner = Partner::findOrFail($partnerId);
        $this->name = $this->selectedPartner->name;
        $this->website_url = $this->selectedPartner->website_url;
        $this->order = $this->selectedPartner->order;
        $this->is_active = $this->selectedPartner->is_active;
        $this->logoPreview = $this->selectedPartner->logo_path;
        $this->logo = null;
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->selectedPartner = null;
        $this->resetForm();
    }

    public function openDeleteModal($partnerId)
    {
        $this->selectedPartner = Partner::findOrFail($partnerId);
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->selectedPartner = null;
    }

    public function resetForm()
    {
        $this->name = '';
        $this->website_url = '';
        $this->logo = null;
        $this->logoPreview = null;
        $this->order = (Partner::max('order') ?? -1) + 1;
        $this->is_active = true;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'website_url' => 'required|url|max:255',
            'logo' => $this->logoRules(true),
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        Partner::create([
            'name' => $this->name,
            'website_url' => $this->website_url,
            'logo_path' => $this->storeLogo($this->logo),
            'order' => $this->order,
            'is_active' => $this->is_active,
        ]);

        session()->flash('success', 'Partner added successfully.');
        $this->closeCreateModal();
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'website_url' => 'required|url|max:255',
            'logo' => $this->logoRules(),
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data = [
            'name' => $this->name,
            'website_url' => $this->website_url,
            'order' => $this->order,
            'is_active' => $this->is_active,
        ];

        if ($this->logo) {
            if ($this->selectedPartner->logo_path) {
                Storage::disk('public')->delete($this->selectedPartner->logo_path);
            }
            $data['logo_path'] = $this->storeLogo($this->logo);
        }

        $this->selectedPartner->update($data);

        session()->flash('success', 'Partner updated successfully.');
        $this->closeEditModal();
    }

    public function delete()
    {
        if ($this->selectedPartner) {
            if ($this->selectedPartner->logo_path) {
                Storage::disk('public')->delete($this->selectedPartner->logo_path);
            }
            $this->selectedPartner->delete();
            session()->flash('success', 'Partner deleted successfully.');
        }

        $this->closeDeleteModal();
    }

    protected function storeLogo($uploadedFile): string
    {
        $extension = strtolower($uploadedFile->getClientOriginalExtension() ?: 'png');
        $filename = Str::uuid() . '.' . $extension;
        return $uploadedFile->storeAs('partners', $filename, 'public');
    }

    public function render()
    {
        return view('livewire.admin.partner-management', [
            'partners' => Partner::ordered()->paginate(10),
        ]);
    }
}
