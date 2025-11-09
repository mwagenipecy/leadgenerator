<?php

namespace App\Livewire\Admin;

use App\Models\SystemLog;
use Livewire\Component;
use Livewire\WithPagination;

class SystemLogs extends Component
{
    use WithPagination;

    public $search = '';
    public $severityFilter = '';
    public $actionFilter = '';
    public $userFilter = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $selectedLog = null;
    public $showLogDetailModal = false;

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSeverityFilter()
    {
        $this->resetPage();
    }

    public function updatingActionFilter()
    {
        $this->resetPage();
    }

    public function updatingUserFilter()
    {
        $this->resetPage();
    }

    public function viewLogDetail($logId)
    {
        $this->selectedLog = SystemLog::with('user')->find($logId);
        $this->showLogDetailModal = true;
    }

    public function closeLogDetailModal()
    {
        $this->showLogDetailModal = false;
        $this->selectedLog = null;
    }

    public function render()
    {
        $query = SystemLog::with('user');

        // Apply search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('action', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('ip_address', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function ($userQuery) {
                      $userQuery->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('email', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Apply severity filter
        if ($this->severityFilter) {
            $query->where('severity', $this->severityFilter);
        }

        // Apply action filter
        if ($this->actionFilter) {
            $query->where('action', $this->actionFilter);
        }

        // Apply user filter
        if ($this->userFilter) {
            $query->where('user_id', $this->userFilter);
        }

        // Apply date filters
        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get filter options
        $severities = ['low', 'medium', 'high', 'critical'];
        $actions = SystemLog::distinct()->pluck('action')->sort()->values();
        $users = \App\Models\User::whereIn('id', SystemLog::distinct()->pluck('user_id'))->get();

        // Get stats
        $totalLogs = SystemLog::count();
        $criticalLogs = SystemLog::where('severity', 'critical')->count();
        $todayLogs = SystemLog::whereDate('created_at', today())->count();
        $thisWeekLogs = SystemLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();

        return view('livewire.admin.system-logs', [
            'logs' => $logs,
            'severities' => $severities,
            'actions' => $actions,
            'users' => $users,
            'totalLogs' => $totalLogs,
            'criticalLogs' => $criticalLogs,
            'todayLogs' => $todayLogs,
            'thisWeekLogs' => $thisWeekLogs,
        ]);
    }
}
