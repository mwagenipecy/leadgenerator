<div wire:poll.10s="refreshList">

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
                            if($applicationId) {
                                $currentApplication = \App\Models\Application::find($applicationId);
                            } else {
                                $currentApplication = null;
                            }
                        @endphp
                        @if($currentApplication)
                            <div class="w-full border border-gray-300 rounded-md bg-gray-100 p-3 text-gray-700">
                                <strong>{{ $currentApplication->application_number }}</strong> - {{ $currentApplication->first_name }} {{ $currentApplication->last_name }}
                                <div class="text-xs text-gray-500 mt-1">NIDA: {{ $currentApplication->national_id ?? 'Not set' }}</div>
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
                            Credit Score & Grade
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Summary
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
                            $responseData = $request->response_payload ?? [];
                            $creditData = $responseData['credit_data'] ?? null;
                            $extract = $creditData['extract'] ?? null;
                            $currentContracts = $creditData['current_contracts'] ?? null;
                            $pastDueInfo = $creditData['past_due_information'] ?? null;
                            $repaymentInfo = $creditData['repayment_information'] ?? null;
                            $inquiriesAnalysis = $creditData['inquiries_analysis'] ?? null;
                            $scoringAnalysis = $creditData['scoring_analysis'] ?? null;
                            $generalInfo = $creditData['general_information'] ?? null;
                            $strategy = $creditData['strategy'] ?? null;
                            $statusMeta = [
                                'status' => $creditData['status'] ?? null,
                                'hitcount' => $creditData['hitcount'] ?? null,
                                'infomsg' => $creditData['infomsg'] ?? null,
                                'currency' => $creditData['currency'] ?? null,
                            ];

                            // Derive credit score & grade with fallbacks
                            $creditScore = null;
                            $creditGrade = 'N/A';

                            if ($extract && (isset($extract['CIPScore']['_value']) || isset($extract['CIPScore']))) {
                                $creditScore = isset($extract['CIPScore']['_value'])
                                    ? (int) $extract['CIPScore']['_value']
                                    : (int) $extract['CIPScore'];
                                $creditGrade = isset($extract['CIPGrade']['_value'])
                                    ? $extract['CIPGrade']['_value']
                                    : ($extract['CIPGrade'] ?? 'N/A');
                            } elseif ($scoringAnalysis && (isset($scoringAnalysis['CIPScore']['_value']) || isset($scoringAnalysis['CIPScore']))) {
                                $creditScore = isset($scoringAnalysis['CIPScore']['_value'])
                                    ? (int) $scoringAnalysis['CIPScore']['_value']
                                    : (int) $scoringAnalysis['CIPScore'];
                                $creditGrade = isset($scoringAnalysis['CIPRiskGrade']['_value'])
                                    ? $scoringAnalysis['CIPRiskGrade']['_value']
                                    : ($scoringAnalysis['CIPRiskGrade'] ?? 'N/A');
                            } elseif (isset($responseData['cip_score'])) {
                                $creditScore = (int) $responseData['cip_score'];
                                $creditGrade = $responseData['rating'] ?? 'N/A';
                            }
                        @endphp
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($request->application)
                                    <div class="font-medium text-gray-900">
                                        {{ $request->application->application_number }}
                                    </div>
                                    <div class="text-gray-500">
                                        {{ $request->application->first_name }} {{ $request->application->last_name }}
                                    </div>
                                    <div class="text-xs text-gray-400 mt-1">
                                        NIDA: {{ $request->application->national_id ?? 'Not set' }}
                                    </div>
                                @else
                                    <span class="text-gray-400">Application not found</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($creditScore !== null)
                                    <div class="flex items-center space-x-3">
                                        <div>
                                            <div class="text-lg font-bold 
                                                @if($creditScore >= 750) text-green-600
                                                @elseif($creditScore >= 650) text-green-500
                                                @elseif($creditScore >= 550) text-yellow-500
                                                @else text-red-500
                                                @endif">
                                                {{ $creditScore }}
                                            </div>
                                            <div class="text-xs text-gray-500 uppercase tracking-wide">CIP Score</div>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-800">
                                                {{ $creditGrade }}
                                            </div>
                                            <div class="text-xs text-gray-500 uppercase tracking-wide">Grade</div>
                                        </div>
                                    </div>
                                @elseif(isset($responseData['success']) && $responseData['success'] === true)
                                    <span class="text-xs text-gray-500">Score pending in details</span>
                                @else
                                    <span class="text-gray-400 text-sm">N/A</span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($request->isSuccessful())
                                    <div class="space-y-1 text-sm">
                                        @if($creditScore !== null)
                                            <div>
                                                <span class="font-medium">Score / Grade:</span>
                                                <span class="font-semibold">
                                                    {{ $creditScore }} ({{ $creditGrade }})
                                                </span>
                                            </div>
                                        @endif

                                        @if($generalInfo && isset($generalInfo['RecommendedDecision']))
                                            <div>
                                                <span class="font-medium">Decision:</span>
                                                <span class="font-semibold {{ (($generalInfo['RecommendedDecision']['_value'] ?? $generalInfo['RecommendedDecision']) === 'Approve') ? 'text-green-700' : 'text-sidebar-green-light' }}">
                                                    {{ $generalInfo['RecommendedDecision']['_value'] ?? $generalInfo['RecommendedDecision'] }}
                                                </span>
                                            </div>
                                        @elseif($extract && isset($extract['Decision']))
                                            <div>
                                                <span class="font-medium">Decision:</span>
                                                <span class="font-semibold {{ (($extract['Decision']['_value'] ?? $extract['Decision']) === 'Approve') ? 'text-green-700' : 'text-sidebar-green-light' }}">
                                                    {{ $extract['Decision']['_value'] ?? $extract['Decision'] }}
                                                </span>
                                            </div>
                                        @endif

                                        @if($statusMeta['hitcount'] !== null)
                                            <div>
                                                <span class="font-medium">Hits:</span>
                                                <span class="text-gray-900">{{ $statusMeta['hitcount'] }}</span>
                                            </div>
                                        @endif

                                        @if($currentContracts && isset($currentContracts['Total']['Balance']))
                                            <div>
                                                <span class="font-medium">Total Balance:</span>
                                                <span class="text-gray-900 font-semibold">
                                                    TZS {{ number_format($currentContracts['Total']['Balance']['_value'] ?? $currentContracts['Total']['Balance']) }}
                                                </span>
                                            </div>
                                        @endif

                                        @if($pastDueInfo && isset($pastDueInfo['TotalCurrentPastDue']))
                                            <div>
                                                <span class="font-medium">Past Due:</span>
                                                <span class="text-orange-700 font-semibold">
                                                    TZS {{ number_format($pastDueInfo['TotalCurrentPastDue']['_value'] ?? $pastDueInfo['TotalCurrentPastDue']) }}
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
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Completed
                                    </span>
                                @elseif($request->status === 'failed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Failed
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                        <svg class="w-3.5 h-3.5 mr-1 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v2m0 12v2m8-10h-2M6 12H4m13.657 6.657l-1.414-1.414M7.757 7.757L6.343 6.343m0 11.314l1.414-1.414M16.243 7.757l1.414-1.414"/>
                                        </svg>
                                        Pending
                                    </span>
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
        @php
            // Helper function to safely get nested array values
            function getValue($data, $key, $default = null) {
                if (is_array($data)) {
                    if (isset($data[$key . '_value'])) {
                        return $data[$key . '_value'];
                    }
                    if (isset($data[$key])) {
                        return is_array($data[$key]) && isset($data[$key]['_value']) ? $data[$key]['_value'] : $data[$key];
                    }
                }
                return $default;
            }
            
            $responseData = $selectedRequest->response_payload ?? [];
            $creditData = $responseData['credit_data'] ?? null;
            
            // Extract all sections
            $status = $creditData['status'] ?? null;
            $hitcount = $creditData['hitcount'] ?? null;
            $infomsg = $creditData['infomsg'] ?? null;
            $currency = $creditData['currency'] ?? null;
            $extract = $creditData['extract'] ?? null;
            $currentContracts = $creditData['current_contracts'] ?? null;
            $pastDueInfo = $creditData['past_due_information'] ?? null;
            $repaymentInfo = $creditData['repayment_information'] ?? null;
            $inquiriesAnalysis = $creditData['inquiries_analysis'] ?? null;
            $scoringAnalysis = $creditData['scoring_analysis'] ?? null;
            $generalInfo = $creditData['general_information'] ?? null;
            $personalInfo = $creditData['personal_information'] ?? null;
            $policyRules = $creditData['policy_rules'] ?? null;
            $tzaCb5Data = $creditData['tza_cb5_data'] ?? null;
            $strategy = $creditData['strategy'] ?? null;
            
            // Extract TzaCb5_data subsections
            $cipRecords = $tzaCb5Data['CIP']['RecordList']['Record'] ?? null;
            $ciqData = $tzaCb5Data['CIQ'] ?? null;
            $contractOverview = $tzaCb5Data['ContractOverview'] ?? null;
            $contractSummary = $tzaCb5Data['ContractSummary'] ?? null;
            $contracts = $tzaCb5Data['Contracts'] ?? null;
            $dashboard = $tzaCb5Data['Dashboard'] ?? null;
            $disputes = $tzaCb5Data['Disputes'] ?? null;
            $individual = $tzaCb5Data['Individual'] ?? null;
            $inquiries = $tzaCb5Data['Inquiries'] ?? null;
            $parameters = $tzaCb5Data['Parameters'] ?? null;
            $paymentIncidents = $tzaCb5Data['PaymentIncidentList'] ?? null;
            $reportInfo = $tzaCb5Data['ReportInfo'] ?? null;
            $subjectInfoHistory = $tzaCb5Data['SubjectInfoHistory'] ?? null;
            $bouncedCheques = $tzaCb5Data['BouncedCheques'] ?? null;
            $currentRelations = $tzaCb5Data['CurrentRelations'] ?? null;
            
            // Try to get score from extract first, then scoring_analysis, then fallback to response_payload
            $creditScore = 0;
            $creditGrade = 'N/A';
            $decision = 'Unknown';
            
            if ($extract) {
                $creditScore = getValue($extract, 'CIPScore', 0);
                $creditGrade = getValue($extract, 'CIPGrade', 'N/A');
                $decision = getValue($extract, 'Decision', 'Unknown');
            } elseif ($scoringAnalysis) {
                $creditScore = getValue($scoringAnalysis, 'CIPScore', 0);
                $creditGrade = getValue($scoringAnalysis, 'CIPRiskGrade', 'N/A');
            } elseif (isset($responseData['cip_score'])) {
                $creditScore = (int)$responseData['cip_score'];
                $creditGrade = $responseData['rating'] ?? 'N/A';
            }
            
            // Check if we have any data to display
            $hasData = $selectedRequest->isSuccessful() && ($creditData || isset($responseData['cip_score']));
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
                
                @if($hasData && $creditScore > 0)
                    <!-- Improved Credit Score Gauge -->
                    <div class="flex justify-center mb-8">
                        <div class="bg-gradient-to-br from-white to-gray-50">
                            <!-- Semi-circular Gauge Container -->
                            <div class="relative mx-auto" style="width: 320px; height: 200px;">
                                <svg width="320" height="200" viewBox="0 0 320 200" class="mx-auto">
                                    <!-- Gauge Background -->
                                    <defs>
                                        <linearGradient id="backgroundGradient{{ $selectedRequest->id }}" x1="0%" y1="0%" x2="100%" y2="0%">
                                            <stop offset="0%" style="stop-color:#f3f4f6;stop-opacity:1" />
                                            <stop offset="100%" style="stop-color:#e5e7eb;stop-opacity:1" />
                                        </linearGradient>
                                        
                                        <!-- Shadows for depth -->
                                        <filter id="shadow{{ $selectedRequest->id }}" x="-50%" y="-50%" width="200%" height="200%">
                                            <feDropShadow dx="2" dy="2" stdDeviation="3" flood-color="#00000020"/>
                                        </filter>
                                    </defs>
                                    
                                    <!-- Background Arc -->
                                    <path d="M 50 170 A 110 110 0 0 1 270 170" 
                                          stroke="url(#backgroundGradient{{ $selectedRequest->id }})" 
                                          stroke-width="20" 
                                          fill="none" 
                                          filter="url(#shadow{{ $selectedRequest->id }})"/>
                                    
                                    <!-- Score Segments -->
                                    @php
                                        $maxScore = 999;
                                        $segments = [
                                            ['min' => 0, 'max' => 299, 'color' => '#1D753F', 'label' => 'Poor'],
                                            ['min' => 300, 'max' => 549, 'color' => '#ea580c', 'label' => 'Fair'], 
                                            ['min' => 550, 'max' => 699, 'color' => '#fbbf24', 'label' => 'Good'],
                                            ['min' => 700, 'max' => 999, 'color' => '#16a34a', 'label' => 'Excellent']
                                        ];
                                        
                                        $totalAngle = 180;
                                        $radius = 110;
                                        $centerX = 160;
                                        $centerY = 170;
                                    @endphp
                                    
                                    @foreach($segments as $segment)
                                        @php
                                            $startAngle = ($segment['min'] / $maxScore) * $totalAngle;
                                            $endAngle = ($segment['max'] / $maxScore) * $totalAngle;
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
                                        <linearGradient id="needleGradient{{ $selectedRequest->id }}" x1="0%" y1="0%" x2="100%" y2="0%">
                                            <stop offset="0%" style="stop-color:#374151;stop-opacity:1" />
                                            <stop offset="100%" style="stop-color:#1f2937;stop-opacity:1" />
                                        </linearGradient>
                                    </defs>
                                    
                                    <line x1="{{ $centerX }}" y1="{{ $centerY }}" 
                                          x2="{{ $needleX }}" y2="{{ $needleY }}" 
                                          stroke="url(#needleGradient{{ $selectedRequest->id }})" 
                                          stroke-width="4" 
                                          stroke-linecap="round"
                                          filter="url(#shadow{{ $selectedRequest->id }})"/>
                                    
                                    <!-- Center dot -->
                                    <circle cx="{{ $centerX }}" cy="{{ $centerY }}" r="8" 
                                            fill="url(#needleGradient{{ $selectedRequest->id }})" 
                                            filter="url(#shadow{{ $selectedRequest->id }})"/>
                                    
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
                                @if($decision !== 'Unknown')
                                    <div class="text-sm px-4 py-2 rounded-full inline-block
                                        @if($decision === 'Approve') bg-green-100 text-green-800 @else bg-sidebar-green-100 text-sidebar-green-800 @endif">
                                        {{ $decision }}
                                    </div>
                                @endif
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
                                    <div class="text-sm text-gray-900">
                                        @if($extract && isset($extract['NationalId']['_value']))
                                            {{ $extract['NationalId']['_value'] }}
                                        @elseif($extract && isset($extract['NationalId']))
                                            {{ $extract['NationalId'] }}
                                        @else
                                            {{ $selectedRequest->national_id }}
                                        @endif
                                    </div>
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
                                        <div class="text-sm text-gray-900">
                                            {{ isset($extract['MobilePhone']['_value']) ? $extract['MobilePhone']['_value'] : $extract['MobilePhone'] }}
                                        </div>
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
                                        <div class="text-sm text-gray-900">
                                            {{ isset($generalInfo['ReferenceNumber']['_value']) ? ($generalInfo['ReferenceNumber']['_value'] ?: 'N/A') : ($generalInfo['ReferenceNumber'] ?: 'N/A') }}
                                        </div>
                                    </div>
                                @endif
                                @if($generalInfo && isset($generalInfo['RequestDate']))
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Request Date</span>
                                        <div class="text-sm text-gray-900">
                                            @php
                                                $requestDate = isset($generalInfo['RequestDate']['_value']) ? $generalInfo['RequestDate']['_value'] : $generalInfo['RequestDate'];
                                            @endphp
                                            {{ \Carbon\Carbon::parse($requestDate)->format('M d, Y H:i') }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($hasData)
                        <!-- Credit Analysis Row -->
                        <div class="border border-gray-200 rounded-lg">
                            <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                                <h4 class="font-bold text-gray-900">Credit Analysis</h4>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                    @if($extract && isset($extract['CIPScore']))
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">CIP Score</span>
                                            <div class="text-sm text-gray-900 font-semibold">
                                                {{ isset($extract['CIPScore']['_value']) ? $extract['CIPScore']['_value'] : $extract['CIPScore'] }} 
                                                ({{ isset($extract['CIPGrade']['_value']) ? $extract['CIPGrade']['_value'] : (isset($extract['CIPGrade']) ? $extract['CIPGrade'] : 'N/A') }})
                                            </div>
                                        </div>
                                    @elseif($scoringAnalysis && isset($scoringAnalysis['CIPScore']))
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">CIP Score</span>
                                            <div class="text-sm text-gray-900 font-semibold">
                                                {{ isset($scoringAnalysis['CIPScore']['_value']) ? $scoringAnalysis['CIPScore']['_value'] : $scoringAnalysis['CIPScore'] }} 
                                                ({{ isset($scoringAnalysis['CIPRiskGrade']['_value']) ? $scoringAnalysis['CIPRiskGrade']['_value'] : (isset($scoringAnalysis['CIPRiskGrade']) ? $scoringAnalysis['CIPRiskGrade'] : 'N/A') }})
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if($extract && isset($extract['MobileScore']))
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Mobile Score</span>
                                            <div class="text-sm text-gray-900 font-semibold">
                                                {{ isset($extract['MobileScore']['_value']) ? $extract['MobileScore']['_value'] : $extract['MobileScore'] }} 
                                                ({{ isset($extract['MobileGrade']['_value']) ? $extract['MobileGrade']['_value'] : (isset($extract['MobileGrade']) ? $extract['MobileGrade'] : 'N/A') }})
                                            </div>
                                        </div>
                                    @elseif($scoringAnalysis && isset($scoringAnalysis['MobileScore']))
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Mobile Score</span>
                                            <div class="text-sm text-gray-900 font-semibold">
                                                {{ isset($scoringAnalysis['MobileScore']['_value']) ? $scoringAnalysis['MobileScore']['_value'] : $scoringAnalysis['MobileScore'] }} 
                                                ({{ isset($scoringAnalysis['MobileScoreRiskGrade']['_value']) ? $scoringAnalysis['MobileScoreRiskGrade']['_value'] : (isset($scoringAnalysis['MobileScoreRiskGrade']) ? $scoringAnalysis['MobileScoreRiskGrade'] : 'N/A') }})
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if($extract && isset($extract['Decision']))
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Final Decision</span>
                                            <div class="text-sm font-bold {{ ($decision === 'Approve') ? 'text-green-700' : 'text-sidebar-green-light' }}">
                                                {{ $decision }}
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if($generalInfo && isset($generalInfo['RecommendedDecision']))
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Recommended Decision</span>
                                            <div class="text-sm font-bold {{ (isset($generalInfo['RecommendedDecision']['_value']) ? $generalInfo['RecommendedDecision']['_value'] : $generalInfo['RecommendedDecision']) === 'Approve' ? 'text-green-700' : 'text-sidebar-green-light' }}">
                                                {{ isset($generalInfo['RecommendedDecision']['_value']) ? $generalInfo['RecommendedDecision']['_value'] : $generalInfo['RecommendedDecision'] }}
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if($generalInfo && isset($generalInfo['BrokenRules']))
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Broken Rules</span>
                                            <div class="text-sm font-bold {{ (isset($generalInfo['BrokenRules']['_value']) ? (int)$generalInfo['BrokenRules']['_value'] : (int)$generalInfo['BrokenRules']) > 0 ? 'text-sidebar-green-light' : 'text-green-700' }}">
                                                {{ isset($generalInfo['BrokenRules']['_value']) ? $generalInfo['BrokenRules']['_value'] : $generalInfo['BrokenRules'] }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Current Contracts Row -->
                        @if($currentContracts && isset($currentContracts['Total']))
                            <div class="border border-gray-200 rounded-lg">
                                <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                                    <h4 class="font-bold text-gray-900">Current Contracts</h4>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                        @if(isset($currentContracts['Total']['Balance']))
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">Total Balance</span>
                                                <div class="text-sm text-gray-900 font-semibold">
                                                    TZS {{ number_format(isset($currentContracts['Total']['Balance']['_value']) ? $currentContracts['Total']['Balance']['_value'] : $currentContracts['Total']['Balance']) }}
                                                </div>
                                            </div>
                                        @endif
                                        @if(isset($currentContracts['Total']['BalanceAtRisk']))
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">Balance At Risk</span>
                                                <div class="text-sm text-sidebar-green-light font-semibold">
                                                    TZS {{ number_format(isset($currentContracts['Total']['BalanceAtRisk']['_value']) ? $currentContracts['Total']['BalanceAtRisk']['_value'] : $currentContracts['Total']['BalanceAtRisk']) }}
                                                </div>
                                            </div>
                                        @endif
                                        @if(isset($currentContracts['Total']['Positive']))
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">Positive Contracts</span>
                                                <div class="text-sm text-green-700 font-semibold">
                                                    {{ isset($currentContracts['Total']['Positive']['_value']) ? $currentContracts['Total']['Positive']['_value'] : $currentContracts['Total']['Positive'] }}
                                                </div>
                                            </div>
                                        @endif
                                        @if(isset($currentContracts['Total']['Negative']))
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">Negative Contracts</span>
                                                <div class="text-sm text-sidebar-green-light font-semibold">
                                                    {{ isset($currentContracts['Total']['Negative']['_value']) ? $currentContracts['Total']['Negative']['_value'] : $currentContracts['Total']['Negative'] }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Past Due Information Row -->
                        @if($pastDueInfo && isset($pastDueInfo['TotalCurrentPastDue']))
                            <div class="border border-gray-200 rounded-lg">
                                <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                                    <h4 class="font-bold text-gray-900">Past Due Analysis</h4>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Current Past Due</span>
                                            <div class="text-sm text-sidebar-green-light font-semibold">
                                                TZS {{ number_format(isset($pastDueInfo['TotalCurrentPastDue']['_value']) ? $pastDueInfo['TotalCurrentPastDue']['_value'] : $pastDueInfo['TotalCurrentPastDue']) }}
                                            </div>
                                        </div>
                                        @if(isset($pastDueInfo['TotalCurrentDaysPastDue']))
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">Current Days Past Due</span>
                                                <div class="text-sm text-gray-900">
                                                    {{ isset($pastDueInfo['TotalCurrentDaysPastDue']['_value']) ? $pastDueInfo['TotalCurrentDaysPastDue']['_value'] : $pastDueInfo['TotalCurrentDaysPastDue'] }} days
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Repayment Information Row -->
                        @if($repaymentInfo && isset($repaymentInfo['TotalMonthlyPayment']))
                            <div class="border border-gray-200 rounded-lg">
                                <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                                    <h4 class="font-bold text-gray-900">Repayment Information</h4>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Total Monthly Payment</span>
                                            <div class="text-sm text-gray-900 font-semibold">
                                                TZS {{ number_format(isset($repaymentInfo['TotalMonthlyPayment']['_value']) ? $repaymentInfo['TotalMonthlyPayment']['_value'] : $repaymentInfo['TotalMonthlyPayment']) }}
                                            </div>
                                        </div>
                                        @if(isset($repaymentInfo['ClosedContracts']))
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">Closed Contracts</span>
                                                <div class="text-sm text-gray-900">
                                                    {{ isset($repaymentInfo['ClosedContracts']['_value']) ? $repaymentInfo['ClosedContracts']['_value'] : $repaymentInfo['ClosedContracts'] }}
                                                </div>
                                            </div>
                                        @endif
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
                                        @if(isset($inquiriesAnalysis['TotalLast7Days']))
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">Last 7 Days</span>
                                                <div class="text-sm text-gray-900">
                                                    {{ isset($inquiriesAnalysis['TotalLast7Days']['_value']) ? $inquiriesAnalysis['TotalLast7Days']['_value'] : $inquiriesAnalysis['TotalLast7Days'] }}
                                                </div>
                                            </div>
                                        @endif
                                        @if(isset($inquiriesAnalysis['TotalLast1Month']))
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">Last 1 Month</span>
                                                <div class="text-sm text-gray-900">
                                                    {{ isset($inquiriesAnalysis['TotalLast1Month']['_value']) ? $inquiriesAnalysis['TotalLast1Month']['_value'] : $inquiriesAnalysis['TotalLast1Month'] }}
                                                </div>
                                            </div>
                                        @endif
                                        @if(isset($inquiriesAnalysis['NonBankingLast1Month']))
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">Non-Banking Last Month</span>
                                                <div class="text-sm text-gray-900">
                                                    {{ isset($inquiriesAnalysis['NonBankingLast1Month']['_value']) ? $inquiriesAnalysis['NonBankingLast1Month']['_value'] : $inquiriesAnalysis['NonBankingLast1Month'] }}
                                                </div>
                                            </div>
                                        @endif
                                        @if(isset($inquiriesAnalysis['Conclusion']))
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">Conclusion</span>
                                                <div class="text-sm text-gray-900">
                                                    {{ isset($inquiriesAnalysis['Conclusion']['_value']) ? $inquiriesAnalysis['Conclusion']['_value'] : ($inquiriesAnalysis['Conclusion'] ?: 'N/A') }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    @if(isset($inquiriesAnalysis['PolicyRules']['Rule']))
                                        <div class="mt-4 pt-4 border-t border-gray-200">
                                            <h5 class="text-sm font-semibold text-gray-700 mb-2">Policy Rules</h5>
                                            <div class="space-y-2">
                                                @php
                                                    $rules = isset($inquiriesAnalysis['PolicyRules']['Rule'][0]) ? $inquiriesAnalysis['PolicyRules']['Rule'] : [$inquiriesAnalysis['PolicyRules']['Rule']];
                                                @endphp
                                                @foreach($rules as $rule)
                                                    @if(isset($rule['@attributes']['id']))
                                                        <div class="text-xs text-gray-600">
                                                            <span class="font-medium">{{ $rule['@attributes']['id'] }}:</span>
                                                            {{ getValue($rule, 'Result', 'N/A') }} - {{ getValue($rule, 'Description', '') }}
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Personal Information Row -->
                        @if($personalInfo)
                            <div class="border border-gray-200 rounded-lg">
                                <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                                    <h4 class="font-bold text-gray-900">Personal Information</h4>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                        @if(isset($personalInfo['FullName']))
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">Full Name</span>
                                                <div class="text-sm text-gray-900">{{ getValue($personalInfo, 'FullName') }}</div>
                                            </div>
                                        @endif
                                        @if(isset($personalInfo['DateOfBirth']))
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">Date of Birth</span>
                                                <div class="text-sm text-gray-900">
                                                    {{ \Carbon\Carbon::parse(getValue($personalInfo, 'DateOfBirth'))->format('M d, Y') }}
                                                </div>
                                            </div>
                                        @endif
                                        @if(isset($personalInfo['Age']))
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">Age</span>
                                                <div class="text-sm text-gray-900">{{ getValue($personalInfo, 'Age') }}</div>
                                            </div>
                                        @endif
                                        @if(isset($personalInfo['Gender']))
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">Gender</span>
                                                <div class="text-sm text-gray-900">{{ getValue($personalInfo, 'Gender') }}</div>
                                            </div>
                                        @endif
                                        @if(isset($personalInfo['MaritalStatus']))
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">Marital Status</span>
                                                <div class="text-sm text-gray-900">{{ getValue($personalInfo, 'MaritalStatus') }}</div>
                                            </div>
                                        @endif
                                        @if(isset($personalInfo['EmploymentStatus']))
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">Employment Status</span>
                                                <div class="text-sm text-gray-900">{{ getValue($personalInfo, 'EmploymentStatus') }}</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Scoring Analysis Policy Rules -->
                        @if($scoringAnalysis && isset($scoringAnalysis['PolicyRules']['Rule']))
                            <div class="border border-gray-200 rounded-lg">
                                <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                                    <h4 class="font-bold text-gray-900">Scoring Analysis Policy Rules</h4>
                                </div>
                                <div class="p-6">
                                    <div class="space-y-2">
                                        @php
                                            $rules = isset($scoringAnalysis['PolicyRules']['Rule'][0]) ? $scoringAnalysis['PolicyRules']['Rule'] : [$scoringAnalysis['PolicyRules']['Rule']];
                                        @endphp
                                        @foreach($rules as $rule)
                                            @if(isset($rule['@attributes']['id']))
                                                <div class="flex items-start space-x-4 p-3 bg-gray-50 rounded">
                                                    <div class="font-medium text-sm text-gray-700">{{ $rule['@attributes']['id'] }}</div>
                                                    <div class="text-sm text-gray-600">
                                                        <div><strong>Result:</strong> {{ getValue($rule, 'Result', 'N/A') }}</div>
                                                        <div><strong>Description:</strong> {{ getValue($rule, 'Description', '') }}</div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Root Policy Rules -->
                        @if($policyRules && isset($policyRules['Rule']))
                            <div class="border border-gray-200 rounded-lg">
                                <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                                    <h4 class="font-bold text-gray-900">Policy Rules</h4>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @php
                                            $rules = isset($policyRules['Rule'][0]) ? $policyRules['Rule'] : [$policyRules['Rule']];
                                        @endphp
                                        @foreach($rules as $rule)
                                            @if(isset($rule['@attributes']['id']))
                                                <div class="p-3 bg-gray-50 rounded">
                                                    <div class="font-medium text-sm text-gray-700 mb-1">{{ $rule['@attributes']['id'] }}</div>
                                                    <div class="text-xs text-gray-600">
                                                        <div><strong>Result:</strong> {{ getValue($rule, 'Result', 'N/A') }}</div>
                                                        <div><strong>Description:</strong> {{ getValue($rule, 'Description', '') }}</div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- CIP Records (Credit History) -->
                        @if($cipRecords)
                            <div class="border border-gray-200 rounded-lg">
                                <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                                    <h4 class="font-bold text-gray-900">CIP Records (Credit History)</h4>
                                </div>
                                <div class="p-6">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Score</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Grade</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Probability</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Trend</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Reasons</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @php
                                                    $records = isset($cipRecords[0]) ? $cipRecords : [$cipRecords];
                                                @endphp
                                                @foreach($records as $record)
                                                    <tr>
                                                        <td class="px-4 py-2 text-sm text-gray-900">
                                                            {{ \Carbon\Carbon::parse(getValue($record, 'Date'))->format('M d, Y') }}
                                                        </td>
                                                        <td class="px-4 py-2 text-sm text-gray-900">{{ getValue($record, 'Score') }}</td>
                                                        <td class="px-4 py-2 text-sm text-gray-900">{{ getValue($record, 'Grade') }}</td>
                                                        <td class="px-4 py-2 text-sm text-gray-900">{{ getValue($record, 'ProbabilityOfDefault') }}%</td>
                                                        <td class="px-4 py-2 text-sm text-gray-900">{{ getValue($record, 'Trend') }}</td>
                                                        <td class="px-4 py-2 text-sm text-gray-600">
                                                            @if(isset($record['ReasonsList']['Reason']))
                                                                @php
                                                                    $reasons = isset($record['ReasonsList']['Reason'][0]) ? $record['ReasonsList']['Reason'] : [$record['ReasonsList']['Reason']];
                                                                @endphp
                                                                @foreach($reasons as $reason)
                                                                    <div class="text-xs">
                                                                        {{ getValue($reason, 'Code') }}: {{ getValue($reason, 'Description', '') }}
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- CIQ Data -->
                        @if($ciqData)
                            <div class="border border-gray-200 rounded-lg">
                                <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                                    <h4 class="font-bold text-gray-900">CIQ Data</h4>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                        @if(isset($ciqData['Detail']))
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">Lost/Stolen Records</span>
                                                <div class="text-sm text-gray-900">{{ getValue($ciqData['Detail'], 'LostStolenRecordsFound', 0) }}</div>
                                            </div>
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">Cancelled/Closed Contracts</span>
                                                <div class="text-sm text-gray-900">{{ getValue($ciqData['Detail'], 'NumberOfCancelledClosedContracts', 0) }}</div>
                                            </div>
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">Inquiries Last 14 Days</span>
                                                <div class="text-sm text-gray-900">{{ getValue($ciqData['Detail'], 'NumberOfSubscribersMadeInquiriesLast14Days', 0) }}</div>
                                            </div>
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">Inquiries Last 2 Days</span>
                                                <div class="text-sm text-gray-900">{{ getValue($ciqData['Detail'], 'NumberOfSubscribersMadeInquiriesLast2Days', 0) }}</div>
                                            </div>
                                        @endif
                                        @if(isset($ciqData['Summary']))
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">Fraud Alerts (Primary)</span>
                                                <div class="text-sm text-gray-900">{{ getValue($ciqData['Summary'], 'NumberOfFraudAlertsPrimarySubject', 0) }}</div>
                                            </div>
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">Fraud Alerts (Third Party)</span>
                                                <div class="text-sm text-gray-900">{{ getValue($ciqData['Summary'], 'NumberOfFraudAlertsThirdParty', 0) }}</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Contract Overview -->
                        @if($contractOverview && isset($contractOverview['ContractList']['Contract']))
                            <div class="border border-gray-200 rounded-lg">
                                <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                                    <h4 class="font-bold text-gray-900">Contract Overview</h4>
                                </div>
                                <div class="p-6">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Sector</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Start Date</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total Amount</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Past Due Amount</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Past Due Days</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Phase</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @php
                                                    $contracts = isset($contractOverview['ContractList']['Contract'][0]) ? $contractOverview['ContractList']['Contract'] : [$contractOverview['ContractList']['Contract']];
                                                @endphp
                                                @foreach($contracts as $contract)
                                                    <tr>
                                                        <td class="px-4 py-2 text-sm text-gray-900">{{ getValue($contract, 'ContractStatus') }}</td>
                                                        <td class="px-4 py-2 text-sm text-gray-900">{{ getValue($contract, 'Sector') }}</td>
                                                        <td class="px-4 py-2 text-sm text-gray-900">
                                                            {{ \Carbon\Carbon::parse(getValue($contract, 'StartDate'))->format('M d, Y') }}
                                                        </td>
                                                        <td class="px-4 py-2 text-sm text-gray-900">
                                                            {{ getValue($contract['TotalAmount'], 'LocalValue', 0) }} {{ getValue($contract['TotalAmount'], 'Currency', 'TZS') }}
                                                        </td>
                                                        <td class="px-4 py-2 text-sm text-gray-900">
                                                            {{ getValue($contract['PastDueAmount'], 'LocalValue', 0) }} {{ getValue($contract['PastDueAmount'], 'Currency', 'TZS') }}
                                                        </td>
                                                        <td class="px-4 py-2 text-sm text-gray-900">{{ getValue($contract, 'PastDueDays', 0) }}</td>
                                                        <td class="px-4 py-2 text-sm text-gray-900">{{ getValue($contract, 'PhaseOfContract') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Contract Summary -->
                        @if($contractSummary)
                            <div class="border border-gray-200 rounded-lg">
                                <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                                    <h4 class="font-bold text-gray-900">Contract Summary</h4>
                                </div>
                                <div class="p-6">
                                    @if(isset($contractSummary['Debtor']))
                                        <div class="mb-4">
                                            <h5 class="text-sm font-semibold text-gray-700 mb-2">Debtor</h5>
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                                <div>
                                                    <span class="text-xs font-medium text-gray-500">Open Contracts</span>
                                                    <div class="text-sm text-gray-900">{{ getValue($contractSummary['Debtor'], 'OpenContracts', 0) }}</div>
                                                </div>
                                                <div>
                                                    <span class="text-xs font-medium text-gray-500">Closed Contracts</span>
                                                    <div class="text-sm text-gray-900">{{ getValue($contractSummary['Debtor'], 'ClosedContracts', 0) }}</div>
                                                </div>
                                                <div>
                                                    <span class="text-xs font-medium text-gray-500">Past Due Amount</span>
                                                    <div class="text-sm text-gray-900">
                                                        {{ getValue($contractSummary['Debtor']['PastDueAmountSum'], 'LocalValue', 0) }} {{ getValue($contractSummary['Debtor']['PastDueAmountSum'], 'Currency', 'TZS') }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <span class="text-xs font-medium text-gray-500">Total Amount</span>
                                                    <div class="text-sm text-gray-900">
                                                        {{ getValue($contractSummary['Debtor']['TotalAmountSum'], 'LocalValue', 0) }} {{ getValue($contractSummary['Debtor']['TotalAmountSum'], 'Currency', 'TZS') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if(isset($contractSummary['Overall']))
                                        <div class="mb-4 pt-4 border-t border-gray-200">
                                            <h5 class="text-sm font-semibold text-gray-700 mb-2">Overall</h5>
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                                <div>
                                                    <span class="text-xs font-medium text-gray-500">Max Due Installments (Open)</span>
                                                    <div class="text-sm text-gray-900">{{ getValue($contractSummary['Overall'], 'MaxDueInstallmentsOpenContracts', 0) }}</div>
                                                </div>
                                                <div>
                                                    <span class="text-xs font-medium text-gray-500">Max Due Installments (Closed)</span>
                                                    <div class="text-sm text-gray-900">{{ getValue($contractSummary['Overall'], 'MaxDueInstallmentsClosedContracts', 0) }}</div>
                                                </div>
                                                <div>
                                                    <span class="text-xs font-medium text-gray-500">Worst Past Due Amount</span>
                                                    <div class="text-sm text-gray-900">
                                                        {{ getValue($contractSummary['Overall']['WorstPastDueAmount'], 'LocalValue', 0) }} {{ getValue($contractSummary['Overall']['WorstPastDueAmount'], 'Currency', 'TZS') }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <span class="text-xs font-medium text-gray-500">Worst Past Due Days</span>
                                                    <div class="text-sm text-gray-900">{{ getValue($contractSummary['Overall'], 'WorstPastDueDays', 0) }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if(isset($contractSummary['SectorInfoList']['SectorInfo']))
                                        <div class="pt-4 border-t border-gray-200">
                                            <h5 class="text-sm font-semibold text-gray-700 mb-2">Sector Information</h5>
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full divide-y divide-gray-200">
                                                    <thead class="bg-gray-50">
                                                        <tr>
                                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Sector</th>
                                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Open Contracts</th>
                                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Closed Contracts</th>
                                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Past Due Amount</th>
                                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="bg-white divide-y divide-gray-200">
                                                        @php
                                                            $sectors = isset($contractSummary['SectorInfoList']['SectorInfo'][0]) ? $contractSummary['SectorInfoList']['SectorInfo'] : [$contractSummary['SectorInfoList']['SectorInfo']];
                                                        @endphp
                                                        @foreach($sectors as $sector)
                                                            <tr>
                                                                <td class="px-4 py-2 text-sm text-gray-900">{{ getValue($sector, 'Sector') }}</td>
                                                                <td class="px-4 py-2 text-sm text-gray-900">{{ getValue($sector, 'DebtorOpenContracts', 0) }}</td>
                                                                <td class="px-4 py-2 text-sm text-gray-900">{{ getValue($sector, 'DebtorClosedContracts', 0) }}</td>
                                                                <td class="px-4 py-2 text-sm text-gray-900">
                                                                    {{ getValue($sector['DebtorPastDueAmountSum'], 'LocalValue', 0) }} {{ getValue($sector['DebtorPastDueAmountSum'], 'Currency', 'TZS') }}
                                                                </td>
                                                                <td class="px-4 py-2 text-sm text-gray-900">
                                                                    {{ getValue($sector['DebtorTotalAmountSum'], 'LocalValue', 0) }} {{ getValue($sector['DebtorTotalAmountSum'], 'Currency', 'TZS') }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Dashboard -->
                        @if($dashboard)
                            <div class="border border-gray-200 rounded-lg">
                                <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                                    <h4 class="font-bold text-gray-900">Dashboard</h4>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                        @if(isset($dashboard['CIP']))
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">CIP Score</span>
                                                <div class="text-sm text-gray-900 font-semibold">{{ getValue($dashboard['CIP'], 'Score') }}</div>
                                            </div>
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">CIP Grade</span>
                                                <div class="text-sm text-gray-900 font-semibold">{{ getValue($dashboard['CIP'], 'Grade') }}</div>
                                            </div>
                                        @endif
                                        @if(isset($dashboard['PaymentsProfile']))
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">Past Due Amount Sum</span>
                                                <div class="text-sm text-gray-900">
                                                    {{ getValue($dashboard['PaymentsProfile']['PastDueAmountSum'], 'LocalValue', 0) }} {{ getValue($dashboard['PaymentsProfile']['PastDueAmountSum'], 'Currency', 'TZS') }}
                                                </div>
                                            </div>
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">Worst Past Due Days (Current)</span>
                                                <div class="text-sm text-gray-900">{{ getValue($dashboard['PaymentsProfile'], 'WorstPastDueDaysCurrent', 0) }}</div>
                                            </div>
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">Worst Past Due Days (12M)</span>
                                                <div class="text-sm text-gray-900">{{ getValue($dashboard['PaymentsProfile'], 'WorstPastDueDaysForLast12Months', 0) }}</div>
                                            </div>
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">Number of Subscribers</span>
                                                <div class="text-sm text-gray-900">{{ getValue($dashboard['PaymentsProfile'], 'NumberOfDifferentSubscribers', 0) }}</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Individual Information -->
                        @if($individual)
                            <div class="border border-gray-200 rounded-lg">
                                <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                                    <h4 class="font-bold text-gray-900">Individual Information</h4>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                        @if(isset($individual['General']))
                                            @foreach($individual['General'] as $key => $value)
                                                @if(!is_array($value) || isset($value['_value']))
                                                    <div>
                                                        <span class="text-sm font-medium text-gray-500">{{ str_replace(['_', 'Of'], [' ', 'of'], $key) }}</span>
                                                        <div class="text-sm text-gray-900">{{ is_array($value) ? getValue($value, $key) : $value }}</div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                        @if(isset($individual['Contact']))
                                            @foreach($individual['Contact'] as $key => $value)
                                                <div>
                                                    <span class="text-sm font-medium text-gray-500">{{ str_replace(['_', 'Of'], [' ', 'of'], $key) }}</span>
                                                    <div class="text-sm text-gray-900">{{ is_array($value) ? getValue($value, $key) : $value }}</div>
                                                </div>
                                            @endforeach
                                        @endif
                                        @if(isset($individual['Identifications']))
                                            @foreach($individual['Identifications'] as $key => $value)
                                                <div>
                                                    <span class="text-sm font-medium text-gray-500">{{ str_replace(['_', 'Of'], [' ', 'of'], $key) }}</span>
                                                    <div class="text-sm text-gray-900">{{ is_array($value) ? getValue($value, $key) : $value }}</div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Report Info -->
                        @if($reportInfo)
                            <div class="border border-gray-200 rounded-lg">
                                <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                                    <h4 class="font-bold text-gray-900">Report Information</h4>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                        @foreach($reportInfo as $key => $value)
                                            @if(!is_array($value))
                                                <div>
                                                    <span class="text-sm font-medium text-gray-500">{{ str_replace(['_', 'Of'], [' ', 'of'], $key) }}</span>
                                                    <div class="text-sm text-gray-900">{{ $value }}</div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Strategy Information -->
                        @if($strategy)
                            <div class="border border-gray-200 rounded-lg">
                                <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                                    <h4 class="font-bold text-gray-900">Strategy Applied</h4>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                        @foreach($strategy as $key => $value)
                                            @if(!is_array($value))
                                                <div>
                                                    <span class="text-sm font-medium text-gray-500">{{ str_replace(['_', 'Of'], [' ', 'of'], $key) }}</span>
                                                    <div class="text-sm text-gray-900">{{ $value }}</div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Extract Section (All Parameters) -->
                        @if($extract)
                            <div class="border border-gray-200 rounded-lg">
                                <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                                    <h4 class="font-bold text-gray-900">Extract Parameters</h4>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                        @foreach($extract as $key => $value)
                                            @if(!is_array($value) || (is_array($value) && !isset($value[0])))
                                                <div>
                                                    <span class="text-sm font-medium text-gray-500">{{ str_replace(['_', 'Of'], [' ', 'of'], $key) }}</span>
                                                    <div class="text-sm text-gray-900">
                                                        @if(is_array($value))
                                                            {{ getValue($value, $key, 'N/A') }}
                                                        @else
                                                            {{ $value }}
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="border border-gray-200 rounded-lg p-6">
                            <p class="text-gray-500 text-sm">No detailed credit information is available for this request.</p>
                            @if($selectedRequest->error_message)
                                <p class="text-red-500 text-sm mt-2">Error: {{ $selectedRequest->error_message }}</p>
                            @endif
                        </div>
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
</div>


