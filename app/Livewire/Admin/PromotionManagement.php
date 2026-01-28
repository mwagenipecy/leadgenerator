<?php

namespace App\Livewire\Admin;

use App\Models\Promotion;
use App\Models\User;
use App\Models\Application;
use App\Notifications\PromotionNotification;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PromotionManagement extends Component
{
    use WithPagination;

    public $showCreateModal = false;
    public $showPreviewModal = false;
    public $showSendModal = false;
    public $selectedPromotion = null;
    public $creatingSample = false;

    // Form properties
    public $title = '';
    public $message = '';
    public $send_email = true;
    public $send_notification = true;
    public $scheduled_at = '';
    
    // Target audience filters
    public $target_roles = [];
    public $target_new_customers = false;
    public $target_no_loans = false;
    public $target_incomplete_registration = false;
    public $target_credit_score_min = '';
    public $target_credit_score_max = '';
    public $target_registration_days = '';
    public $target_city = '';
    public $target_region = '';

    // Preview stats
    public $previewRecipientsCount = 0;

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'title' => 'required|string|max:255',
        'message' => 'required|string|max:5000',
        'send_email' => 'boolean',
        'send_notification' => 'boolean',
        'scheduled_at' => 'nullable|date|after_or_equal:now',
    ];

    public function mount()
    {
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

    public function openPreviewModal()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $this->previewRecipientsCount = $this->getTargetRecipients()->count();
        $this->showPreviewModal = true;
    }

    public function closePreviewModal()
    {
        $this->showPreviewModal = false;
    }

    public function openSendModal()
    {
        $this->validate();
        $this->showSendModal = true;
    }

    public function closeSendModal()
    {
        $this->showSendModal = false;
    }

    public function resetForm()
    {
        $this->title = '';
        $this->message = '';
        $this->send_email = true;
        $this->send_notification = true;
        $this->scheduled_at = '';
        $this->target_roles = [];
        $this->target_new_customers = false;
        $this->target_no_loans = false;
        $this->target_incomplete_registration = false;
        $this->target_credit_score_min = '';
        $this->target_credit_score_max = '';
        $this->target_registration_days = '';
        $this->target_city = '';
        $this->target_region = '';
        $this->previewRecipientsCount = 0;
        $this->resetValidation();
    }

    public function getTargetRecipients()
    {
        $query = User::query();

        // Filter by roles
        if (!empty($this->target_roles)) {
            $query->whereIn('role', $this->target_roles);
        }

        // New customers (registered within specified days)
        if ($this->target_new_customers && $this->target_registration_days) {
            $daysAgo = Carbon::now()->subDays($this->target_registration_days);
            $query->where('created_at', '>=', $daysAgo);
        }

        // Customers with no loans
        if ($this->target_no_loans) {
            $query->whereDoesntHave('applications');
        }

        // Incomplete registration (no NIDA verification)
        if ($this->target_incomplete_registration) {
            $query->whereNull('nida_verified_at');
        }

        // Credit score range
        if ($this->target_credit_score_min !== '') {
            $query->where('credit_score', '>=', $this->target_credit_score_min);
        }
        if ($this->target_credit_score_max !== '') {
            $query->where('credit_score', '<=', $this->target_credit_score_max);
        }

        // City filter
        if ($this->target_city) {
            $query->where(function($q) {
                $q->whereHas('applications', function($subQ) {
                    $subQ->where('current_city', $this->target_city);
                })->orWhereHas('profile', function($subQ) {
                    $subQ->where('city', $this->target_city);
                });
            });
        }

        // Region filter
        if ($this->target_region) {
            $query->where(function($q) {
                $q->whereHas('applications', function($subQ) {
                    $subQ->where('current_region', $this->target_region);
                })->orWhereHas('profile', function($subQ) {
                    $subQ->where('region', $this->target_region);
                });
            });
        }

        // Only active users
        $query->where('is_active', true);

        return $query->get();
    }

    public function buildTargetAudience()
    {
        return [
            'roles' => $this->target_roles,
            'new_customers' => $this->target_new_customers,
            'registration_days' => $this->target_registration_days,
            'no_loans' => $this->target_no_loans,
            'incomplete_registration' => $this->target_incomplete_registration,
            'credit_score_min' => $this->target_credit_score_min,
            'credit_score_max' => $this->target_credit_score_max,
            'city' => $this->target_city,
            'region' => $this->target_region,
        ];
    }

    public function savePromotion()
    {
        $this->validate();

        try {
            $targetAudience = $this->buildTargetAudience();
            $recipients = $this->getTargetRecipients();
            $totalRecipients = $recipients->count();

            if ($totalRecipients === 0) {
                session()->flash('error', 'No recipients match the selected criteria. Please adjust your filters.');
                return;
            }

            $status = $this->scheduled_at ? 'scheduled' : 'draft';

            $promotion = Promotion::create([
                'title' => $this->title,
                'message' => $this->message,
                'target_audience' => $targetAudience,
                'send_email' => $this->send_email,
                'send_notification' => $this->send_notification,
                'status' => $status,
                'scheduled_at' => $this->scheduled_at ? Carbon::parse($this->scheduled_at) : null,
                'total_recipients' => $totalRecipients,
                'created_by' => Auth::id(),
            ]);

            // If not scheduled, send immediately
            if (!$this->scheduled_at) {
                $this->closePreviewModal();
                $this->closeCreateModal();
                // Send promotion (this will update status)
                $this->sendPromotion($promotion->id);
            } else {
                session()->flash('success', 'Promotion scheduled successfully!');
                $this->closePreviewModal();
                $this->closeCreateModal();
            }
            
            $this->resetPage();

        } catch (\Exception $e) {
            Log::error('Failed to create promotion', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            session()->flash('error', 'Failed to create promotion. Please try again.');
        }
    }

    public function sendPromotion($promotionId)
    {
        try {
            $promotion = Promotion::findOrFail($promotionId);
            
            if ($promotion->status === 'sent') {
                if (request()->wantsJson()) {
                    return response()->json(['error' => 'This promotion has already been sent.'], 400);
                }
                session()->flash('error', 'This promotion has already been sent.');
                return;
            }

            $promotion->update(['status' => 'sending']);

            $recipients = $this->getTargetRecipientsFromAudience($promotion->target_audience);

            $emailsSent = 0;
            $notificationsSent = 0;
            $emailsFailed = 0;
            $notificationsFailed = 0;
            $errorMessages = [];

            foreach ($recipients as $user) {
                try {
                    // Notify once - the notification class handles both email and database channels
                    $user->notify(new PromotionNotification($promotion));
                    
                    // Count based on what's enabled in the promotion
                    // Note: Since notifications are queued, we count based on enabled channels
                    // Actual email delivery will be handled by the queue worker
                    if ($promotion->send_email) {
                        $emailsSent++;
                    }
                    if ($promotion->send_notification) {
                        $notificationsSent++;
                    }
                } catch (\Exception $e) {
                    // Count failures based on what was attempted
                    if ($promotion->send_email) {
                        $emailsFailed++;
                    }
                    if ($promotion->send_notification) {
                        $notificationsFailed++;
                    }
                    $errorMessages[] = "User {$user->id}: " . $e->getMessage();
                    Log::error('Failed to send promotion notification', [
                        'user_id' => $user->id,
                        'promotion_id' => $promotion->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            $promotion->update([
                'status' => 'sent',
                'sent_at' => now(),
                'emails_sent' => $emailsSent,
                'notifications_sent' => $notificationsSent,
                'emails_failed' => $emailsFailed,
                'notifications_failed' => $notificationsFailed,
                'error_message' => !empty($errorMessages) ? implode('; ', array_slice($errorMessages, 0, 5)) : null,
            ]);

            session()->flash('success', "Promotion sent successfully! Emails: {$emailsSent}, Notifications: {$notificationsSent}");
            $this->closeSendModal();

        } catch (\Exception $e) {
            Log::error('Failed to send promotion', [
                'promotion_id' => $promotionId,
                'error' => $e->getMessage()
            ]);
            
            if (isset($promotion)) {
                $promotion->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage()
                ]);
            }
            
            session()->flash('error', 'Failed to send promotion. Please try again.');
        }
    }

    public function getTargetRecipientsFromAudience($targetAudience)
    {
        $query = User::query();

        if (!empty($targetAudience['roles'])) {
            $query->whereIn('role', $targetAudience['roles']);
        }

        if (!empty($targetAudience['new_customers']) && !empty($targetAudience['registration_days'])) {
            $daysAgo = Carbon::now()->subDays($targetAudience['registration_days']);
            $query->where('created_at', '>=', $daysAgo);
        }

        if (!empty($targetAudience['no_loans'])) {
            $query->whereDoesntHave('applications');
        }

        if (!empty($targetAudience['incomplete_registration'])) {
            $query->whereNull('nida_verified_at');
        }

        if (!empty($targetAudience['credit_score_min'])) {
            $query->where('credit_score', '>=', $targetAudience['credit_score_min']);
        }
        if (!empty($targetAudience['credit_score_max'])) {
            $query->where('credit_score', '<=', $targetAudience['credit_score_max']);
        }

        if (!empty($targetAudience['city'])) {
            $query->where(function($q) use ($targetAudience) {
                $q->whereHas('applications', function($subQ) use ($targetAudience) {
                    $subQ->where('current_city', $targetAudience['city']);
                })->orWhereHas('profile', function($subQ) use ($targetAudience) {
                    $subQ->where('city', $targetAudience['city']);
                });
            });
        }

        if (!empty($targetAudience['region'])) {
            $query->where(function($q) use ($targetAudience) {
                $q->whereHas('applications', function($subQ) use ($targetAudience) {
                    $subQ->where('current_region', $targetAudience['region']);
                })->orWhereHas('profile', function($subQ) use ($targetAudience) {
                    $subQ->where('region', $targetAudience['region']);
                });
            });
        }

        $query->where('is_active', true);

        return $query->get();
    }

    public function createSamplePromotion()
    {
        $this->creatingSample = true;
        
        try {
            $admins = User::where('role', 'super_admin')->where('is_active', true)->get();
            
            if ($admins->isEmpty()) {
                session()->flash('error', 'No admin users found.');
                $this->creatingSample = false;
                return;
            }

            $targetAudience = [
                'roles' => ['super_admin'],
                'new_customers' => false,
                'no_loans' => false,
                'incomplete_registration' => false,
            ];

            $promotion = Promotion::create([
                'title' => 'Welcome to Fanikisha Market place Admin Panel!',
                'message' => 'You now have access to the new promotion management system. You can create and send targeted promotions to users, lenders, and borrowers. Explore the new features and start engaging with your users!',
                'target_audience' => $targetAudience,
                'send_email' => true, // Enable email for sample
                'send_notification' => true,
                'status' => 'draft',
                'total_recipients' => $admins->count(),
                'created_by' => Auth::id(),
            ]);

            // Send immediately
            $this->sendPromotion($promotion->id);

            session()->flash('success', 'Sample promotion created and sent to all admin users!');
            $this->resetPage();
            $this->creatingSample = false;

        } catch (\Exception $e) {
            Log::error('Failed to create sample promotion', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            session()->flash('error', 'Failed to create sample promotion. Please try again.');
            $this->creatingSample = false;
        }
    }

    public function render()
    {
        $promotions = Promotion::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $roles = ['borrower', 'lender', 'super_admin'];
        $cities = Application::distinct()
            ->whereNotNull('current_city')
            ->pluck('current_city')
            ->filter()
            ->unique()
            ->values()
            ->toArray();
        
        $regions = Application::distinct()
            ->whereNotNull('current_region')
            ->pluck('current_region')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        return view('livewire.admin.promotion-management', [
            'promotions' => $promotions,
            'roles' => $roles,
            'cities' => $cities,
            'regions' => $regions,
        ]);
    }
}
