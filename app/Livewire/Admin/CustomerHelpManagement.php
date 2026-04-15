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
    public ?int $activeRequestId = null;
    public string $adminReply = '';

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

    public function selectRequest(int $id): void
    {
        $this->activeRequestId = $id;
    }

    public function sendAdminReply(): void
    {
        $this->validate([
            'adminReply' => 'required|string|max:2000',
        ]);

        if (!$this->activeRequestId) {
            return;
        }

        $request = CustomerHelpRequest::findOrFail($this->activeRequestId);
        $request->messages()->create([
            'user_id' => Auth::id(),
            'sender_type' => 'admin',
            'message' => $this->adminReply,
        ]);
        $request->update(['status' => CustomerHelpRequest::STATUS_ATTENDED]);

        $this->adminReply = '';
        session()->flash('success', 'Reply sent successfully.');
    }

    public function render()
    {
        return view('livewire.admin.customer-help-management', [
            'requests' => CustomerHelpRequest::with('messages')->latestFirst()->paginate(15),
            'activeRequest' => $this->activeRequestId
                ? CustomerHelpRequest::with(['messages.user'])->find($this->activeRequestId)
                : null,
        ]);
    }
}
