<div>

<div class="max-w-7xl mx-auto p-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold text-gray-900">Credit Information Requests</h1>
            <button wire:click="toggleRequestForm" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
                {{ $showRequestForm ? 'Cancel' : 'Request New Report' }}
            </button>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-sidebar-green-100 border border-sidebar-green-400 text-sidebar-green-light px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- New Report Request Form -->
        @if($showRequestForm)
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Request New Credit Report</h3>
                <form wire:submit.prevent="requestNewReport">
                    <div class="max-w-md">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Application</label>
                        @php
                            // When in application context, ALWAYS use that application - no exceptions
                            if($applicationId) {
                                $currentApplication = \App\Models\Application::find($applicationId);
                            } else {
                                $currentApplication = !empty($applications) ? $applications->first() : null;
                            }
                        @endphp
                        @if($currentApplication)
                            <div class="w-full border border-gray-300 rounded-md bg-gray-100 p-3 text-gray-700">
                                <strong>{{ $currentApplication->application_number }}</strong> - {{ $currentApplication->first_name }} {{ $currentApplication->last_name }}
                                @if($applicationId)
                                    <div class="text-xs text-gray-500 mt-1">NIDA: {{ $currentApplication->national_id ?? 'Not set' }}</div>
                                @endif
                            </div>
                        @else
                            <div class="w-full border border-sidebar-green-300 rounded-md bg-sidebar-green-50 p-3 text-sidebar-green-light">
                                No application available
                            </div>
                        @endif
                        @error('application_id') <span class="text-sidebar-green text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" 
                                wire:loading.attr="disabled"
                                @if(!$currentApplication) disabled @endif
                                class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                            <span wire:loading.remove>Generate Report</span>
                            <span wire:loading>Processing...</span>
                        </button>
                    </div>
                </form>
            </div>
        @endif

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
                                        {{ $request->application_number ?? 'N/A' }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        ID: {{ isset($extract['NationalId']['_value']) ? $extract['NationalId']['_value'] : $request->national_id }}
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
                                        DOB: {{ $request->date_of_birth ? $request->date_of_birth->format('M d, Y') : 'N/A' }}
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
                                            <span class="text-gray-900 font-semibold">{{ $extract['CIPScore']['_value'] }}</span>
                                            <span class="text-gray-500">({{ $extract['CIPGrade']['_value'] }})</span>
                                        </div>
                                        <div class="text-sm">
                                            <span class="font-medium">Mobile Score:</span> 
                                            <span class="text-gray-900 font-semibold">{{ $extract['MobileScore']['_value'] }}</span>
                                            <span class="text-gray-500">({{ $extract['MobileGrade']['_value'] }})</span>
                                        </div>
                                        <div class="text-sm">
                                            <span class="font-medium">Decision:</span> 
                                            <span class="font-semibold {{ $extract['Decision']['_value'] === 'Approve' ? 'text-green-700' : 'text-sidebar-green-light' }}">
                                                {{ $extract['Decision']['_value'] }}
                                            </span>
                                        </div>
                                        @if($generalInfo && isset($generalInfo['BrokenRules']))
                                            <div class="text-sm">
                                                <span class="font-medium">Broken Rules:</span> 
                                                <span class="{{ $generalInfo['BrokenRules']['_value'] > 0 ? 'text-sidebar-green-light' : 'text-green-700' }} font-semibold">
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
                                            <span class="text-gray-900 font-semibold">
                                                TZS {{ number_format($currentContracts['Total']['Balance']['_value']) }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="font-medium">At Risk:</span> 
                                            <span class="text-sidebar-green-light font-semibold">
                                                TZS {{ number_format($currentContracts['Total']['BalanceAtRisk']['_value']) }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="font-medium">Contracts:</span> 
                                            <span class="text-green-700 font-semibold">{{ $currentContracts['Total']['Positive']['_value'] }}</span> / 
                                            <span class="text-sidebar-green-light font-semibold">{{ $currentContracts['Total']['Negative']['_value'] }}</span>
                                        </div>
                                        @if($pastDueInfo)
                                            <div>
                                                <span class="font-medium">Past Due:</span> 
                                                <span class="text-orange-700 font-semibold">
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
                                    <span class="text-green-700 font-semibold">Success</span>
                                @elseif($request->status === 'failed')
                                    <span class="text-sidebar-green-light font-semibold">Failed</span>
                                @else
                                    <span class="text-yellow-700 font-semibold">Pending</span>
                                @endif
                                
                                @if($request->error_message)
                                    <div class="text-xs text-sidebar-green mt-1">
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
            
            // Get the primary credit score (CIP Score)
            $creditScore = isset($extract['CIPScore']['_value']) ? $extract['CIPScore']['_value'] : 0;
            $creditGrade = isset($extract['CIPGrade']['_value']) ? $extract['CIPGrade']['_value'] : 'N/A';
            $decision = isset($extract['Decision']['_value']) ? $extract['Decision']['_value'] : 'Unknown';
        @endphp
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-6xl shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Credit Information Details</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                @if($selectedRequest->isSuccessful() && $extract)
                    <!-- Improved Credit Score Gauge -->
                    <div class="flex justify-center mb-8">
                        <div class="bg-gradient-to-br from-white to-gray-50  ">
                            
                            
            <!-- Semi-circular Gauge Container -->
                            <div class="relative mx-auto" style="width: 320px; height: 200px;">
                                <svg width="320" height="200" viewBox="0 0 320 200" class="mx-auto">
                                    <!-- Gauge Background -->
                                    <defs>
                                        <linearGradient id="backgroundGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                            <stop offset="0%" style="stop-color:#f3f4f6;stop-opacity:1" />
                                            <stop offset="100%" style="stop-color:#e5e7eb;stop-opacity:1" />
                                        </linearGradient>
                                        
                                        <!-- Shadows for depth -->
                                        <filter id="shadow" x="-50%" y="-50%" width="200%" height="200%">
                                            <feDropShadow dx="2" dy="2" stdDeviation="3" flood-color="#00000020"/>
                                        </filter>
                                    </defs>
                                    
                                    <!-- Background Arc -->
                                    <path d="M 50 170 A 110 110 0 0 1 270 170" 
                                          stroke="url(#backgroundGradient)" 
                                          stroke-width="20" 
                                          fill="none" 
                                          filter="url(#shadow)"/>
                                    
                                    <!-- Score Segments with Proper Color Distribution -->
                                    @php
                                        // Define score ranges and their corresponding angles
                                        $maxScore = 999;
                                        $segments = [
                                            ['min' => 0, 'max' => 299, 'color' => '#1D753F', 'label' => 'Poor'],
                                            ['min' => 300, 'max' => 549, 'color' => '#ea580c', 'label' => 'Fair'], 
                                            ['min' => 550, 'max' => 699, 'color' => '#fbbf24', 'label' => 'Good'],
                                            ['min' => 700, 'max' => 999, 'color' => '#16a34a', 'label' => 'Excellent']
                                        ];
                                        
                                        $totalAngle = 180; // Semi-circle
                                        $radius = 110;
                                        $centerX = 160;
                                        $centerY = 170;
                                    @endphp
                                    
                                    @foreach($segments as $segment)
                                        @php
                                            $startAngle = ($segment['min'] / $maxScore) * $totalAngle;
                                            $endAngle = ($segment['max'] / $maxScore) * $totalAngle;
                                            
                                            // Convert to radians and calculate coordinates
                                            $startRad = ($startAngle - 90) * pi() / 180;
                                            $endRad = ($endAngle - 90) * pi() / 180;
                                            
                                            $startX = $centerX + $radius * cos($startRad);
                                            $startY = $centerY + $radius * sin($startRad);
                                            $endX = $centerX + $radius * cos($endRad);
                                            $endY = $centerY + $radius * sin($endRad);
                                            
                                            $largeArcFlag = ($endAngle - $startAngle) > 90 ? 1 : 0;
                                        @endphp
                                        
                                        <path d="M {{ $startX }} {{ $startY }} A {{ $radius }} {{ $radius }} 0 {{ $largeArcFlag }} 1 {{ $endX }} {{ $endY }}" 
                                              stroke="{{ $segment['color'] }}" 
                                              stroke-width="18" 
                                              fill="none" 
                                              stroke-linecap="round"
                                              opacity="0.9"/>
                                    @endforeach
                                    
                                    <!-- Score Needle -->
                                    @php
                                        $scoreAngle = ($creditScore / $maxScore) * $totalAngle;
                                        $needleRad = ($scoreAngle - 90) * pi() / 180;
                                        $needleLength = 90;
                                        $needleX = $centerX + $needleLength * cos($needleRad);
                                        $needleY = $centerY + $needleLength * sin($needleRad);
                                    @endphp
                                    
                                    <!-- Needle with gradient -->
                                    <defs>
                                        <linearGradient id="needleGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                            <stop offset="0%" style="stop-color:#374151;stop-opacity:1" />
                                            <stop offset="100%" style="stop-color:#1f2937;stop-opacity:1" />
                                        </linearGradient>
                                    </defs>
                                    
                                    <line x1="{{ $centerX }}" y1="{{ $centerY }}" 
                                          x2="{{ $needleX }}" y2="{{ $needleY }}" 
                                          stroke="url(#needleGradient)" 
                                          stroke-width="4" 
                                          stroke-linecap="round"
                                          filter="url(#shadow)"/>
                                    
                                    <!-- Center dot -->
                                    <circle cx="{{ $centerX }}" cy="{{ $centerY }}" r="8" 
                                            fill="url(#needleGradient)" 
                                            filter="url(#shadow)"/>
                                    
                                    <!-- Score Labels -->
                                    <text x="60" y="185" text-anchor="middle" fill="#6b7280" font-size="11" font-weight="500">Poor</text>
                                    <text x="120" y="110" text-anchor="middle" fill="#6b7280" font-size="11" font-weight="500">Fair</text>
                                    <text x="200" y="110" text-anchor="middle" fill="#6b7280" font-size="11" font-weight="500">Good</text>
                                    <text x="260" y="185" text-anchor="middle" fill="#6b7280" font-size="11" font-weight="500">Excellent</text>
                                    
                                    <!-- Score Range Labels -->
                                    <text x="60" y="195" text-anchor="middle" fill="#9ca3af" font-size="9">0-299</text>
                                    <text x="120" y="100" text-anchor="middle" fill="#9ca3af" font-size="9">300-549</text>
                                    <text x="200" y="100" text-anchor="middle" fill="#9ca3af" font-size="9">550-699</text>
                                    <text x="260" y="195" text-anchor="middle" fill="#9ca3af" font-size="9">700-999</text>
                                </svg>
                            </div>
                            
                            <!-- Score Display -->
                            <div class="text-center mt-4">
                                <div class="text-5xl font-bold text-gray-900 mb-2">{{ $creditScore }}</div>
                                <div class="text-lg font-medium text-gray-600 mb-1">Grade: {{ $creditGrade }}</div>
                                <div class="text-sm px-4 py-2 rounded-full inline-block
                                    @if($decision === 'Approve') bg-green-100 text-green-800 @else bg-sidebar-green-100 text-sidebar-green-800 @endif">
                                
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Information Rows -->
                <div class="space-y-6">
                    <!-- Basic Information Row -->
                    <div class="border border-gray-200 rounded-lg">
                        <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                            <h4 class="font-bold text-gray-900">Basic Information</h4>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Name</span>
                                    <div class="text-sm text-gray-900">{{ $selectedRequest->full_name }}</div>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">National ID</span>
                                    <div class="text-sm text-gray-900">{{ isset($extract['NationalId']['_value']) ? $extract['NationalId']['_value'] : $selectedRequest->national_id }}</div>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Date of Birth</span>
                                    <div class="text-sm text-gray-900">{{ $selectedRequest->date_of_birth ? $selectedRequest->date_of_birth->format('M d, Y') : 'N/A' }}</div>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Status</span>
                                    <div class="text-sm font-semibold {{ $selectedRequest->status === 'success' ? 'text-green-700' : ($selectedRequest->status === 'failed' ? 'text-sidebar-green-light' : 'text-yellow-700') }}">
                                        {{ ucfirst($selectedRequest->status) }}
                                    </div>
                                </div>
                                @if($extract && isset($extract['MobilePhone']))
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Mobile Phone</span>
                                        <div class="text-sm text-gray-900">{{ $extract['MobilePhone']['_value'] }}</div>
                                    </div>
                                @endif
                                @if($selectedRequest->application)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Application</span>
                                        <div class="text-sm text-gray-900">{{ $selectedRequest->application->application_number }}</div>
                                    </div>
                                @endif
                                @if($generalInfo && isset($generalInfo['ReferenceNumber']))
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Reference</span>
                                        <div class="text-sm text-gray-900">{{ $generalInfo['ReferenceNumber']['_value'] ?: 'N/A' }}</div>
                                    </div>
                                @endif
                                @if($generalInfo)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Request Date</span>
                                        <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($generalInfo['RequestDate']['_value'])->format('M d, Y H:i') }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($selectedRequest->isSuccessful() && $extract)
                        <!-- Credit Analysis Row -->
                        <div class="border border-gray-200 rounded-lg">
                            <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                                <h4 class="font-bold text-gray-900">Credit Analysis</h4>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">CIP Score</span>
                                        <div class="text-sm text-gray-900 font-semibold">{{ $extract['CIPScore']['_value'] }} ({{ $extract['CIPGrade']['_value'] }})</div>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Mobile Score</span>
                                        <div class="text-sm text-gray-900 font-semibold">{{ $extract['MobileScore']['_value'] }} ({{ $extract['MobileGrade']['_value'] }})</div>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Final Decision</span>
                                        <div class="text-sm font-bold {{ $decision === 'Approve' ? 'text-green-700' : 'text-sidebar-green-light' }}">{{ $decision }}</div>
                                    </div>
                                    @if($generalInfo)
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Recommended Decision</span>
                                            <div class="text-sm font-bold {{ $generalInfo['RecommendedDecision']['_value'] === 'Approve' ? 'text-green-700' : 'text-sidebar-green-light' }}">{{ $generalInfo['RecommendedDecision']['_value'] }}</div>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Broken Rules</span>
                                            <div class="text-sm font-bold {{ $generalInfo['BrokenRules']['_value'] > 0 ? 'text-sidebar-green-light' : 'text-green-700' }}">{{ $generalInfo['BrokenRules']['_value'] }}</div>
                                        </div>
                                    @endif
                                    @if($creditData)
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Hit Count</span>
                                            <div class="text-sm text-gray-900">{{ $creditData['hit_count'] }}</div>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Currency</span>
                                            <div class="text-sm text-gray-900">{{ $creditData['currency']['_value'] }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Current Contracts Row -->
                        @if($currentContracts)
                            <div class="border border-gray-200 rounded-lg">
                                <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                                    <h4 class="font-bold text-gray-900">Current Contracts</h4>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Total Balance</span>
                                            <div class="text-sm text-gray-900 font-semibold">TZS {{ number_format($currentContracts['Total']['Balance']['_value']) }}</div>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Balance At Risk</span>
                                            <div class="text-sm text-sidebar-green-light font-semibold">TZS {{ number_format($currentContracts['Total']['BalanceAtRisk']['_value']) }}</div>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Positive Contracts</span>
                                            <div class="text-sm text-green-700 font-semibold">{{ $currentContracts['Total']['Positive']['_value'] }}</div>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Negative Contracts</span>
                                            <div class="text-sm text-sidebar-green-light font-semibold">{{ $currentContracts['Total']['Negative']['_value'] }}</div>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Banking Balance</span>
                                            <div class="text-sm text-gray-900">TZS {{ number_format($currentContracts['CurrentBanking']['Balance']['_value']) }}</div>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Banking At Risk</span>
                                            <div class="text-sm text-gray-900">TZS {{ number_format($currentContracts['CurrentBanking']['BalanceAtRisk']['_value']) }}</div>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Non-Banking Balance</span>
                                            <div class="text-sm text-gray-900">TZS {{ number_format($currentContracts['CurrentNonBanking']['Balance']['_value']) }}</div>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Non-Banking At Risk</span>
                                            <div class="text-sm text-gray-900">TZS {{ number_format($currentContracts['CurrentNonBanking']['BalanceAtRisk']['_value']) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Past Due Information Row -->
                        @if($pastDueInfo)
                            <div class="border border-gray-200 rounded-lg">
                                <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                                    <h4 class="font-bold text-gray-900">Past Due Analysis</h4>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Current Past Due</span>
                                            <div class="text-sm text-sidebar-green-light font-semibold">TZS {{ number_format($pastDueInfo['TotalCurrentPastDue']['_value']) }}</div>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Current Days Past Due</span>
                                            <div class="text-sm text-gray-900">{{ $pastDueInfo['TotalCurrentDaysPastDue']['_value'] }} days</div>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Worst Current Past Due</span>
                                            <div class="text-sm text-sidebar-green-light font-semibold">TZS {{ number_format($pastDueInfo['WorstCurrentPastDue']['_value']) }}</div>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Worst Current Days</span>
                                            <div class="text-sm text-gray-900">{{ $pastDueInfo['WorstCurrentDaysPastDue']['_value'] }} days</div>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Worst Last 12M</span>
                                            <div class="text-sm text-gray-900">TZS {{ number_format($pastDueInfo['WorstPastDueLast12Months']['_value']) }}</div>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Worst Days Last 12M</span>
                                            <div class="text-sm text-gray-900">{{ $pastDueInfo['WorstPastDueDaysLast12Months']['_value'] }} days</div>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Months w/o Arrears</span>
                                            <div class="text-sm text-gray-900">{{ $pastDueInfo['MonthsWithoutArrearsLast12Months']['_value'] }}/{{ $pastDueInfo['TotalMonthsWithHistoryLast12Months']['_value'] }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Repayment Information Row -->
                        @if($repaymentInfo)
                            <div class="border border-gray-200 rounded-lg">
                                <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                                    <h4 class="font-bold text-gray-900">Repayment Information</h4>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Total Monthly Payment</span>
                                            <div class="text-sm text-gray-900 font-semibold">TZS {{ number_format($repaymentInfo['TotalMonthlyPayment']['_value']) }}</div>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Closed Contracts</span>
                                            <div class="text-sm text-gray-900">{{ $repaymentInfo['ClosedContracts']['_value'] }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Inquiries Analysis Row -->
                        @if($inquiriesAnalysis)
                            <div class="border border-gray-200 rounded-lg">
                                <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                                    <h4 class="font-bold text-gray-900">Credit Inquiries</h4>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Last 7 Days</span>
                                            <div class="text-sm text-gray-900">{{ $inquiriesAnalysis['TotalLast7Days']['_value'] }}</div>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Non-Banking Last Month</span>
                                            <div class="text-sm text-gray-900">{{ $inquiriesAnalysis['NonBankingLast1Month']['_value'] }}</div>
                                        </div>
                                        @if(isset($inquiriesAnalysis['Conclusion']) && $inquiriesAnalysis['Conclusion']['_value'])
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">Conclusion</span>
                                                <div class="text-sm text-gray-900">{{ $inquiriesAnalysis['Conclusion']['_value'] }}</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Strategy Information Row -->
                        @if($strategy)
                            <div class="border border-gray-200 rounded-lg">
                                <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                                    <h4 class="font-bold text-gray-900">Strategy Applied</h4>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Strategy Name</span>
                                            <div class="text-sm text-gray-900">{{ $strategy['Name']['_value'] }}</div>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Bee Strategy</span>
                                            <div class="text-sm text-gray-900">{{ $strategy['BeeStrategy']['_value'] }}</div>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Template</span>
                                            <div class="text-sm text-gray-900">{{ $strategy['TemplateName']['_value'] }}</div>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Strategy ID</span>
                                            <div class="text-sm text-gray-900">{{ Str::limit($strategy['Id']['_value'], 20) }}...</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Risk Assessment Parameters Row -->
                        @if($extract)
                            <div class="border border-gray-200 rounded-lg">
                                <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                                    <h4 class="font-bold text-gray-900">Risk Assessment Parameters</h4>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                                        @if(isset($extract['SCR2Result']))
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">SCR2 (Score)</span>
                                                <div class="text-sm text-gray-900">
                                                    <div>Result: {{ $extract['SCR2Result']['_value'] }}</div>
                                                    <div>Parameter: {{ $extract['SCR2Parameter']['_value'] }}</div>
                                                    <div>Value: {{ $extract['SCR2Value']['_value'] }}</div>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @if(isset($extract['INQ2Result']))
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">INQ2 (Inquiries)</span>
                                                <div class="text-sm text-gray-900">
                                                    <div>Result: {{ $extract['INQ2Result']['_value'] }}</div>
                                                    <div>Parameter: {{ $extract['INQ2Parameter']['_value'] }}</div>
                                                    <div>Value: {{ $extract['INQ2Value']['_value'] }}</div>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @if(isset($extract['RSK3Result']))
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">RSK3 (Risk)</span>
                                                <div class="text-sm text-gray-900">
                                                    <div>Result: {{ $extract['RSK3Result']['_value'] }}</div>
                                                    <div>Parameter: {{ $extract['RSK3Parameter']['_value'] }}</div>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @if(isset($extract['RSK6Result']))
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">RSK6 (Risk)</span>
                                                <div class="text-sm text-gray-900">
                                                    <div>Result: {{ $extract['RSK6Result']['_value'] }}</div>
                                                    <div>Parameter: {{ $extract['RSK6Parameter']['_value'] }}</div>
                                                    <div>Value: {{ $extract['RSK6Value']['_value'] }}</div>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @if(isset($extract['CST2Result']))
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">CST2 (Cost)</span>
                                                <div class="text-sm text-gray-900">
                                                    <div>Result: {{ $extract['CST2Result']['_value'] }}</div>
                                                    <div>Parameter: {{ number_format($extract['CST2Parameter']['_value']) }}</div>
                                                    <div>Value: {{ $extract['CST2Value']['_value'] }}</div>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @if(isset($extract['CST3Result']))
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">CST3 (Cost)</span>
                                                <div class="text-sm text-gray-900">
                                                    <div>Result: {{ $extract['CST3Result']['_value'] }}</div>
                                                    <div>Parameter: {{ number_format($extract['CST3Parameter']['_value']) }}</div>
                                                    <div>Value: {{ $extract['CST3Value']['_value'] }}</div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Technical Details Row -->
                      
                    @endif

                    @if($selectedRequest->error_message)
                        <!-- Error Information Row -->
                        <div class="border border-sidebar-green-200 rounded-lg">
                            <div class="bg-sidebar-green-50 px-6 py-3 border-b border-sidebar-green-200">
                                <h4 class="font-bold text-sidebar-green-900">Error Details</h4>
                            </div>
                            <div class="p-6">
                                <p class="text-sm text-sidebar-green-light">{{ $selectedRequest->error_message }}</p>
                            </div>
                        </div>
                    @endif
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

<script>
    function copyJsonToClipboard(button) {
        try {
            const preElement = button.closest('.relative').querySelector('pre');
            if (preElement) {
                const textToCopy = preElement.textContent || preElement.innerText;
                
                // Modern browsers
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(textToCopy).then(function() {
                        showCopyFeedback(button);
                    }).catch(function(err) {
                        console.error('Could not copy text: ', err);
                        fallbackCopyTextToClipboard(textToCopy, button);
                    });
                } else {
                    // Fallback for older browsers
                    fallbackCopyTextToClipboard(textToCopy, button);
                }
            }
        } catch (err) {
            console.error('Copy failed: ', err);
        }
    }

    function fallbackCopyTextToClipboard(text, button) {
        const textArea = document.createElement("textarea");
        textArea.value = text;
        textArea.style.position = "fixed";
        textArea.style.top = "-9999px";
        textArea.style.left = "-9999px";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
            const successful = document.execCommand('copy');
            if (successful) {
                showCopyFeedback(button);
            }
        } catch (err) {
            console.error('Fallback copy failed: ', err);
        }
        
        document.body.removeChild(textArea);
    }

    function showCopyFeedback(button) {
        const originalText = button.textContent;
        button.textContent = 'Copied!';
        button.classList.add('bg-green-600');
        button.classList.remove('bg-gray-600');
        
        setTimeout(() => {
            button.textContent = originalText;
            button.classList.remove('bg-green-600');
            button.classList.add('bg-gray-600');
        }, 2000);
    }

    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Any additional initialization code can go here
        console.log('Credit Info Component loaded successfully');
    });

    // Livewire hook for after updates
    document.addEventListener('livewire:load', function () {
        // Component loaded
    });

    document.addEventListener('livewire:update', function () {
        // Component updated
    });
</script>


</div>