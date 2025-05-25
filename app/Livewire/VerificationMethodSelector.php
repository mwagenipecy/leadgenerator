<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class VerificationMethodSelector extends Component
{
    public $selectedMethod = '';
    public $deviceType = '';

    public function mount()
    {
        $this->detectDevice();
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
        $this->selectedMethod = $method;
        
        // Redirect to specific verification page based on method
        switch ($method) {
            case 'phone_photo':
                return redirect()->route('verification.phone-photo');
            case 'qr_code':
                return redirect()->route('verification.qr-code');
            case 'questionnaire':
                return redirect()->route('verification.questionnaire');
            default:
                return redirect()->route('verification.method');
        }
    }
}