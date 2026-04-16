<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VerificationMethodSelector extends Component
{
    public $selectedMethod = '';
    public $deviceType = '';
    public $nidaNumber = '';
    public $isEditingNida = false;
    public $nidaConfirmed = null;

    public function mount()
    {
        $this->detectDevice();
        $this->nidaNumber = (string) (Auth::user()?->nida_number ?? '');
        $this->nidaConfirmed = $this->hasValidNida();
    }

    public function render()
    {
        return view('livewire.verification-method-selector');
    }

    private function detectDevice()
    {
        $userAgent = request()->header('User-Agent');
        $isMobile = preg_match('/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i', $userAgent);
        $this->deviceType = $isMobile ? 'mobile' : 'desktop';
    }

    public function selectMethod($method)
    {
        // Questionnaire is the only active method for now.
        if ($method !== 'questionnaire') {
            session()->flash('info', 'This method is temporarily unavailable. Please use Questionnaire verification.');
            return;
        }

        $this->selectedMethod = 'questionnaire';

        if (!$this->hasValidNida()) {
            $this->isEditingNida = true;
            session()->flash('error', 'Please update your NIDA number before continuing.');
            return;
        }

        return redirect()->route('verification.questionnaire');
    }

    public function enableNidaEdit(): void
    {
        $this->isEditingNida = true;
        $this->nidaConfirmed = false;
    }

    public function cancelNidaEdit(): void
    {
        $this->isEditingNida = false;
        $this->nidaNumber = (string) (Auth::user()?->nida_number ?? '');
        $this->nidaConfirmed = $this->hasValidNida();
        $this->resetValidation('nidaNumber');
    }

    public function saveNidaNumber(): void
    {
        $this->validate([
            'nidaNumber' => [
                'required',
                'string',
                'size:20',
                'regex:/^[0-9]{20}$/',
                Rule::unique('users', 'nida_number')->ignore(Auth::id()),
            ],
        ], [
            'nidaNumber.required' => 'NIDA number is required.',
            'nidaNumber.size' => 'NIDA number must be exactly 20 digits.',
            'nidaNumber.regex' => 'NIDA number must contain digits only.',
            'nidaNumber.unique' => 'This NIDA number is already in use.',
        ]);

        $user = Auth::user();
        if (!$user) {
            return;
        }

        $updateData = ['nida_number' => $this->nidaNumber];
        if ($user->registration_type === 'company' && $user->isFromTanzania()) {
            $updateData['company_contact_nida'] = $this->nidaNumber;
        }

        $user->update($updateData);
        $this->isEditingNida = false;
        $this->nidaConfirmed = true;
        session()->flash('success', 'NIDA number updated successfully.');
    }

    public function continueWithQuestionnaire()
    {
        if ($this->nidaConfirmed !== true) {
            session()->flash('error', 'Please confirm your NIDA number before continuing.');
            return;
        }

        if (!$this->hasValidNida()) {
            $this->isEditingNida = true;
            session()->flash('error', 'Please provide a valid 20-digit NIDA number first.');
            return;
        }

        return redirect()->route('verification.questionnaire');
    }

    public function updatedNidaNumber($value): void
    {
        $this->nidaNumber = preg_replace('/[^0-9]/', '', (string) $value);
        $this->nidaConfirmed = false;
    }

    public function confirmNidaCorrect(): void
    {
        if (!$this->hasValidNida()) {
            $this->isEditingNida = true;
            session()->flash('error', 'Please provide a valid 20-digit NIDA number first.');
            return;
        }

        $this->nidaConfirmed = true;
    }

    public function confirmNidaIncorrect(): void
    {
        $this->nidaConfirmed = false;
        $this->isEditingNida = true;
    }

    private function hasValidNida(): bool
    {
        return is_string($this->nidaNumber) && preg_match('/^[0-9]{20}$/', $this->nidaNumber) === 1;
    }
}