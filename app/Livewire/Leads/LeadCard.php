<?php

namespace App\Livewire\Leads\Components;

use Livewire\Component;
use App\Models\Application;
use App\Models\ApplicationLenderSubmission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeadCard extends Component
{
    public $lead;
    public $isAvailable;
    public $application;

    public function mount($lead, $isAvailable = true)
    {
        $this->lead = $lead;
        $this->isAvailable = $isAvailable;
        $this->application = $isAvailable ? $lead : $lead->application;
    }

    public function bookLead()
    {
        if (!$this->isAvailable) {
            return;
        }

        try {
            DB::beginTransaction();

            $application = Application::where('id', $this->lead->id)
                ->where('booking_status', 'unbooked')
                ->where('status', 'submitted')
                ->first();

            if (!$application) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => 'Lead is no longer available or has been booked by another lender.'
                ]);
                return;
            }

            // Create submission record
            $submission = ApplicationLenderSubmission::create([
                'user_id' => $application->user_id,
                'application_id' => $application->id,
                'lender_id' => Auth::user()->lender_id,
                'status' => 'submitted',
                'submitted_at' => now(),
                'submission_data' => [
                    'booked_by' => Auth::id(),
                    'booking_method' => 'manual',
                    'booking_timestamp' => now()
                ]
            ]);

            // Update application status
            $application->update([
                'booking_status' => 'booked',
                'lender_id' => Auth::user()->lender_id,
                'status' => 'under_review',
                'reviewed_at' => now(),
                'reviewed_by' => Auth::id()
            ]);

            Log::info('Lead booked successfully', [
                'application_id' => $this->lead->id,
                'lender_id' => Auth::user()->lender_id,
                'submission_id' => $submission->id
            ]);

            DB::commit();

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Lead booked successfully! You can now process this application.'
            ]);

            // Refresh parent component
            $this->dispatch('leadBooked');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to book lead', [
                'application_id' => $this->lead->id,
                'error' => $e->getMessage(),
                'lender_id' => Auth::user()->lender_id
            ]);

            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Failed to book lead. Please try again.'
            ]);
        }
    }

    public function viewLead()
    {
        $this->dispatch('viewLead', $this->lead->id, $this->isAvailable);
    }

    public function processLead($decision)
    {
        if ($this->isAvailable) {
            return;
        }

        $this->dispatch('openProcessModal', $this->lead->id, $decision);
    }

    public function getBlurredName()
    {
        if ($this->isAvailable) {
            $firstName = substr($this->application->first_name, 0, 1) . str_repeat('*', strlen($this->application->first_name) - 1);
            $lastName = substr($this->application->last_name, 0, 1) . str_repeat('*', strlen($this->application->last_name) - 1);
            return $firstName . ' ' . $lastName;
        }
        
        return $this->application->first_name . ' ' . $this->application->last_name;
    }

    public function getBlurredEmail()
    {
        if ($this->isAvailable) {
            $email = $this->application->email;
            $parts = explode('@', $email);
            $username = substr($parts[0], 0, 2) . str_repeat('*', strlen($parts[0]) - 2);
            $domain = explode('.', $parts[1]);
            $domainName = substr($domain[0], 0, 1) . str_repeat('*', strlen($domain[0]) - 1);
            return $username . '@' . $domainName . '.' . $domain[1];
        }
        
        return $this->application->email;
    }

    public function getBlurredPhone()
    {
        if ($this->isAvailable) {
            $phone = $this->application->phone_number;
            return substr($phone, 0, 4) . str_repeat('*', strlen($phone) - 7) . substr($phone, -3);
        }
        
        return $this->application->phone_number;
    }

    public function render()
    {
        return view('livewire.leads.components.lead-card');
    }
}