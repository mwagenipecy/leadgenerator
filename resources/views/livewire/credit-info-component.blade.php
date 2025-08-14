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
                            Credit Analysis
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Financial Summary
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
                        @php
                            $responseData = $request->response_payload;
                            $creditData = $responseData['credit_data'] ?? null;
                            $extract = $creditData['extract'] ?? null;
                            $currentContracts = $creditData['current_contracts'] ?? null;
                            $pastDueInfo = $creditData['past_due_information'] ?? null;
                            $generalInfo = $creditData['general_information'] ?? null;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $request->application_number ?? 'Manual Check' }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        ID: {{ $extract['NationalId']['_value'] ?? $request->national_id }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $request->created_at->format('M d, Y H:i') }}
                                    </div>
                                    @if($extract && isset($extract['MobilePhone']))
                                        <div class="text-sm text-gray-500">
                                            Phone: {{ $extract['MobilePhone']['_value'] }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $request->full_name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        DOB: {{ $request->date_of_birth ?->format('M d, Y') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $request->phone_number ?? "N/A" }}
                                    </div>
                                    @if($generalInfo && isset($generalInfo['ReferenceNumber']))
                                        <div class="text-sm text-gray-500">
                                            Ref: {{ $generalInfo['ReferenceNumber']['_value'] }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($request->isSuccessful() && $extract)
                                    <div class="space-y-1">
                                        <div class="text-sm">
                                            <span class="font-medium">CIP Score:</span> 
                                            <span class="px-2 py-1 text-xs rounded-full {{ $extract['CIPScore']['_value'] == 999 ? 'bg-gray-100 text-gray-600' : 'bg-blue-100 text-blue-800' }}">
                                                {{ $extract['CIPScore']['_value'] }} ({{ $extract['CIPGrade']['_value'] }})
                                            </span>
                                        </div>
                                        <div class="text-sm">
                                            <span class="font-medium">Mobile Score:</span> 
                                            <span class="px-2 py-1 text-xs rounded-full {{ $extract['MobileScore']['_value'] == 999 ? 'bg-gray-100 text-gray-600' : 'bg-blue-100 text-blue-800' }}">
                                                {{ $extract['MobileScore']['_value'] }} ({{ $extract['MobileGrade']['_value'] }})
                                            </span>
                                        </div>
                                        <div class="text-sm">
                                            <span class="font-medium">Decision:</span> 
                                            <span class="px-2 py-1 text-xs rounded-full {{ $extract['Decision']['_value'] === 'Approve' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $extract['Decision']['_value'] }}
                                            </span>
                                        </div>
                                        @if($generalInfo && isset($generalInfo['BrokenRules']))
                                            <div class="text-sm">
                                                <span class="font-medium">Broken Rules:</span> 
                                                <span class="text-{{ $generalInfo['BrokenRules']['_value'] > 0 ? 'red' : 'green' }}-600">
                                                    {{ $generalInfo['BrokenRules']['_value'] }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">N/A</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($request->isSuccessful() && $currentContracts)
                                    <div class="space-y-1 text-sm">
                                        <div>
                                            <span class="font-medium">Total Balance:</span> 
                                            <span class="text-blue-600">
                                                TZS {{ number_format($currentContracts['Total']['Balance']['_value']) }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="font-medium">At Risk:</span> 
                                            <span class="text-red-600">
                                                TZS {{ number_format($currentContracts['Total']['BalanceAtRisk']['_value']) }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="font-medium">Contracts:</span> 
                                            <span class="text-green-600">{{ $currentContracts['Total']['Positive']['_value'] }}</span> / 
                                            <span class="text-red-600">{{ $currentContracts['Total']['Negative']['_value'] }}</span>
                                        </div>
                                        @if($pastDueInfo)
                                            <div>
                                                <span class="font-medium">Past Due:</span> 
                                                <span class="text-orange-600">
                                                    TZS {{ number_format($pastDueInfo['TotalCurrentPastDue']['_value']) }}
                                                </span>
                                            </div>
                                        @endif
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

                                @if($creditData && isset($creditData['hit_count']))
                                    <div class="text-xs text-gray-500 mt-1">
                                        Hits: {{ $creditData['hit_count'] }}
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
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
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
        @php
            $responseData = $selectedRequest->response_payload;
            $creditData = $responseData['credit_data'] ?? null;
            $extract = $creditData['extract'] ?? null;
            $currentContracts = $creditData['current_contracts'] ?? null;
            $pastDueInfo = $creditData['past_due_information'] ?? null;
            $repaymentInfo = $creditData['repayment_information'] ?? null;
            $inquiriesAnalysis = $creditData['inquiries_analysis'] ?? null;
            $scoringAnalysis = $creditData['scoring_analysis'] ?? null;
            $generalInfo = $creditData['general_information'] ?? null;
            $strategy = $creditData['strategy'] ?? null;
        @endphp
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-6xl shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Credit Information Details</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Basic Information -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 mb-3">Basic Information</h4>
                        <div class="space-y-2 text-sm">
                            <div><span class="font-medium">Name:</span> {{ $selectedRequest->full_name }}</div>
                            <div><span class="font-medium">National ID:</span> {{ $extract['NationalId']['_value'] ?? $selectedRequest->national_id }}</div>
                            @if($extract && isset($extract['MobilePhone']))
                                <div><span class="font-medium">Mobile:</span> {{ $extract['MobilePhone']['_value'] }}</div>
                            @endif
                            <div><span class="font-medium">DOB:</span> {{ $selectedRequest->date_of_birth ?->format('M d, Y') }}</div>
                            <div><span class="font-medium">Status:</span> 
                                <span class="px-2 py-1 text-xs rounded-full {{ $selectedRequest->status === 'success' ? 'bg-green-100 text-green-800' : ($selectedRequest->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($selectedRequest->status) }}
                                </span>
                            </div>
                            @if($selectedRequest->application)
                                <div><span class="font-medium">Application:</span> {{ $selectedRequest->application->application_number }}</div>
                            @endif
                            @if($generalInfo)
                                <div><span class="font-medium">Request Date:</span> {{ \Carbon\Carbon::parse($generalInfo['RequestDate']['_value'])->format('M d, Y H:i') }}</div>
                                @if(isset($generalInfo['ReferenceNumber']))
                                    <div><span class="font-medium">Reference:</span> {{ $generalInfo['ReferenceNumber']['_value'] ?: 'N/A' }}</div>
                                @endif
                            @endif
                        </div>
                    </div>

                    @if($selectedRequest->isSuccessful() && $extract)
                        <!-- Credit Scores & Decision -->
                        <div class="bg-blue-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3">Credit Analysis</h4>
                            <div class="space-y-2 text-sm">
                                <div><span class="font-medium">CIP Score:</span> 
                                    <span class="px-2 py-1 text-xs rounded-full {{ $extract['CIPScore']['_value'] == 999 ? 'bg-gray-100 text-gray-600' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $extract['CIPScore']['_value'] }} ({{ $extract['CIPGrade']['_value'] }})
                                    </span>
                                </div>
                                <div><span class="font-medium">Mobile Score:</span> 
                                    <span class="px-2 py-1 text-xs rounded-full {{ $extract['MobileScore']['_value'] == 999 ? 'bg-gray-100 text-gray-600' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $extract['MobileScore']['_value'] }} ({{ $extract['MobileGrade']['_value'] }})
                                    </span>
                                </div>
                                <div><span class="font-medium">Final Decision:</span> 
                                    <span class="px-2 py-1 text-xs rounded-full {{ $extract['Decision']['_value'] === 'Approve' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $extract['Decision']['_value'] }}
                                    </span>
                                </div>
                                @if($generalInfo)
                                    <div><span class="font-medium">Recommended Decision:</span> 
                                        <span class="px-2 py-1 text-xs rounded-full {{ $generalInfo['RecommendedDecision']['_value'] === 'Approve' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $generalInfo['RecommendedDecision']['_value'] }}
                                        </span>
                                    </div>
                                    <div><span class="font-medium">Broken Rules:</span> 
                                        <span class="px-2 py-1 text-xs rounded-full {{ $generalInfo['BrokenRules']['_value'] > 0 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $generalInfo['BrokenRules']['_value'] }}
                                        </span>
                                    </div>
                                @endif
                                @if($creditData)
                                    <div><span class="font-medium">Hit Count:</span> {{ $creditData['hit_count'] }}</div>
                                    <div><span class="font-medium">Currency:</span> {{ $creditData['currency']['_value'] }}</div>
                                @endif
                            </div>
                        </div>

                        <!-- Current Contracts Summary -->
                        @if($currentContracts)
                            <div class="bg-green-50 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 mb-3">Current Contracts</h4>
                                <div class="space-y-3 text-sm">
                                    <!-- Total Summary -->
                                    <div class="border-b pb-2">
                                        <div class="font-medium text-gray-800 mb-1">Total Portfolio</div>
                                        <div><span class="font-medium">Balance:</span> TZS {{ number_format($currentContracts['Total']['Balance']['_value']) }}</div>
                                        <div><span class="font-medium">At Risk:</span> <span class="text-red-600">TZS {{ number_format($currentContracts['Total']['BalanceAtRisk']['_value']) }}</span></div>
                                        <div><span class="font-medium">Positive:</span> <span class="text-green-600">{{ $currentContracts['Total']['Positive']['_value'] }}</span></div>
                                        <div><span class="font-medium">Negative:</span> <span class="text-red-600">{{ $currentContracts['Total']['Negative']['_value'] }}</span></div>
                                    </div>
                                    
                                    <!-- Banking -->
                                    <div class="border-b pb-2">
                                        <div class="font-medium text-gray-800 mb-1">Banking</div>
                                        <div><span class="font-medium">Balance:</span> TZS {{ number_format($currentContracts['CurrentBanking']['Balance']['_value']) }}</div>
                                        <div><span class="font-medium">At Risk:</span> TZS {{ number_format($currentContracts['CurrentBanking']['BalanceAtRisk']['_value']) }}</div>
                                        <div><span class="font-medium">Positive:</span> {{ $currentContracts['CurrentBanking']['Positive']['_value'] }} | <span class="font-medium">Negative:</span> {{ $currentContracts['CurrentBanking']['Negative']['_value'] }}</div>
                                    </div>
                                    
                                    <!-- Non-Banking -->
                                    <div>
                                        <div class="font-medium text-gray-800 mb-1">Non-Banking</div>
                                        <div><span class="font-medium">Balance:</span> TZS {{ number_format($currentContracts['CurrentNonBanking']['Balance']['_value']) }}</div>
                                        <div><span class="font-medium">At Risk:</span> TZS {{ number_format($currentContracts['CurrentNonBanking']['BalanceAtRisk']['_value']) }}</div>
                                        <div><span class="font-medium">Positive:</span> {{ $currentContracts['CurrentNonBanking']['Positive']['_value'] }} | <span class="font-medium">Negative:</span> {{ $currentContracts['CurrentNonBanking']['Negative']['_value'] }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Past Due Information -->
                        @if($pastDueInfo)
                            <div class="bg-orange-50 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 mb-3">Past Due Analysis</h4>
                                <div class="space-y-2 text-sm">
                                    <div><span class="font-medium">Current Past Due:</span> <span class="text-orange-600">TZS {{ number_format($pastDueInfo['TotalCurrentPastDue']['_value']) }}</span></div>
                                    <div><span class="font-medium">Current Days Past Due:</span> {{ $pastDueInfo['TotalCurrentDaysPastDue']['_value'] }} days</div>
                                    <div><span class="font-medium">Worst Current Past Due:</span> <span class="text-red-600">TZS {{ number_format($pastDueInfo['WorstCurrentPastDue']['_value']) }}</span></div>
                                    <div><span class="font-medium">Worst Current Days:</span> {{ $pastDueInfo['WorstCurrentDaysPastDue']['_value'] }} days</div>
                                    <div><span class="font-medium">Worst Last 12M:</span> TZS {{ number_format($pastDueInfo['WorstPastDueLast12Months']['_value']) }}</div>
                                    <div><span class="font-medium">Worst Days Last 12M:</span> {{ $pastDueInfo['WorstPastDueDaysLast12Months']['_value'] }} days</div>
                                    <div><span class="font-medium">Months w/o Arrears:</span> {{ $pastDueInfo['MonthsWithoutArrearsLast12Months']['_value'] }}/{{ $pastDueInfo['TotalMonthsWithHistoryLast12Months']['_value'] }}</div>
                                </div>
                            </div>
                        @endif

                        <!-- Repayment Information -->
                        @if($repaymentInfo)
                            <div class="bg-purple-50 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 mb-3">Repayment Information</h4>
                                <div class="space-y-2 text-sm">
                                    <div><span class="font-medium">Total Monthly Payment:</span> TZS {{ number_format($repaymentInfo['TotalMonthlyPayment']['_value']) }}</div>
                                    <div><span class="font-medium">Closed Contracts:</span> {{ $repaymentInfo['ClosedContracts']['_value'] }}</div>
                                </div>
                            </div>
                        @endif

                        <!-- Inquiries Analysis -->
                        @if($inquiriesAnalysis)
                            <div class="bg-yellow-50 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 mb-3">Credit Inquiries</h4>
                                <div class="space-y-2 text-sm">
                                    <div><span class="font-medium">Last 7 Days:</span> {{ $inquiriesAnalysis['TotalLast7Days']['_value'] }}</div>
                                    <div><span class="font-medium">Non-Banking Last Month:</span> {{ $inquiriesAnalysis['NonBankingLast1Month']['_value'] }}</div>
                                    @if(isset($inquiriesAnalysis['Conclusion']) && $inquiriesAnalysis['Conclusion']['_value'])
                                        <div><span class="font-medium">Conclusion:</span> {{ $inquiriesAnalysis['Conclusion']['_value'] }}</div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Strategy Information -->
                        @if($strategy)
                            <div class="bg-indigo-50 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 mb-3">Strategy Applied</h4>
                                <div class="space-y-2 text-sm">
                                    <div><span class="font-medium">Strategy Name:</span> {{ $strategy['Name']['_value'] }}</div>
                                    <div><span class="font-medium">Bee Strategy:</span> {{ $strategy['BeeStrategy']['_value'] }}</div>
                                    <div><span class="font-medium">Template:</span> {{ $strategy['TemplateName']['_value'] }}</div>
                                    <div><span class="font-medium">Strategy ID:</span> {{ Str::limit($strategy['Id']['_value'], 20) }}...</div>
                                </div>
                            </div>
                        @endif

                        <!-- Risk Parameters Summary -->
                        @if($extract)
                            <div class="bg-red-50 rounded-lg p-4 md:col-span-2 lg:col-span-3">
                                <h4 class="font-semibold text-gray-900 mb-3">Risk Assessment Parameters</h4>
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 text-sm">
                                    @if(isset($extract['SCR2Result']))
                                        <div class="bg-white rounded p-2">
                                            <div class="font-medium">SCR2 (Score)</div>
                                            <div>Result: {{ $extract['SCR2Result']['_value'] }}</div>
                                            <div>Parameter: {{ $extract['SCR2Parameter']['_value'] }}</div>
                                            <div>Value: {{ $extract['SCR2Value']['_value'] }}</div>
                                        </div>
                                    @endif
                                    
                                    @if(isset($extract['INQ2Result']))
                                        <div class="bg-white rounded p-2">
                                            <div class="font-medium">INQ2 (Inquiries)</div>
                                            <div>Result: {{ $extract['INQ2Result']['_value'] }}</div>
                                            <div>Parameter: {{ $extract['INQ2Parameter']['_value'] }}</div>
                                            <div>Value: {{ $extract['INQ2Value']['_value'] }}</div>
                                        </div>
                                    @endif
                                    
                                    @if(isset($extract['RSK3Result']))
                                        <div class="bg-white rounded p-2">
                                            <div class="font-medium">RSK3 (Risk)</div>
                                            <div>Result: {{ $extract['RSK3Result']['_value'] }}</div>
                                            <div>Parameter: {{ $extract['RSK3Parameter']['_value'] }}</div>
                                        </div>
                                    @endif
                                    
                                    @if(isset($extract['RSK6Result']))
                                        <div class="bg-white rounded p-2">
                                            <div class="font-medium">RSK6 (Risk)</div>
                                            <div>Result: {{ $extract['RSK6Result']['_value'] }}</div>
                                            <div>Parameter: {{ $extract['RSK6Parameter']['_value'] }}</div>
                                            <div>Value: {{ $extract['RSK6Value']['_value'] }}</div>
                                        </div>
                                    @endif
                                    
                                    @if(isset($extract['CST2Result']))
                                        <div class="bg-white rounded p-2">
                                            <div class="font-medium">CST2 (Cost)</div>
                                            <div>Result: {{ $extract['CST2Result']['_value'] }}</div>
                                            <div>Parameter: {{ number_format($extract['CST2Parameter']['_value']) }}</div>
                                            <div>Value: {{ $extract['CST2Value']['_value'] }}</div>
                                        </div>
                                    @endif
                                    
                                    @if(isset($extract['CST3Result']))
                                        <div class="bg-white rounded p-2">
                                            <div class="font-medium">CST3 (Cost)</div>
                                            <div>Result: {{ $extract['CST3Result']['_value'] }}</div>
                                            <div>Parameter: {{ number_format($extract['CST3Parameter']['_value']) }}</div>
                                            <div>Value: {{ $extract['CST3Value']['_value'] }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endif

                    @if($selectedRequest->error_message)
                        <!-- Error Information -->
                        <div class="bg-red-50 rounded-lg p-4 md:col-span-2 lg:col-span-3">
                            <h4 class="font-semibold text-red-900 mb-3">Error Details</h4>
                            <p class="text-sm text-red-700">{{ $selectedRequest->error_message }}</p>
                        </div>
                    @endif
                </div>

                <!-- Additional Details Section -->
                @if($selectedRequest->isSuccessful() && $extract)
                    <div class="mt-6 bg-gray-50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 mb-3">Technical Details</h4>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 text-sm">
                            <div><span class="font-medium">Message ID:</span> {{ Str::limit($creditData['message_id']['_value'], 20) }}...</div>
                            <div><span class="font-medium">Timestamp:</span> {{ \Carbon\Carbon::parse($creditData['timestamp']['_value'])->format('M d, Y H:i:s') }}</div>
                            @if(isset($extract['APD1DPD']))
                                <div><span class="font-medium">APD1DPD:</span> {{ $extract['APD1DPD']['_value'] }}</div>
                            @endif
                            @if(isset($extract['MobileTotalBalance']))
                                <div><span class="font-medium">Mobile Total Balance:</span> TZS {{ number_format($extract['MobileTotalBalance']['_value']) }}</div>
                            @endif
                        </div>
                    </div>
                @endif

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

                    @if($creditData)
                        <button wire:click="viewJson({{ json_encode($creditData) }}, 'Credit Data Extract')" 
                                class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm">
                            View Credit Data
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
                
                <div class="mt-4 flex justify-end space-x-2">
                    <button onclick="navigator.clipboard.writeText(this.parentElement.previousElementSibling.querySelector('pre').textContent)" 
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm">
                        Copy to Clipboard
                    </button>
                    <button wire:click="closeJsonModal"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm">
                        Close
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
                <p class="text-gray-500 text-sm mt-2">This may take a few moments...</p>
            </div>
        </div>
    @endif
</div>