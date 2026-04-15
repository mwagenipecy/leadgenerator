<?php

namespace App\Livewire\Admin;

use App\Models\CustomerHelpRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerHelpManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        if (!Auth::check() || Auth::user()->role !== 'super_admin') {
            abort(403, 'Access denied. Admin access required.');
        }
    }

    public function markAsAttended(int $id): void
    {
        $request = CustomerHelpRequest::findOrFail($id);
        $request->update(['status' => CustomerHelpRequest::STATUS_ATTENDED]);
        session()->flash('success', 'Request marked as attended.');
    }

    public function markAsNew(int $id): void
    {
        $request = CustomerHelpRequest::findOrFail($id);
        $request->update(['status' => CustomerHelpRequest::STATUS_NEW]);
        session()->flash('success', 'Request marked as new.');
    }

    public function render()
    {
        return view('livewire.admin.customer-help-management', [
            'requests' => CustomerHelpRequest::latestFirst()->paginate(15),
        ]);
    }
}
