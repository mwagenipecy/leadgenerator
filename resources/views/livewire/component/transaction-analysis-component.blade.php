<div class="min-h-screen bg-gray-100">
    <!-- Header with Logo and Title -->
    <div class="bg-white shadow-lg border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <img src="{{ asset('/logo/cit_logo.svg') }}" class="h-12 w-auto" alt="Logo" />
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-black">Transaction Analytics Dashboard</h1>
                        <p class="text-sm text-gray-500">Real-time Financial Analysis</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="hidden md:flex items-center space-x-2 text-sm text-gray-500">
                        <i class="fas fa-clock"></i>
                        <span>Last updated: {{ now()->format('M d, Y H:i') }}</span>
                    </div>
                    <button wire:click="$refresh" 
                            class="bg-sidebar-green hover:bg-sidebar-green-light text-white px-4 py-2 rounded-lg transition-colors duration-200">
                        <i class="fas fa-sync-alt mr-2"></i>Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

    @php
        $latestAnalysis = $analyses->first();
        $responseData = $latestAnalysis ? $latestAnalysis->full_response : null;
        $profile = $responseData['profile'] ?? null;
        $oneDAnalysis = $responseData['1d_analysis'] ?? null;
        $twoDAnalysis = $responseData['2d_analysis'] ?? null;
        $threeDAnalysis = $responseData['3d_analysis'] ?? null;
        $customerProfile = $oneDAnalysis['customer_profile'] ?? null;
        $cashFlow = $oneDAnalysis['cash_flow_summary'] ?? null;
        $affordability = $responseData['affordability_scores'] ?? null;
    @endphp

    @if($latestAnalysis && $responseData)
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Account Header -->
            <div class="bg-black text-white rounded-lg shadow-lg p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <img src="{{ asset('/logo/creditinfo_logo-2.png') }}" class="h-12 w-auto rounded" alt="Company Logo" />
                        <div>
                            <h2 class="text-3xl font-bold">{{ $profile['account'] ?? $latestAnalysis->account_number }}</h2>
                            <p class="text-gray-300">{{ $profile['company'] ?? 'Mobile Money Account' }} • {{ $profile['currency_code'] ?? 'TZS' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $latestAnalysis->status === 'success' ? 'bg-green-500 text-white' : 'bg-sidebar-green text-white' }}">
                            {{ ucfirst($latestAnalysis->status) }}
                        </span>
                        <p class="text-gray-300 text-sm mt-2">{{ $latestAnalysis->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
                
                @if($profile)
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-white bg-opacity-20 rounded-lg p-4 text-center">
                            <p class="text-sm text-gray-300">Analysis Period</p>
                            <p class="text-lg font-bold">
                                {{ \Carbon\Carbon::parse($profile['start_date'])->format('M d') }} - 
                                {{ \Carbon\Carbon::parse($profile['end_date'])->format('M d') }}
                            </p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-lg p-4 text-center">
                            <p class="text-sm text-gray-300">Total Days</p>
                            <p class="text-lg font-bold">{{ $oneDAnalysis['initial_info']['total_days'] ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-lg p-4 text-center">
                            <p class="text-sm text-gray-300">Active Days</p>
                            <p class="text-lg font-bold">{{ $oneDAnalysis['initial_info']['total_active_days'] ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-lg p-4 text-center">
                            <p class="text-sm text-gray-300">Account Type</p>
                            <p class="text-lg font-bold">{{ strtoupper($profile['type'] ?? 'MNO') }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Financial Overview Cards -->
            @if($customerProfile)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                    <div class="bg-white rounded-lg shadow-lg border-l-4 border-sidebar-green p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-sidebar-green">Total Turnover</p>
                                <p class="text-3xl font-bold text-black mt-2">
                                    {{ number_format($customerProfile['total_turnover']) }}
                                </p>
                                <p class="text-sm text-gray-500">{{ $profile['currency_code'] ?? 'TZS' }}</p>
                            </div>
                            <div class="w-16 h-16 bg-sidebar-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-chart-line text-sidebar-green text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-lg border-l-4 border-green-500 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-green-600">Wallet Balance</p>
                                <p class="text-3xl font-bold text-black mt-2">
                                    {{ number_format($customerProfile['wallet_balance']) }}
                                </p>
                                <p class="text-sm text-gray-500">{{ $profile['currency_code'] ?? 'TZS' }}</p>
                            </div>
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-wallet text-green-600 text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-lg border-l-4 border-blue-500 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-blue-600">Total Transactions</p>
                                <p class="text-3xl font-bold text-black mt-2">
                                    {{ number_format($customerProfile['total_transactions']) }}
                                </p>
                                <p class="text-sm text-gray-500">Transactions</p>
                            </div>
                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-exchange-alt text-blue-600 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Cash Flow Analysis -->
            @if($cashFlow)
                <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                    <h3 class="text-2xl font-bold text-black mb-6 flex items-center">
                        <i class="fas fa-chart-area mr-3 text-sidebar-green"></i>
                        Cash Flow Analysis
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Cash Inflow -->
                        <div class="bg-green-50 rounded-lg p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-xl font-bold text-green-700">Cash Inflow</h4>
                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-arrow-down text-green-600"></i>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-3xl font-bold text-black">
                                        {{ number_format($cashFlow['total_cashin']) }} {{ $profile['currency_code'] ?? 'TZS' }}
                                    </p>
                                    <p class="text-sm text-gray-600">{{ $cashFlow['cashin_transactions'] }} transactions</p>
                                </div>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <p class="text-gray-600">% of Turnover</p>
                                        <p class="font-bold text-black">{{ number_format($cashFlow['cashin_to_turnover_percentage'], 1) }}%</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600">% of Total Trans</p>
                                        <p class="font-bold text-black">{{ number_format($cashFlow['cashin_to_total_trans_percentage'], 1) }}%</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cash Outflow -->
                        <div class="bg-sidebar-green-50 rounded-lg p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-xl font-bold text-sidebar-green-light">Cash Outflow</h4>
                                <div class="w-12 h-12 bg-sidebar-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-arrow-up text-sidebar-green"></i>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-3xl font-bold text-black">
                                        {{ number_format($cashFlow['total_cashout']) }} {{ $profile['currency_code'] ?? 'TZS' }}
                                    </p>
                                    <p class="text-sm text-gray-600">{{ $cashFlow['cashout_transactions'] }} transactions</p>
                                </div>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <p class="text-gray-600">% of Turnover</p>
                                        <p class="font-bold text-black">{{ number_format($cashFlow['cashout_to_turnover_percentage'], 1) }}%</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600">% of Total Trans</p>
                                        <p class="font-bold text-black">{{ number_format($cashFlow['cashout_to_total_trans_percentage'], 1) }}%</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Affordability Assessment -->
            @if($affordability)
                <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                    <h3 class="text-2xl font-bold text-black mb-6 flex items-center">
                        <i class="fas fa-star mr-3 text-sidebar-green"></i>
                        Affordability Assessment
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="bg-sidebar-green-50 rounded-lg p-6 text-center border-2 border-sidebar-green-200">
                            <div class="w-20 h-20 bg-sidebar-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-3xl font-bold text-sidebar-green">{{ $affordability['rank'] }}</span>
                            </div>
                            <p class="text-lg font-bold text-black">Affordability Rank</p>
                            <p class="text-sm text-gray-600">Overall Rating</p>
                        </div>
                        
                        <div class="bg-green-50 rounded-lg p-6 text-center">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-arrow-up text-green-600 text-2xl"></i>
                            </div>
                            <p class="text-lg font-bold text-black">High Capacity</p>
                            <p class="text-2xl font-bold text-green-600">
                                {{ number_format($affordability['high']) }}
                            </p>
                            <p class="text-sm text-gray-600">{{ $profile['currency_code'] ?? 'TZS' }}</p>
                        </div>
                        
                        <div class="bg-yellow-50 rounded-lg p-6 text-center">
                            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-minus text-yellow-600 text-2xl"></i>
                            </div>
                            <p class="text-lg font-bold text-black">Moderate</p>
                            <p class="text-2xl font-bold text-yellow-600">
                                {{ number_format($affordability['moderate']) }}
                            </p>
                            <p class="text-sm text-gray-600">{{ $profile['currency_code'] ?? 'TZS' }}</p>
                        </div>
                        
                        <div class="bg-sidebar-green-50 rounded-lg p-6 text-center">
                            <div class="w-16 h-16 bg-sidebar-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-arrow-down text-sidebar-green text-2xl"></i>
                            </div>
                            <p class="text-lg font-bold text-black">Low Capacity</p>
                            <p class="text-2xl font-bold text-sidebar-green">
                                {{ number_format($affordability['low']) }}
                            </p>
                            <p class="text-sm text-gray-600">{{ $profile['currency_code'] ?? 'TZS' }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Transaction Breakdown -->
            @if($oneDAnalysis)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- Cash In Sources -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-xl font-bold text-black mb-6 flex items-center">
                            <i class="fas fa-plus-circle mr-3 text-green-600"></i>
                            Cash In Sources
                        </h3>
                        <div class="space-y-4">
                            @if(isset($oneDAnalysis['bank_to_wallet']) && $oneDAnalysis['bank_to_wallet']['total_bank_to_wallet_amount'] > 0)
                                <div class="flex justify-between items-center p-4 bg-green-50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-black">Bank to Wallet</p>
                                        <p class="text-sm text-gray-600">{{ $oneDAnalysis['bank_to_wallet']['no_of_bank_to_wallet_transactions'] }} transactions</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-black">{{ number_format($oneDAnalysis['bank_to_wallet']['total_bank_to_wallet_amount']) }}</p>
                                        <p class="text-sm text-gray-600">{{ $profile['currency_code'] ?? 'TZS' }}</p>
                                    </div>
                                </div>
                            @endif
                            
                            @if(isset($oneDAnalysis['p2p_received']) && $oneDAnalysis['p2p_received']['total_p2p_received_amount'] > 0)
                                <div class="flex justify-between items-center p-4 bg-green-50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-black">P2P Received</p>
                                        <p class="text-sm text-gray-600">{{ $oneDAnalysis['p2p_received']['no_of_p2p_received_transactions'] }} transactions</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-black">{{ number_format($oneDAnalysis['p2p_received']['total_p2p_received_amount']) }}</p>
                                        <p class="text-sm text-gray-600">{{ $profile['currency_code'] ?? 'TZS' }}</p>
                                    </div>
                                </div>
                            @endif
                            
                            @if(isset($oneDAnalysis['agent_deposit']) && $oneDAnalysis['agent_deposit']['total_agent_deposit_amount'] > 0)
                                <div class="flex justify-between items-center p-4 bg-green-50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-black">Agent Deposit</p>
                                        <p class="text-sm text-gray-600">{{ $oneDAnalysis['agent_deposit']['no_of_agent_deposit_transactions'] }} transactions</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-black">{{ number_format($oneDAnalysis['agent_deposit']['total_agent_deposit_amount']) }}</p>
                                        <p class="text-sm text-gray-600">{{ $profile['currency_code'] ?? 'TZS' }}</p>
                                    </div>
                                </div>
                            @endif
                            
                            @if(isset($oneDAnalysis['other']) && $oneDAnalysis['other']['total_other_amount'] > 0)
                                <div class="flex justify-between items-center p-4 bg-green-50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-black">Other Income</p>
                                        <p class="text-sm text-gray-600">{{ $oneDAnalysis['other']['no_of_other_transactions'] }} transactions</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-black">{{ number_format($oneDAnalysis['other']['total_other_amount']) }}</p>
                                        <p class="text-sm text-gray-600">{{ $profile['currency_code'] ?? 'TZS' }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Cash Out Categories -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-xl font-bold text-black mb-6 flex items-center">
                            <i class="fas fa-minus-circle mr-3 text-sidebar-green"></i>
                            Cash Out Categories
                        </h3>
                        <div class="space-y-4">
                            @if(isset($oneDAnalysis['p2p_sent']) && $oneDAnalysis['p2p_sent']['total_p2p_sent_amount'] > 0)
                                <div class="flex justify-between items-center p-4 bg-sidebar-green-50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-black">P2P Sent</p>
                                        <p class="text-sm text-gray-600">{{ $oneDAnalysis['p2p_sent']['no_of_p2p_sent_transactions'] }} transactions</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-black">{{ number_format($oneDAnalysis['p2p_sent']['total_p2p_sent_amount']) }}</p>
                                        <p class="text-sm text-gray-600">{{ $profile['currency_code'] ?? 'TZS' }}</p>
                                    </div>
                                </div>
                            @endif
                            
                            @if(isset($oneDAnalysis['agent_withdrawal']) && $oneDAnalysis['agent_withdrawal']['total_agent_withdrawal_amount'] > 0)
                                <div class="flex justify-between items-center p-4 bg-sidebar-green-50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-black">Agent Withdrawal</p>
                                        <p class="text-sm text-gray-600">{{ $oneDAnalysis['agent_withdrawal']['no_of_agent_withdrawal_transactions'] }} transactions</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-black">{{ number_format($oneDAnalysis['agent_withdrawal']['total_agent_withdrawal_amount']) }}</p>
                                        <p class="text-sm text-gray-600">{{ $profile['currency_code'] ?? 'TZS' }}</p>
                                    </div>
                                </div>
                            @endif
                            
                            @if(isset($oneDAnalysis['bill_payment']) && $oneDAnalysis['bill_payment']['total_bill_payment_amount'] > 0)
                                <div class="flex justify-between items-center p-4 bg-sidebar-green-50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-black">Bill Payments</p>
                                        <p class="text-sm text-gray-600">{{ $oneDAnalysis['bill_payment']['no_of_bill_payment_transactions'] }} transactions</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-black">{{ number_format($oneDAnalysis['bill_payment']['total_bill_payment_amount']) }}</p>
                                        <p class="text-sm text-gray-600">{{ $profile['currency_code'] ?? 'TZS' }}</p>
                                    </div>
                                </div>
                            @endif
                            
                            @if(isset($oneDAnalysis['wallet_to_bank']) && $oneDAnalysis['wallet_to_bank']['total_wallet_to_bank_amount'] > 0)
                                <div class="flex justify-between items-center p-4 bg-sidebar-green-50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-black">Wallet to Bank</p>
                                        <p class="text-sm text-gray-600">{{ $oneDAnalysis['wallet_to_bank']['no_of_wallet_to_bank_transactions'] }} transactions</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-black">{{ number_format($oneDAnalysis['wallet_to_bank']['total_wallet_to_bank_amount']) }}</p>
                                        <p class="text-sm text-gray-600">{{ $profile['currency_code'] ?? 'TZS' }}</p>
                                    </div>
                                </div>
                            @endif
                            
                            @if(isset($oneDAnalysis['luku']) && $oneDAnalysis['luku']['total_luku_amount'] > 0)
                                <div class="flex justify-between items-center p-4 bg-sidebar-green-50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-black">Luku Payments</p>
                                        <p class="text-sm text-gray-600">{{ $oneDAnalysis['luku']['no_of_luku_transactions'] }} transactions</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-black">{{ number_format($oneDAnalysis['luku']['total_luku_amount']) }}</p>
                                        <p class="text-sm text-gray-600">{{ $profile['currency_code'] ?? 'TZS' }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Loan Services Overview -->
            @if($oneDAnalysis && (
                (isset($oneDAnalysis['songesha_info']) && ($oneDAnalysis['songesha_info']['total_amount_songesha_disbursed'] > 0 || $oneDAnalysis['songesha_info']['total_amount_songesha_repaid'] > 0)) ||
                (isset($oneDAnalysis['mpawa_info']) && ($oneDAnalysis['mpawa_info']['total_amount_mpawa_disbursed'] > 0 || $oneDAnalysis['mpawa_info']['total_amount_mpawa_repaid'] > 0)) ||
                (isset($oneDAnalysis['chomoka_info']) && ($oneDAnalysis['chomoka_info']['total_amount_chomoka_disbursed'] > 0 || $oneDAnalysis['chomoka_info']['total_amount_chomoka_repaid'] > 0)) ||
                (isset($oneDAnalysis['mgodi_info']) && ($oneDAnalysis['mgodi_info']['total_amount_mgodi_disbursed'] > 0 || $oneDAnalysis['mgodi_info']['total_amount_mgodi_repaid'] > 0))
            ))
                <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                    <h3 class="text-2xl font-bold text-black mb-6 flex items-center">
                        <i class="fas fa-credit-card mr-3 text-sidebar-green"></i>
                        Loan Services Activity
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach(['songesha_info' => 'Songesha', 'mpawa_info' => 'Mpawa', 'chomoka_info' => 'Chomoka', 'mgodi_info' => 'Mgodi'] as $key => $name)
                            @if(isset($oneDAnalysis[$key]))
                                @php 
                                    $loanInfo = $oneDAnalysis[$key];
                                    $disbursed = $loanInfo['total_amount_' . strtolower($name) . '_disbursed'];
                                    $repaid = $loanInfo['total_amount_' . strtolower($name) . '_repaid'];
                                @endphp
                                @if($disbursed > 0 || $repaid > 0)
                                    <div class="bg-gray-50 rounded-lg p-6 text-center">
                                        <h4 class="text-lg font-bold text-black mb-4">{{ $name }}</h4>
                                        <div class="space-y-3">
                                            <div class="bg-green-100 rounded-lg p-3">
                                                <p class="text-sm text-green-600 font-medium">Disbursed</p>
                                                <p class="text-xl font-bold text-black">{{ number_format($disbursed) }}</p>
                                                <p class="text-xs text-gray-600">{{ $loanInfo['number_of_' . strtolower($name) . '_disbursements'] }} transactions</p>
                                            </div>
                                            <div class="bg-sidebar-green-100 rounded-lg p-3">
                                                <p class="text-sm text-sidebar-green font-medium">Repaid</p>
                                                <p class="text-xl font-bold text-black">{{ number_format($repaid) }}</p>
                                                <p class="text-xs text-gray-600">{{ $loanInfo['number_of_' . strtolower($name) . '_repayments'] }} transactions</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Monthly Trends -->
            @if($threeDAnalysis && isset($threeDAnalysis['cash_flow_analysis']))
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-2xl font-bold text-black mb-6 flex items-center">
                        <i class="fas fa-calendar-alt mr-3 text-sidebar-green"></i>
                        Monthly Cash Flow Trends
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Monthly Inflow -->
                        <div class="bg-green-50 rounded-lg p-6">
                            <h4 class="text-xl font-bold text-green-700 mb-4">Monthly Cash Inflow</h4>
                            <div class="space-y-4">
                                @foreach($threeDAnalysis['cash_flow_analysis']['cash_inflow'] as $monthData)
                                    <div class="flex justify-between items-center p-3 bg-white rounded-lg">
                                        <span class="font-medium text-black">{{ $monthData['month'] }}</span>
                                        <div class="text-right">
                                            <span class="text-lg font-bold text-black">{{ number_format($monthData['amount']) }}</span>
                                            <span class="text-sm text-gray-600 block">({{ number_format($monthData['percentage'], 1) }}%)</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Monthly Outflow -->
                        <div class="bg-sidebar-green-50 rounded-lg p-6">
                            <h4 class="text-xl font-bold text-sidebar-green-light mb-4">Monthly Cash Outflow</h4>
                            <div class="space-y-4">
                                @foreach($threeDAnalysis['cash_flow_analysis']['cash_outflow'] as $monthData)
                                    <div class="flex justify-between items-center p-3 bg-white rounded-lg">
                                        <span class="font-medium text-black">{{ $monthData['month'] }}</span>
                                        <div class="text-right">
                                            <span class="text-lg font-bold text-black">{{ number_format($monthData['amount']) }}</span>
                                            <span class="text-sm text-gray-600 block">({{ number_format($monthData['percentage'], 1) }}%)</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    @else
        <!-- No Data State -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                <div class="flex flex-col items-center">
                    <i class="fas fa-chart-line text-6xl text-gray-300 mb-6"></i>
                    <h2 class="text-2xl font-bold text-black mb-4">No Transaction Analysis Available</h2>
                    <p class="text-lg text-gray-500 mb-6">No transaction analysis data found for this user.</p>
                    <button wire:click="$refresh" 
                            class="bg-sidebar-green hover:bg-sidebar-green-light text-white px-6 py-3 rounded-lg transition-colors duration-200 font-medium">
                        <i class="fas fa-sync-alt mr-2"></i>Check for Updates
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>