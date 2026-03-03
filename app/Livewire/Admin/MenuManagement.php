<?php

namespace App\Livewire\Admin;

use App\Models\SidebarMenuItem;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class MenuManagement extends Component
{
    public function mount(): void
    {
        if (! Auth::check() || Auth::user()->role !== 'super_admin') {
            abort(403);
        }
    }

    public function toggleVisible(int $id): void
    {
        $item = SidebarMenuItem::findOrFail($id);
        $item->update(['is_visible' => ! $item->is_visible]);
        $this->dispatch('menu-updated');
    }

    public function toggleEnabled(int $id): void
    {
        $item = SidebarMenuItem::findOrFail($id);
        $item->update(['is_enabled' => ! $item->is_enabled]);
        $this->dispatch('menu-updated');
    }

    public function render()
    {
        $items = SidebarMenuItem::with('children')
            ->roots()
            ->orderBy('sort_order')
            ->get();

        return view('livewire.admin.menu-management', [
            'menuItems' => $items,
        ]);
    }
}
