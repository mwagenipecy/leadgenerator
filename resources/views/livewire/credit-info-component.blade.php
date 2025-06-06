<div class="max-w-7xl mx-auto p-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold text-gray-900">Credit Information Requests</h1>
            <button wire:click="toggleManualForm" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
                {{ $showManualForm ? 'Cancel' : 'Manual Check' }}
            </button>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Manual Form -->
        @if($showManualForm)
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Manual Credit Check</h3>
                <form wire:submit.prevent="checkCreditInfo">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">National ID</label>
                            <input type="text" wire:model="national_id" 
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('national_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                            <input type="text" wire:model="first_name" 
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('first_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                            <input type="text" wire:model="last_name" 
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('last_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                            <input type="date" wire:model="date_of_birth" 
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('date_of_birth') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="text" wire:model="phone_number" 
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('phone_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Loan Application (Optional)</label>
                            <select wire:model="loan_id" 
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select Application</option>
                                @foreach($applications as $app)
                                    <option value="{{ $app->id }}">{{ $app->application_number }} - {{ $app->first_name }} {{ $app->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" 
                                wire:loading.attr="disabled"
                                class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md font-medium disabled:opacity-50">
                            <span wire:loading.remove>Check Credit Info</span>
                            <span wire:loading>Processing...</span>
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <!-- Filters -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" wire:model="search" placeholder="National ID, Name, Application..." 
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select wire:model="statusFilter" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="success">Success</option>
                    <option value="failed">Failed</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Loan Application</label>
                <select wire:model="selectedLoanId" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Applications</option>
                    @foreach($applications as $app)
                        <option value="{{ $app->id }}">{{ $app->application_number }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Results Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Application Details
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Personal Info
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Credit Scores
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($creditRequests as $request)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $request->application_number ?? 'Manual Check' }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        ID: {{ $request->national_id }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $request->created_at->format('M d, Y H:i') }}
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $request->full_name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        DOB: {{ $request->date_of_birth->format('M d, Y') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $request->phone_number }}
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($request->isSuccessful())
                                    <div>
                                        <div class="text-sm">
                                            <span class="font-medium">CIP:</span> 
                                            {{ $request->cip_score }} ({{ $request->cip_grade }})
                                        </div>
                                        <div class="text-sm">
                                            <span class="font-medium">Mobile:</span> 
                                            {{ $request->mobile_score }} ({{ $request->mobile_grade }})
                                        </div>
                                        <div class="text-sm">
                                            <span class="font-medium">Decision:</span> 
                                            <span class="px-2 py-1 text-xs rounded-full {{ $request->decision === 'Approve' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $request->decision }}
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">N/A</span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($request->status === 'success')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Success
                                    </span>
                                @elseif($request->status === 'failed')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Failed
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                @endif
                                
                                @if($request->error_message)
                                    <div class="text-xs text-red-600 mt-1">
                                        {{ Str::limit($request->error_message, 50) }}
                                    </div>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button wire:click="viewDetails({{ $request->id }})" 
                                            class="text-blue-600 hover:text-blue-900">
                                        View Details
                                    </button>
                                    
                                    @if($request->application)
                                        <button wire:click="checkCreditInfo({{ $request->application->id }})" 
                                                class="text-green-600 hover:text-green-900">
                                            Re-check
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No credit information requests found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $creditRequests->links() }}
        </div>
    </div>

    <!-- Details Modal -->
    @if($showModal && $selectedRequest)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Credit Information Details</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 mb-3">Basic Information</h4>
                        <div class="space-y-2 text-sm">
                            <div><span class="font-medium">Name:</span> {{ $selectedRequest->full_name }}</div>
                            <div><span class="font-medium">National ID:</span> {{ $selectedRequest->national_id }}</div>
                            <div><span class="font-medium">Phone:</span> {{ $selectedRequest->phone_number }}</div>
                            <div><span class="font-medium">DOB:</span> {{ $selectedRequest->date_of_birth->format('M d, Y') }}</div>
                            <div><span class="font-medium">Status:</span> 
                                <span class="px-2 py-1 text-xs rounded-full {{ $selectedRequest->status === 'success' ? 'bg-green-100 text-green-800' : ($selectedRequest->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($selectedRequest->status) }}
                                </span>
                            </div>
                            @if($selectedRequest->application)
                                <div><span class="font-medium">Application:</span> {{ $selectedRequest->application->application_number }}</div>
                            @endif
                        </div>
                    </div>

                    @if($selectedRequest->isSuccessful())
                        <!-- Credit Scores -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3">Credit Scores</h4>
                            <div class="space-y-2 text-sm">
                                <div><span class="font-medium">CIP Score:</span> {{ $selectedRequest->cip_score }} ({{ $selectedRequest->cip_grade }})</div>
                                <div><span class="font-medium">Mobile Score:</span> {{ $selectedRequest->mobile_score }} ({{ $selectedRequest->mobile_grade }})</div>
                                <div><span class="font-medium">Decision:</span> 
                                    <span class="px-2 py-1 text-xs rounded-full {{ $selectedRequest->decision === 'Approve' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $selectedRequest->decision }}
                                    </span>
                                </div>
                                <div><span class="font-medium">Reference:</span> {{ $selectedRequest->reference_number }}</div>
                            </div>
                        </div>

                        <!-- Financial Summary -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3">Financial Summary</h4>
                            <div class="space-y-2 text-sm">
                                <div><span class="font-medium">Past Due Amount:</span> TZS {{ number_format($selectedRequest->total_past_due_amount ?? 0) }}</div>
                                <div><span class="font-medium">Worst Past Due Days:</span> {{ $selectedRequest->worst_past_due_days ?? 0 }} days</div>
                                <div><span class="font-medium">Open Contracts:</span> {{ $selectedRequest->open_contracts ?? 0 }}</div>
                                <div><span class="font-medium">Closed Contracts:</span> {{ $selectedRequest->closed_contracts ?? 0 }}</div>
                            </div>
                        </div>

                        <!-- Contract Details -->
                        @if($selectedRequest->contract_details)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 mb-3">Contract Details</h4>
                                @foreach($selectedRequest->contract_details as $contract)
                                    <div class="border-b border-gray-200 pb-2 mb-2 last:border-b-0">
                                        <div class="text-sm">
                                            <div><span class="font-medium">Lender:</span> {{ $contract['Subscriber'] ?? 'N/A' }}</div>
                                            <div><span class="font-medium">Amount:</span> TZS {{ number_format($contract['TotalAmount']['Value'] ?? 0) }}</div>
                                            <div><span class="font-medium">Status:</span> {{ $contract['ContractStatus'] ?? 'N/A' }}</div>
                                            <div><span class="font-medium">Type:</span> {{ $contract['TypeOfContract'] ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif

                    @if($selectedRequest->error_message)
                        <!-- Error Information -->
                        <div class="bg-red-50 rounded-lg p-4 md:col-span-2">
                            <h4 class="font-semibold text-red-900 mb-3">Error Details</h4>
                            <p class="text-sm text-red-700">{{ $selectedRequest->error_message }}</p>
                        </div>
                    @endif
                </div>

                <!-- JSON Data Buttons -->
                <div class="mt-6 flex space-x-3">
                    @if($selectedRequest->request_payload)
                        <button wire:click="viewJson({{ json_encode($selectedRequest->request_payload) }}, 'Request Payload')" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm">
                            View Request JSON
                        </button>
                    @endif
                    
                    @if($selectedRequest->response_payload)
                        <button wire:click="viewJson({{ json_encode($selectedRequest->response_payload) }}, 'Response Payload')" 
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm">
                            View Response JSON
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- JSON Modal -->
    @if($showJsonModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-6xl shadow-lg rounded-md bg-white">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900">{{ $jsonTitle }}</h3>
                    <button wire:click="closeJsonModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="bg-gray-900 rounded-lg p-4 overflow-auto max-h-96">
                    <pre class="text-green-400 text-sm whitespace-pre-wrap">{{ $jsonData }}</pre>
                </div>
                
                <div class="mt-4 flex justify-end">
                    <button onclick="navigator.clipboard.writeText(this.previousElementSibling.querySelector('pre').textContent)" 
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm">
                        Copy to Clipboard
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Loading Overlay -->
    @if($isLoading)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                <p class="text-gray-700">Processing credit information request...</p>
            </div>
        </div>
    @endif
</div>