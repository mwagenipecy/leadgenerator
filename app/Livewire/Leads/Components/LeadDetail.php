<?php

namespace App\Livewire\Leads\Components;

use Livewire\Component;
use App\Models\Application;
use App\Models\ApplicationLenderSubmission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeadDetail extends Component
{
    public $leadId;
    public $isAvailable;
    public $lead;
    public $application;
    public $activeTab = 'overview';
    
    // Form fields for processing
    public $leadNotes = '';
    public $offerAmount = null;
    public $offerInterestRate = null;
    public $offerTenure = null;

    public function mount($leadId, $isAvailable = true)
    {
        $this->leadId = $leadId;
        $this->isAvailable = false ; // $isAvailable;
        $this->loadLead();
    }

    public function loadLead()
    {
        if ($this->isAvailable) {
            $this->lead = Application::with(['loanProduct', 'user'])
                ->where('booking_status', 'unbooked')
                ->where('status', 'submitted')
                ->find($this->leadId);
        } else {
            
            $this->lead = ApplicationLenderSubmission::with(['application.loanProduct', 'application.user', 'lender'])
                ->where('lender_id', Auth::user()->lender_id)
                ->find($this->leadId);
        }
        
        $this->application = $this->isAvailable ? $this->lead : $this->lead->application;

       // dd($this->application);
    }

    public function switchTab($tabName)
    {
        $this->activeTab = $tabName;
    }

    public function bookLead()
    {
        if (!$this->isAvailable) {
            return;
        }

        try {
            DB::beginTransaction();

            $application = Application::where('id', $this->leadId)
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
                'application_id' => $this->leadId,
                'lender_id' => Auth::user()->lender_id,
                'submission_id' => $submission->id
            ]);

            DB::commit();

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Lead booked successfully! You can now process this application.'
            ]);

            // Refresh and navigate back
            $this->dispatch('leadBooked');
            $this->dispatch('backToList');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to book lead', [
                'application_id' => $this->leadId,
                'error' => $e->getMessage(),
                'lender_id' => Auth::user()->lender_id
            ]);

            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Failed to book lead. Please try again.'
            ]);
        }
    }

    public function processLead($decision)
    {
        // if ($this->isAvailable) {
        //     return;
        // }

        try {
            DB::beginTransaction();

            $submission = ApplicationLenderSubmission::where('id', $this->leadId)
                ->where('lender_id', Auth::user()->lender_id)
                ->first();

            if (!$submission) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => 'Submission not found.'
                ]);
                return;
            }

            if ($decision === 'approve') {
                $submission->update([
                    // 'status' => 'approved',
                    'decision_at' => now(),
                    'offered_amount' => $this->offerAmount,
                    'offered_interest_rate' => $this->offerInterestRate,
                    'offered_tenure_months' => $this->offerTenure,
                    'lender_response' => [
                        'decision' => 'approved',
                        'notes' => $this->leadNotes,
                        'processed_by' => Auth::id()
                    ]
                ]);

                $submission->application->update([
                    'status' => 'approved',
                    'approved_at' => now(),
                    'notes' => $this->leadNotes
                ]);

            } elseif ($decision === 'reject') {
                $submission->update([
                    'status' => 'rejected',
                    'decision_at' => now(),
                    'rejection_reason' => $this->leadNotes,
                    'lender_response' => [
                        'decision' => 'rejected',
                        'notes' => $this->leadNotes,
                        'processed_by' => Auth::id()
                    ]
                ]);

                ApplicationLenderSubmission::where('application_id', $submission->application_id)
                    ->where('status', 'withdrawn')
                    ->update([
                        'status' => 'submitted',
                    ]);



                    // $submission->application->update([
                    //     'status' => 'submitted',
                    //     'notes' => $this->leadNotes,
                    //     'lender_id' => null,
                    //     'booking_status' => 'unbooked',
                    // ]);


                // Return to market for other lenders
                $submission->application->update([
                    'booking_status' => 'unbooked',
                    'lender_id' => null,
                    'status' => 'submitted',
                    'reviewed_at' => null,
                    'reviewed_by' => null
                ]);
            }elseif($decision =='disbursed'){

                $submission->application->update([
                    'status' => 'disbursed',
                    'approved_at' => now(),
                    'notes' => $this->leadNotes
                ]);
                
            }

            DB::commit();

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => ucfirst($decision) . ' processed successfully.'
            ]);

            $this->resetForm();
            $this->dispatch('leadProcessed');
            $this->dispatch('backToList');

            return redirect()->route('application.list');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to process lead', [
                'submission_id' => $this->leadId,
                'decision' => $decision,
                'error' => $e->getMessage()
            ]);

            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Failed to process lead. Please try again.'
            ]);
        }
    }

    public function backToList()
    {
        $this->dispatch('backToList');
    }

    private function resetForm()
    {
        $this->leadNotes = '';
        $this->offerAmount = null;
        $this->offerInterestRate = null;
        $this->offerTenure = null;
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
        return view('livewire.leads.components.lead-detail');
    }
}