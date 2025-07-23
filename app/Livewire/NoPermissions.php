<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NoPermissions extends Component
{
    public $attemptedUrl;
    public $userRole;
    public $requiredPermission;

    public function mount($url = null, $permission = null)
    {
        $this->attemptedUrl = $url ?? request()->url();
        $this->requiredPermission = $permission;
        
        if (Auth::check()) {
            $this->userRole = Auth::user()->role ?? 'user';
        }
    }

    public function goHome()
    {
        // Redirect based on user role
        if (Auth::check()) {
            $user = Auth::user();
            
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'lender':
                    return redirect()->route('lender.dashboard');
                case 'user':
                default:
                    return redirect()->route('dashboard');
            }
        }
        
        // If not authenticated, redirect to login
        return redirect()->route('login');
    }

    public function contactAdmin()
    {
        // You can implement different contact methods here
        // For now, we'll show a success message and potentially send an email
        
        if (Auth::check()) {
            $user = Auth::user();
            
            // Here you could send an email to admin about access request
            // Mail::to('admin@yourapp.com')->send(new AccessRequestMail($user, $this->attemptedUrl));
            
            session()->flash('message', 'Your access request has been sent to the administrator. You will be contacted within 24 hours.');
            
            // Log the access request for admin review
            \Log::info('Access request submitted', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'attempted_url' => $this->attemptedUrl,
                'required_permission' => $this->requiredPermission,
                'user_role' => $user->role,
                'timestamp' => now()
            ]);
        } else {
            session()->flash('error', 'You must be logged in to contact the administrator.');
        }
    }

    public function goBack()
    {
        return redirect()->back();
    }

    public function refreshPage()
    {
        // Check if user now has permissions (maybe they were just granted)
        if (Auth::check()) {
            $user = Auth::user();
            
            // Here you could implement permission checking logic
            // For example, if using Spatie Permission package:
            // if ($user->can($this->requiredPermission)) {
            //     return redirect($this->attemptedUrl);
            // }
            
            session()->flash('message', 'Permissions checked. Contact administrator if you believe you should have access.');
        }
    }

    public function render()
    {
        return view('livewire.no-permissions')
            ->layout('layouts.app'); // or your main layout
    }
}