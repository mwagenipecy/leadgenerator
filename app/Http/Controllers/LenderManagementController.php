<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lender;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\LenderAccountCreated;

class LenderManagementController extends Controller
{
    public function index(){

        return view('pages.lenderManagement.index');
    }

    public function viewLender($lender){
        try {
            $lenderModel = Lender::with(['createdBy', 'updatedBy', 'approvedBy', 'user'])
                ->findOrFail($lender);
            
            // Get all users associated with this lender
            $users = \App\Models\User::where('lender_id', $lenderModel->id)->get();

            return view('pages.lenderManagement.viewLender', [
                'lender' => $lenderModel,
                'users' => $users
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('lenders.index')
                ->with('error', 'Lender not found.');
        }
    }

    public function approveLender(Request $request, $lender){
        $request->validate([
            'approval_notes' => 'nullable|string|max:1000'
        ]);

        try {
            $lenderModel = Lender::findOrFail($lender);
            
            if ($lenderModel->isPending()) {
                DB::transaction(function () use ($lenderModel, $request) {
                    // Create user account
                    $user = $lenderModel->createUserAccount(auth()->id());
                    
                    // Store approval notes if provided
                    if ($request->has('approval_notes') && !empty($request->approval_notes)) {
                        // You can add an approval_notes column to lenders table if needed
                        // For now, we'll log it
                        Log::info('Lender approved with notes', [
                            'lender_id' => $lenderModel->id,
                            'approval_notes' => $request->approval_notes,
                            'approved_by' => auth()->id()
                        ]);
                    }
                    
                    Log::info('Lender approved and user account created', [
                        'lender_id' => $lenderModel->id,
                        'user_id' => $user->id,
                        'approved_by' => auth()->id(),
                        'approval_notes' => $request->approval_notes ?? null
                    ]);
                });
                
                $message = 'Lender approved successfully! User account created and email notification sent.';
                if ($request->has('approval_notes') && !empty($request->approval_notes)) {
                    $message .= ' Approval notes have been recorded.';
                }
                
                return redirect()->route('lenders.view', $lenderModel->id)
                    ->with('success', $message);
            }
            
            return redirect()->route('lenders.view', $lenderModel->id)
                ->with('error', 'Lender cannot be approved in current status.');
                
        } catch (\Exception $e) {
            Log::error('Failed to approve lender', [
                'lender_id' => $lender,
                'error' => $e->getMessage(),
                'approved_by' => auth()->id()
            ]);
            
            return redirect()->route('lenders.view', $lender)
                ->with('error', 'Failed to approve lender. Please try again.');
        }
    }

    public function rejectLender(Request $request, $lender){
        $request->validate([
            'rejection_reason' => 'required|string|min:10'
        ]);

        try {
            $lenderModel = Lender::findOrFail($lender);
            
            if ($lenderModel->isPending()) {
                // Use model method which sends email
                $lenderModel->reject($request->rejection_reason);
                
                // Update updated_by
                $lenderModel->update(['updated_by' => auth()->id()]);
                
                Log::info('Lender application rejected', [
                    'lender_id' => $lender,
                    'rejection_reason' => $request->rejection_reason,
                    'rejected_by' => auth()->id()
                ]);
                
                return redirect()->route('lenders.view', $lenderModel->id)
                    ->with('success', 'Lender application rejected. Email notification sent.');
            }

            return redirect()->route('lenders.view', $lenderModel->id)
                ->with('error', 'Lender cannot be rejected in current status.');

        } catch (\Exception $e) {
            Log::error('Failed to reject lender', [
                'lender_id' => $lender,
                'error' => $e->getMessage(),
                'rejected_by' => auth()->id()
            ]);
            
            return redirect()->route('lenders.view', $lender)
                ->with('error', 'Failed to reject lender. Please try again.');
        }
    }

    public function suspendLender($lender){
        try {
            $lenderModel = Lender::findOrFail($lender);
            
            if ($lenderModel->isApproved()) {
                // Use model method which sends email
                $lenderModel->suspend();
                
                // Update updated_by
                $lenderModel->update(['updated_by' => auth()->id()]);
                
                Log::info('Lender suspended', [
                    'lender_id' => $lender,
                    'suspended_by' => auth()->id()
                ]);
                
                return redirect()->route('lenders.view', $lenderModel->id)
                    ->with('success', 'Lender suspended successfully. Email notification sent.');
            }
            
            return redirect()->route('lenders.view', $lenderModel->id)
                ->with('error', 'Only approved lenders can be suspended.');

        } catch (\Exception $e) {
            Log::error('Failed to suspend lender', [
                'lender_id' => $lender,
                'error' => $e->getMessage(),
                'suspended_by' => auth()->id()
            ]);
            
            return redirect()->route('lenders.view', $lender)
                ->with('error', 'Failed to suspend lender. Please try again.');
        }
    }

    public function reactivateLender($lender){
        try {
            $lenderModel = Lender::findOrFail($lender);
            
            if ($lenderModel->isSuspended()) {
                // Use model method which sends email
                $lenderModel->reactivate();
                
                // Update updated_by
                $lenderModel->update(['updated_by' => auth()->id()]);
                
                Log::info('Lender reactivated', [
                    'lender_id' => $lender,
                    'reactivated_by' => auth()->id()
                ]);
                
                return redirect()->route('lenders.view', $lenderModel->id)
                    ->with('success', 'Lender reactivated successfully. Email notification sent.');
            }

            return redirect()->route('lenders.view', $lenderModel->id)
                ->with('error', 'Only suspended lenders can be reactivated.');

        } catch (\Exception $e) {
            Log::error('Failed to reactivate lender', [
                'lender_id' => $lender,
                'error' => $e->getMessage(),
                'reactivated_by' => auth()->id()
            ]);
            
            return redirect()->route('lenders.view', $lender)
                ->with('error', 'Failed to reactivate lender. Please try again.');
        }
    }

    public function deleteLender($lender){
        try {
            $lenderModel = Lender::findOrFail($lender);
            
            DB::transaction(function () use ($lenderModel) {
                // Delete uploaded documents
                if ($lenderModel->documents) {
                    foreach ($lenderModel->documents as $document) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($document);
                    }
                }
                
                // Delete associated user if exists
                if ($lenderModel->user) {
                    $lenderModel->user->delete();
                }
                
                $lenderId = $lenderModel->id;
                $lenderName = $lenderModel->company_name;
                
                $lenderModel->delete();
                
                Log::info('Lender deleted', [
                    'deleted_lender_id' => $lenderId,
                    'deleted_lender_name' => $lenderName,
                    'deleted_by' => auth()->id()
                ]);
            });
            
            return redirect()->route('lenders.index')
                ->with('success', 'Lender deleted successfully.');
                
        } catch (\Exception $e) {
            Log::error('Failed to delete lender', [
                'lender_id' => $lender,
                'error' => $e->getMessage(),
                'deleted_by' => auth()->id()
            ]);
            
            return redirect()->route('lenders.view', $lender)
                ->with('error', 'Failed to delete lender. Please try again.');
        }
    }

    public function addUser(Request $request, $lender){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:lender,user',
            'is_active' => 'boolean'
        ]);

