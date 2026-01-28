<div class="p-4 sm:p-6 lg:p-8">
    <!-- Page Header -->
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-2">Company Verification</h1>
        <p class="text-gray-600 text-sm sm:text-base lg:text-lg">Review and verify company registration requests</p>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-green-800 text-sm">{{ session('success') }}</p>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-sidebar-green-50 border border-sidebar-green-200 rounded-lg">
            <p class="text-sidebar-green-800 text-sm">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 mb-4 sm:mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search Companies</label>
                <input wire:model.live="search" type="text" placeholder="Search by company name, email, TIN..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-transparent">
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Status</label>
                <select wire:model.live="statusFilter" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sidebar-green focus:border-transparent">
                    <option value="pending">Pending</option>
                    <option value="verified">Verified</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Companies Table - Desktop View -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden hidden md:block">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Country</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">TIN</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Application Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Documents</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($companies as $company)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $company->company_name }}</div>
                                <div class="text-sm text-gray-500">{{ $company->first_name }} {{ $company->last_name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $company->email }}</div>
                                <div class="text-sm text-gray-500">{{ $company->phone }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $company->country ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $company->company_tin }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $company->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $company->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($company->company_verification_status === 'verified')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Verified</span>
                                @elseif($company->company_verification_status === 'rejected')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $company->companyVerificationDocuments->count() }} documents
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.company.verification.show', $company->id) }}" 
                                       class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        View
                                    </a>
                                    @if($company->company_verification_status === 'pending')
                                        <button wire:click="openVerifyModal('{{ $company->id }}')" 
                                                class="inline-flex items-center px-3 py-1.5 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Verify
                                        </button>
                                        <button wire:click="openRejectModal('{{ $company->id }}')" 
                                                class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Reject
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                No companies found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Companies Cards - Mobile View -->
    <div class="md:hidden space-y-4">
        @forelse($companies as $company)
            <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold text-gray-900">{{ $company->company_name }}</h3>
                        <p class="text-xs text-gray-500 mt-1">{{ $company->first_name }} {{ $company->last_name }}</p>
                    </div>
                    <div>
                        @if($company->company_verification_status === 'verified')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Verified</span>
                        @elseif($company->company_verification_status === 'rejected')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                        @endif
                    </div>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex items-start">
                        <span class="text-xs font-medium text-gray-500 w-24 flex-shrink-0">Contact:</span>
                        <div class="flex-1">
                            <div class="text-xs text-gray-900">{{ $company->email }}</div>
                            <div class="text-xs text-gray-500">{{ $company->phone }}</div>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <span class="text-xs font-medium text-gray-500 w-24 flex-shrink-0">Country:</span>
                        <span class="text-xs text-gray-900">{{ $company->country ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-start">
                        <span class="text-xs font-medium text-gray-500 w-24 flex-shrink-0">TIN:</span>
                        <span class="text-xs text-gray-900">{{ $company->company_tin }}</span>
                    </div>
                    <div class="flex items-start">
                        <span class="text-xs font-medium text-gray-500 w-24 flex-shrink-0">Date:</span>
                        <div class="flex-1">
                            <div class="text-xs text-gray-900">{{ $company->created_at->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $company->created_at->format('h:i A') }}</div>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <span class="text-xs font-medium text-gray-500 w-24 flex-shrink-0">Documents:</span>
                        <span class="text-xs text-gray-900">{{ $company->companyVerificationDocuments->count() }} documents</span>
                    </div>
                </div>
                
                <!-- Action Buttons - Always visible on mobile -->
                <div class="flex flex-wrap gap-2 pt-3 border-t border-gray-200">
                    <a href="{{ route('admin.company.verification.show', $company->id) }}" 
                       class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors text-sm font-medium">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        View
                    </a>
                    @if($company->company_verification_status === 'pending')
                        <button wire:click="openVerifyModal('{{ $company->id }}')" 
                                class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors text-sm font-medium">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Verify
                        </button>
                        <button wire:click="openRejectModal('{{ $company->id }}')" 
                                class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-colors text-sm font-medium">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Reject
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                <p class="text-sm text-gray-500">No companies found.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $companies->links() }}
    </div>

    <!-- Verify Modal -->
    @if($verifyModal && $selectedUser)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center" wire:click="closeModals">
        <div class="relative mx-auto p-6 border w-full max-w-lg shadow-lg rounded-xl bg-white" wire:click.stop>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Verify Company</h3>
                <button wire:click="closeModals" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-sm text-green-800">
                    You are about to verify <strong>{{ $selectedUser->company_name }}</strong>. This will allow them to proceed with loan applications.
                </p>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Verification Notes (Optional)</label>
                <textarea wire:model="verificationNotes" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Add any notes about this verification..."></textarea>
            </div>

            <div class="flex justify-end space-x-3">
                <button wire:click="closeModals" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">
                    Cancel
                </button>
                <button wire:click="verifyCompany" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">
                    <span wire:loading.remove wire:target="verifyCompany">Verify Company</span>
                    <span wire:loading wire:target="verifyCompany">Processing...</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Reject Modal -->
    @if($rejectModal && $selectedUser)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center" wire:click="closeModals">
        <div class="relative mx-auto p-6 border w-full max-w-lg shadow-lg rounded-xl bg-white" wire:click.stop>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Reject Company</h3>
                <button wire:click="closeModals" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-sm text-red-800">
                    You are about to reject <strong>{{ $selectedUser->company_name }}</strong>. The company will be notified of the rejection reason.
                </p>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason *</label>
                <textarea wire:model="rejectionReason" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" placeholder="Please provide a detailed reason for rejection..."></textarea>
                @error('rejectionReason') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end space-x-3">
                <button wire:click="closeModals" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">
                    Cancel
                </button>
                <button wire:click="rejectCompany" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">
                    <span wire:loading.remove wire:target="rejectCompany">Reject Company</span>
                    <span wire:loading wire:target="rejectCompany">Processing...</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
