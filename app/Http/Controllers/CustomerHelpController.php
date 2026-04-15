<?php

namespace App\Http\Controllers;

use App\Models\CustomerHelpRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CustomerHelpController extends Controller
{
    public function index(): View
    {
        $tickets = collect();

        if (Auth::check()) {
            $tickets = CustomerHelpRequest::query()
                ->with(['messages.user'])
                ->where('user_id', Auth::id())
                ->latestFirst()
                ->get();
        }

        return view('pages.customer-help.index', [
            'tickets' => $tickets,
        ]);
    }

    public function createTicket(Request $request): RedirectResponse
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $user = $request->user();

        $ticket = CustomerHelpRequest::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'message' => $validated['message'],
            'status' => CustomerHelpRequest::STATUS_NEW,
        ]);

        $ticket->messages()->create([
            'user_id' => $user->id,
            'sender_type' => 'user',
            'message' => $validated['message'],
        ]);

        return back()->with('help_success', 'Your ticket has been created successfully.');
    }

    public function addMessage(Request $request, CustomerHelpRequest $customerHelpRequest): RedirectResponse
    {
        if (!Auth::check() || (int) $customerHelpRequest->user_id !== (int) Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $customerHelpRequest->messages()->create([
            'user_id' => Auth::id(),
            'sender_type' => 'user',
            'message' => $validated['message'],
        ]);

        $customerHelpRequest->update(['status' => CustomerHelpRequest::STATUS_NEW]);

        return back()->with('help_success', 'Message sent successfully.');
    }
}
