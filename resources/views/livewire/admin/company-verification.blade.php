<div class="p-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Company Verification</h1>
        <p class="text-gray-600 text-lg">Review and verify company registration requests</p>
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
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
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

    <!-- Companies Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Country</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">TIN</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Documents</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($companies as $company)
                    <tr>
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
                            @if($company->company_verification_status === 'verified')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Verified</span>
                            @elseif($company->company_verification_status === 'rejected')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-sidebar-green-100 text-sidebar-green-800">Rejected</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $company->companyVerificationDocuments->count() }} documents
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button wire:click="viewDocuments({{ $company->id }})" class="text-sidebar-green hover:text-brand-dark-red mr-3">
                                View
                            </button>
                            @if($company->company_verification_status === 'pending')
                                <button wire:click="openVerifyModal({{ $company->id }})" class="text-green-600 hover:text-green-800 mr-3">
                                    Verify
                                </button>
                                <button wire:click="openRejectModal({{ $company->id }})" class="text-sidebar-green hover:text-sidebar-green-800">
                                    Reject
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                            No companies found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $companies->links() }}
    </div>

    <!-- View Documents Modal -->
    @if($viewDocumentsModal && $selectedUser)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="closeModals">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white" wire:click.stop>
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Documents for {{ $selectedUser->company_name }}</h3>
                    <button wire:click="closeModals" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <div class="space-y-4 max-h-96 overflow-y-auto">
                    @forelse($selectedUser->companyVerificationDocuments as $document)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $document->document_name }}</p>
                                            <p class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $document->document_type)) }} • {{ $document->file_size_human }}</p>
                                        </div>
                                    </div>
                                    @if($document->status === 'verified')
                                        <span class="mt-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Verified</span>
                                    @elseif($document->status === 'rejected')
                                        <span class="mt-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-sidebar-green-100 text-sidebar-green-800">Rejected</span>
                                    @else
                                        <span class="mt-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                    @endif
                                </div>
                                <button wire:click="downloadDocument({{ $document->id }})" class="ml-4 text-sidebar-green hover:text-brand-dark-red">
                                    Download
                                </button>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 text-center">No documents uploaded.</p>
                    @endforelse
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button wire:click="closeModals" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        Close
                    </button>
                    @if($selectedUser->company_verification_status === 'pending')
                        <button wire:click="openVerifyModal({{ $selectedUser->id }})" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            Verify Company
                        </button>
                        <button wire:click="openRejectModal({{ $selectedUser->id }})" class="px-4 py-2 bg-sidebar-green text-white rounded-lg hover:bg-sidebar-green-light">
                            Reject
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Verify Modal -->
    @if($verifyModal && $selectedUser)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="closeModals">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white" wire:click.stop>
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Verify Company: {{ $selectedUser->company_name }}</h3>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Verification Notes (Optional)</label>
                    <textarea wire:model="verificationNotes" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-transparent" placeholder="Add any notes about this verification..."></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button wire:click="closeModals" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        Cancel
                    </button>
                    <button wire:click="verifyCompany" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Verify Company
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Reject Modal -->
    @if($rejectModal && $selectedUser)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="closeModals">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white" wire:click.stop>
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Company: {{ $selectedUser->company_name }}</h3>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason *</label>
                    <textarea wire:model="rejectionReason" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-transparent" placeholder="Please provide a reason for rejection..."></textarea>
                    @error('rejectionReason') <span class="text-sidebar-green text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <button wire:click="closeModals" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        Cancel
                    </button>
                    <button wire:click="rejectCompany" class="px-4 py-2 bg-sidebar-green text-white rounded-lg hover:bg-sidebar-green-light">
                        Reject Company
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
