<div>

<div class="space-y-6">
    <!-- Add Note Section (only for booked leads) -->
    @if(!$isAvailable)
        <div class="bg-gray-50 rounded-lg p-6">
            <h4 class="text-lg font-semibold text-black mb-4">Add Note</h4>
            <div class="space-y-4">
                <div>
                    <label for="noteText" class="block text-sm font-medium text-gray-700 mb-2">Note</label>
                    <textarea wire:model="noteText" id="noteText" rows="3" 
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green"
                              placeholder="Add your notes about this lead..."></textarea>
                </div>
                <div class="flex items-center space-x-3">
                    <button wire:click="addNote" 
                            class="inline-flex items-center px-4 py-2 bg-sidebar-green text-white rounded-lg text-sm font-medium hover:bg-sidebar-green-light transition-colors">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add Note
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Application Timeline -->
    <div>
        <h3 class="text-lg font-semibold text-black mb-4">Application Timeline</h3>
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <div class="flow-root">
                <ul class="-mb-8">
                    <!-- Application Submitted -->
                    <li>
                        <div class="relative pb-8">
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-sidebar-green flex items-center justify-center ring-8 ring-white">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5">
                                    <div>
                                        <p class="text-sm font-medium text-black">Application submitted</p>
                                        <p class="text-xs text-gray-500">{{ $application->created_at->format('M d, Y H:i') }}</p>
                                        <p class="text-xs text-gray-400">{{ $application->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                            @if($application->reviewed_at || $application->approved_at || $application->disbursed_at || ($lead && $lead->submitted_at))
                                <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></div>
                            @endif
                        </div>
                    </li>

                    <!-- Lead Booked (if applicable) -->
                    @if($lead && $lead->submitted_at)
                        <li>
                            <div class="relative pb-8">
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-sidebar-green flex items-center justify-center ring-8 ring-white">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5">
                                        <div>
                                            <p class="text-sm font-medium text-black">Lead booked</p>
                                            @if($lead->lender)

    
                                                <p class="text-xs text-gray-600">by {{ $lead->lender->company_name }}</p>
                                            @endif
                                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($lead->submitted_at)->format('M d, Y H:i') }}</p>
                                            <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($lead->submitted_at)->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                                @if($application->reviewed_at || $application->approved_at || $application->disbursed_at || ($lead && $lead->decision_at))
                                    <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></div>
                                @endif
                            </div>
                        </li>
                    @endif

                    <!-- Review Started -->
                    @if($application->reviewed_at)
                        <li>
                            <div class="relative pb-8">
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-yellow-500 flex items-center justify-center ring-8 ring-white">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5">
                                        <div>
                                            <p class="text-sm font-medium text-black">Review started</p>
                                            <p class="text-xs text-gray-500">{{ $application->reviewed_at->format('M d, Y H:i') }}</p>
                                            <p class="text-xs text-gray-400">{{ $application->reviewed_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                                @if($application->approved_at || $application->disbursed_at || ($lead && $lead->decision_at))
                                    <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></div>
                                @endif
                            </div>
                        </li>
                    @endif

                   

                    <!-- Application Approved -->
                    @if($application->approved_at)
                        <li>
                            <div class="relative pb-8">
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5">
                                        <div>
                                            <p class="text-sm font-medium text-black">Application approved</p>
                                            <p class="text-xs text-gray-500">{{ $application->approved_at->format('M d, Y H:i') }}</p>
                                            <p class="text-xs text-gray-400">{{ $application->approved_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                                @if($application->disbursed_at)
                                    <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></div>
                                @endif
                            </div>
                        </li>
                    @endif

                    <!-- Application Rejected -->
                    @if($application->status === 'rejected' && $application->reviewed_at)
                        <li>
                            <div class="relative pb-8">
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-sidebar-green flex items-center justify-center ring-8 ring-white">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5">
                                        <div>
                                            <p class="text-sm font-medium text-black">Application rejected</p>
                                            @if($application->rejection_reasons && count($application->rejection_reasons) > 0)
                                                <div class="mt-1">
                                                    <p class="text-xs text-gray-600 mb-1">Rejection reasons:</p>
                                                    <ul class="text-xs text-sidebar-green list-disc list-inside">
                                                        @foreach($application->rejection_reasons as $reason)
                                                            <li>{{ $reason }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                            <p class="text-xs text-gray-500">{{ $application->reviewed_at->format('M d, Y H:i') }}</p>
                                            <p class="text-xs text-gray-400">{{ $application->reviewed_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endif

                    <!-- Loan Disbursed -->
                    @if($application->disbursed_at)
                        <li>
                            <div class="relative">
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-sidebar-green flex items-center justify-center ring-8 ring-white">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5">
                                        <div>
                                            <p class="text-sm font-medium text-black">Loan disbursed</p>
                                            <p class="text-xs text-gray-500">{{ $application->disbursed_at->format('M d, Y H:i') }}</p>
                                            <p class="text-xs text-gray-400">{{ $application->disbursed_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    <!-- Notes Section -->
    @if(!$isAvailable)
        <div>
            <h3 class="text-lg font-semibold text-black mb-4">Notes & Communications</h3>
            
            <!-- Existing Notes -->
            @if($application->notes)
                <div class="bg-white border border-gray-200 rounded-lg p-6 mb-4">
                    <div class="flex items-start space-x-4">
                        <div class="h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                <h4 class="text-sm font-medium text-black">System Note</h4>
                                <span class="text-xs text-gray-500">{{ $application->updated_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-gray-700">{{ $application->notes }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Communication Log Placeholder -->
            <div class="bg-gray-50 rounded-lg p-6">
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-black">No communications yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Email and SMS communications with the applicant will appear here.</p>
                </div>
            </div>
        </div>
    @else
        <!-- Protected view for available leads -->
        <div>
            <h3 class="text-lg font-semibold text-black mb-4">Notes & Communications</h3>
            <div class="bg-gray-50 rounded-lg p-6">
                <div class="text-center py-8">
                    <div class="text-gray-400 mb-4">
                        <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Notes Protected</h3>
                    <p class="text-sm text-gray-500">Book this lead to view and add notes.</p>
                </div>
            </div>
        </div>
    @endif
</div>

</div>
