<?php

namespace App\Livewire\Layout;

use Livewire\Component;

class SideBar extends Component
{
    public $isCollapsed = false;

    protected $listeners = ['toggle-sidebar' => 'toggle'];

    public function toggle()
    {
        $this->isCollapsed = !$this->isCollapsed;
        // Store state in session for persistence
        session(['sidebar_collapsed' => $this->isCollapsed]);
    }

    public function mount()
    {
        // Restore collapsed state from session
        $this->isCollapsed = session('sidebar_collapsed', false);
    }

    public function render()
    {
        return view('livewire.layout.side-bar');
    }
}
