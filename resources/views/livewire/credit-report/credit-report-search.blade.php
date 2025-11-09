<div>
    <div class="max-w-6xl mx-auto p-6 bg-white rounded-lg shadow-lg">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-black mb-2">Credit Report Search</h2>
            <p class="text-gray-700">Search for individuals and generate credit reports</p>
        </div>

        <!-- Search Form -->
        <div class="bg-gray-50 p-4 rounded-lg mb-6 border border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label for="fullName" class="block text-sm font-medium text-black mb-1">
                        Full Name
                    </label>
                    <input 
                        type="text" 
                        id="fullName"
                        wire:model.live="fullName" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        placeholder="Enter full name"
                        wire:loading.attr="disabled"
                    >
                    @error('fullName') 
                        <span class="text-red-600 text-sm">{{ $message }}</span> 
                    @enderror
                </div>
                
                <div>
                    <label for="idNumber" class="block text-sm font-medium text-black mb-1">
                        ID Number
                    </label>
                    <input 
                        type="text" 
                        id="idNumber"
                        wire:model.live="idNumber" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        placeholder="Enter ID number"
                        wire:loading.attr="disabled"
                    >
                    @error('idNumber') 
                        <span class="text-red-600 text-sm">{{ $message }}</span> 
                    @enderror
                </div>
                
                <div>
                    <label for="phoneNumber" class="block text-sm font-medium text-black mb-1">
                        Phone Number
                    </label>
                    <input 
                        type="text" 
                        id="phoneNumber"
                        wire:model.live="phoneNumber" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        placeholder="Enter phone number"
                        wire:loading.attr="disabled"
                    >
                    @error('phoneNumber') 
                        <span class="text-red-600 text-sm">{{ $message }}</span> 
                    @enderror
                </div>
            </div>
            
            <div class="flex gap-3">
                <button 
                    wire:click="search" 
                    class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-6 rounded-md transition duration-200 flex items-center"
                    wire:loading.attr="disabled"
                    wire:target="search"
                >
                    <div wire:loading wire:target="search" class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                    <span wire:loading.remove wire:target="search">Search</span>
                    <span wire:loading wire:target="search">Searching...</span>
                </button>
                
                @if(count($searchResults) > 0)
                    <button 
                        wire:click="exportResults" 
                        class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition duration-200"
                    >
                        Export CSV
                    </button>
                @endif
            </div>
        </div>

        <!-- Loading State -->
        <div wire:loading wire:target="search" class="mb-6">
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-red-600 mr-3"></div>
                    <span class="text-red-700">Searching credit information...</span>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if($successMessage)
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-green-800 font-medium">{{ $successMessage }}</p>
                </div>
            </div>
        @endif

        <!-- Error Display -->
        @if($errorMessage)
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold text-red-800 mb-1">Error</h3>
                        <p class="text-red-700">{{ $errorMessage }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Search Results -->
        @if(count($searchResults) > 0)
            <div class="mb-6 bg-white border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-black">
                        Search Results ({{ count($searchResults) }} found)
                    </h3>
                </div>
                
                <!-- Results Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Full Name</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date of Birth</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">National ID</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($paginatedResults as $result)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                        {{ $result['FullName'] ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                        {{ isset($result['DateOfBirth']) ? \Carbon\Carbon::parse($result['DateOfBirth'])->format('Y-m-d') : 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                        {{ $result['NationalID'] ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $result['Address'] ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                        @if(isset($result['CreditinfoId']))
                                            <button 
                                                wire:click="getReport('{{ $result['CreditinfoId'] }}')" 
                                                class="text-red-600 hover:text-red-900"
                                                wire:loading.attr="disabled"
                                                wire:target="getReport"
                                            >
                                                <span wire:loading.remove wire:target="getReport">Generate Report</span>
                                                <span wire:loading wire:target="getReport">Loading...</span>
                                            </button>
                                        @else
                                            <span class="text-gray-400">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($this->getTotalPagesProperty() > 1)
                    <div class="mt-4 flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Showing {{ ($currentPage - 1) * $perPage + 1 }} to {{ min($currentPage * $perPage, count($searchResults)) }} of {{ count($searchResults) }} results
                        </div>
                        <div class="flex gap-2">
                            <button 
                                wire:click="previousPage" 
                                class="px-3 py-1 border border-gray-300 rounded-md text-sm hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                {{ $currentPage == 1 ? 'disabled' : '' }}
                            >
                                Previous
                            </button>
                            <span class="px-3 py-1 text-sm text-gray-700">
                                Page {{ $currentPage }} of {{ $this->getTotalPagesProperty() }}
                            </span>
                            <button 
                                wire:click="nextPage" 
                                class="px-3 py-1 border border-gray-300 rounded-md text-sm hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                {{ $currentPage >= $this->getTotalPagesProperty() ? 'disabled' : '' }}
                            >
                                Next
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Report Loading State -->
        <div wire:loading wire:target="getReport,autoGenerateReport" class="mb-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-600 mr-3"></div>
                    <span class="text-blue-700">Generating credit report...</span>
                </div>
            </div>
        </div>

        <!-- Report Error -->
        @if($reportError)
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold text-red-800 mb-1">Report Generation Error</h3>
                        <p class="text-red-700">{{ $reportError }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Report Display -->
        @if($reportUrl)
            <div class="mb-6 bg-white border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-black">Credit Report Generated</h3>
                    <button 
                        wire:click="clearSelection" 
                        class="text-gray-500 hover:text-gray-700 text-sm"
                    >
                        Close
                    </button>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <iframe 
                        src="{{ $reportUrl }}" 
                        class="w-full h-96 border border-gray-300 rounded"
                        frameborder="0"
                    ></iframe>
                </div>
                
                <div class="mt-4 flex gap-3">
                    <a 
                        href="{{ $reportUrl }}" 
                        target="_blank"
                        class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md transition duration-200"
                    >
                        Open in New Tab
                    </a>
                    <a 
                        href="{{ $reportUrl }}" 
                        download
                        class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition duration-200"
                    >
                        Download PDF
                    </a>
                </div>
            </div>
        @endif

        <!-- Company Statistics (if available) -->
        @if($companyStats)
            <div class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-black mb-4">Company Statistics</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-white p-3 rounded-lg border border-gray-200">
                        <div class="text-sm text-gray-600">Total Reports</div>
                        <div class="text-2xl font-bold text-black">{{ $companyStats['total_reports_generated'] ?? 0 }}</div>
                    </div>
                    <div class="bg-white p-3 rounded-lg border border-gray-200">
                        <div class="text-sm text-gray-600">This Month</div>
                        <div class="text-2xl font-bold text-black">{{ $companyStats['reports_this_month'] ?? 0 }}</div>
                    </div>
                    <div class="bg-white p-3 rounded-lg border border-gray-200">
                        <div class="text-sm text-gray-600">Active Accounts</div>
                        <div class="text-2xl font-bold text-black">{{ $companyStats['active_accounts'] ?? 0 }}</div>
                    </div>
                    <div class="bg-white p-3 rounded-lg border border-gray-200">
                        <div class="text-sm text-gray-600">Remaining Reports</div>
                        <div class="text-2xl font-bold text-black">{{ $companyStats['total_remaining_reports'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Credit Information Requests Section -->
        <div class="mt-6 bg-white shadow rounded-lg overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">Credit Information Requests</h2>
                <p class="text-sm text-gray-600 mt-1">View your credit information requests (filtered by your NIDA number)</p>
            </div>

            <!-- Filters -->
            <div class="p-4 bg-gray-50 border-b border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="creditInfoSearch"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                            placeholder="Search by name, NIDA, or application number..."
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select 
                            wire:model.live="creditInfoStatusFilter"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                        >
                            <option value="">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="success">Success</option>
                            <option value="failed">Failed</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Credit Info Requests Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Application Number</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Full Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIDA Number</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($creditInfoRequests as $request)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $request->application_number ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $request->full_name ?? ($request->first_name . ' ' . $request->last_name) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $request->national_id ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($request->status === 'success') bg-green-100 text-green-800
                                        @elseif($request->status === 'failed') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $request->requested_at ? $request->requested_at->format('Y-m-d H:i') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button 
                                        wire:click="viewCreditInfoDetails({{ $request->id }})"
                                        class="text-red-600 hover:text-red-900 mr-3"
                                    >
                                        View Details
                                    </button>
                                    @if($request->response_payload)
                                        <button 
                                            wire:click="viewCreditInfoJson(@js($request->response_payload), 'Response Payload')"
                                            class="text-blue-600 hover:text-blue-900"
                                        >
                                            View JSON
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No credit information requests found for your NIDA number.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($creditInfoRequests->hasPages())
                <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $creditInfoRequests->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Credit Info Details Modal -->
@if($showCreditInfoModal && $selectedCreditRequest)
    <div class="fixed z-50 inset-0 overflow-y-auto" wire:click="closeCreditInfoModal">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeCreditInfoModal"></div>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full" wire:click.stop>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Credit Information Request Details</h3>
                        <button wire:click="closeCreditInfoModal" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Application Number</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $selectedCreditRequest->application_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Full Name</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $selectedCreditRequest->full_name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">NIDA Number</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $selectedCreditRequest->national_id ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <p class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($selectedCreditRequest->status === 'success') bg-green-100 text-green-800
                                    @elseif($selectedCreditRequest->status === 'failed') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    {{ ucfirst($selectedCreditRequest->status) }}
                                </span>
                            </p>
                        </div>
                        @if($selectedCreditRequest->error_message)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Error Message</label>
                                <p class="mt-1 text-sm text-red-600">{{ $selectedCreditRequest->error_message }}</p>
                            </div>
                        @endif
                        @if($selectedCreditRequest->isSuccessful())
                            <div>
                                <label class="block text-sm font-medium text-gray-700">CIP Score</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $selectedCreditRequest->cip_score ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Mobile Score</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $selectedCreditRequest->mobile_score ?? 'N/A' }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button 
                        type="button" 
                        wire:click="closeCreditInfoModal"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- JSON Modal -->
@if($showCreditInfoJsonModal)
    <div class="fixed z-50 inset-0 overflow-y-auto" wire:click="closeCreditInfoJsonModal">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeCreditInfoJsonModal"></div>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full" wire:click.stop>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ $creditInfoJsonTitle }}</h3>
                        <button wire:click="closeCreditInfoJsonModal" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="overflow-auto max-h-96 bg-gray-50 p-4 rounded">
                        <pre class="text-xs">{{ $creditInfoJsonData }}</pre>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button 
                        type="button" 
                        wire:click="closeCreditInfoJsonModal"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