        try {
            $lenderModel = Lender::findOrFail($lender);
            
            // Only allow adding users to approved lenders
            if (!$lenderModel->isApproved()) {
                return redirect()->route('lenders.view', $lenderModel->id)
                    ->with('error', 'Users can only be added to approved lenders.');
            }

            DB::transaction(function () use ($request, $lenderModel) {
                // Generate secure password
                $password = Str::random(2) . rand(1000, 9999) . Str::random(2) . '!';
                
                // Create user
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'password' => Hash::make($password),
                    'role' => $request->role,
                    'lender_id' => $lenderModel->id,
                    'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
                    'email_verified_at' => now(),
                ]);

                // Send account creation email
                try {
                    Mail::to($user->email)->send(new LenderAccountCreated($user, $password));
                } catch (\Exception $e) {
                    Log::error('Failed to send lender user account creation email: ' . $e->getMessage());
                }

                // Log user creation
                if (class_exists(\App\Services\LogService::class)) {
                    \App\Services\LogService::log(
                        'lender_user_created',
                        "User {$user->email} was added to lender {$lenderModel->company_name}",
                        'high',
                        $user,
                        null,
                        $user->toArray(),
                        ['lender_id' => $lenderModel->id, 'lender_name' => $lenderModel->company_name]
                    );
                }

                Log::info('User added to lender', [
                    'user_id' => $user->id,
                    'lender_id' => $lenderModel->id,
                    'added_by' => auth()->id()
                ]);
            });
            
            return redirect()->route('lenders.view', $lenderModel->id)
                ->with('success', 'User added successfully! Login credentials have been sent via email.');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('lenders.view', $lender)
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Failed to add user to lender', [
                'lender_id' => $lender,
                'error' => $e->getMessage(),
                'added_by' => auth()->id()
            ]);
            
            return redirect()->route('lenders.view', $lender)
                ->with('error', 'Failed to add user. Please try again.');
        }
    }
}
