<?php

namespace App\Livewire\Layout;

use App\Models\SidebarMenuItem;
use Livewire\Component;

class SideBar extends Component
{
    public $isCollapsed = false;

    protected $listeners = ['toggle-sidebar' => 'toggle', 'menu-updated' => '$refresh'];

    public function toggle()
    {
        $this->isCollapsed = !$this->isCollapsed;
        session(['sidebar_collapsed' => $this->isCollapsed]);
    }

    public function mount()
    {
        $this->isCollapsed = session('sidebar_collapsed', false);
    }

    public function render()
    {
        $role = auth()->check() ? auth()->user()->role : null;
        $menuTree = auth()->check()
            ? SidebarMenuItem::getTreeForUser($role)
            : collect();

        return view('livewire.layout.side-bar', [
            'menuTree' => $menuTree,
        ]);
    }
}
