<div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Income Details -->
    <div>
        <h3 class="text-lg font-semibold text-black mb-4">Income Information</h3>
        <div class="bg-gray-50 rounded-lg p-6 space-y-4">
            <div>
                <label class="text-sm font-medium text-gray-600">Total Monthly Income</label>
                <p class="text-lg font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                    {{ $this->getBlurredAmount($application->total_monthly_income) }}
                </p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">Salary Income</label>
                    <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                        {{ $this->getBlurredAmount($application->rent_mortgage) }}
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Existing Loan Payments</label>
                    <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                        {{ $this->getBlurredAmount($application->existing_loan_payments) }}
                    </p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">Credit Card Payments</label>
                    <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                        {{ $this->getBlurredAmount($application->credit_card_payments) }}
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Other Obligations</label>
                    <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                        {{ $this->getBlurredAmount($application->other_obligations) }}
                    </p>
                </div>
            </div>
            <div class="pt-4 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-600">Net Monthly Income</span>
                    <span class="text-lg font-bold text-green-600 {{ $isAvailable ? 'blur-sm' : '' }}">
                        @if($isAvailable)
                            TSh ***,***
                        @else
                            TSh {{ number_format(($application->total_monthly_income ?? 0) - ($application->monthly_expenses ?? 0)) }}
                        @endif
                    </span>
                </div>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm font-medium text-gray-600">Debt-to-Income Ratio</span>
                @if($application->debt_to_income_ratio && !$isAvailable)
                    <span class="text-lg font-bold {{ $application->debt_to_income_ratio <= 30 ? 'text-green-600' : ($application->debt_to_income_ratio <= 40 ? 'text-yellow-600' : 'text-sidebar-green') }}">
                        {{ number_format($application->debt_to_income_ratio, 1) }}%
                    </span>
                @else
                    <span class="text-lg font-bold text-gray-400 {{ $isAvailable ? 'blur-sm' : '' }}">
                        {{ $isAvailable ? '**.*%' : 'N/A' }}
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Banking Information -->
<div class="mt-8">
    <h3 class="text-lg font-semibold text-black mb-4">Banking Information</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gray-50 rounded-lg p-6 space-y-4">
            <div>
                <label class="text-sm font-medium text-gray-600">Primary Bank</label>
                <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                    @if($isAvailable)
                        ***Bank
                    @else
                        {{ $application->bank_name ?? 'N/A' }}
                    @endif
                </p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Account Number</label>
                <p class="text-sm font-bold text-black mt-1 {{ $isAvailable ? 'blur-sm' : '' }}">
                    @if($isAvailable)
                        ****-****-****
                    @else
                        {{ $application->account_number ? '****-****-' . substr($application->account_number, -4) : 'N/A' }}
                    @endif
                </p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Account Type</label>
                <p class="text-sm font-bold text-black mt-1">
                    {{ ucwords(str_replace('_', ' ', $application->account_type ?? 'N/A')) }}
                </p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Years with Bank</label>
                <p class="text-sm font-bold text-black mt-1">
                    {{ $application->years_with_bank ?? 'N/A' }} years
                </p>
            </div>
        </div>

        <!-- Financial Summary -->
        <div class="bg-gray-50 rounded-lg p-6">
            <h4 class="font-medium text-black mb-4">Financial Summary</h4>
            <div class="space-y-3">
                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-sm text-gray-600">Monthly Income:</span>
                    <span class="text-sm font-bold text-black {{ $isAvailable ? 'blur-sm' : '' }}">
                        {{ $this->getBlurredAmount($application->total_monthly_income) }}
                    </span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-sm text-gray-600">Monthly Expenses:</span>
                    <span class="text-sm font-bold text-sidebar-green {{ $isAvailable ? 'blur-sm' : '' }}">
                        {{ $this->getBlurredAmount($application->monthly_expenses) }}
                    </span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-sm text-gray-600">Disposable Income:</span>
                    <span class="text-sm font-bold text-green-600 {{ $isAvailable ? 'blur-sm' : '' }}">
                        @if($isAvailable)
                            TSh ***,***
                        @else
                            TSh {{ number_format(($application->total_monthly_income ?? 0) - ($application->monthly_expenses ?? 0)) }}
                        @endif
                    </span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-sm text-gray-600">Loan Affordability:</span>
                    @php
                        $disposableIncome = ($application->total_monthly_income ?? 0) - ($application->monthly_expenses ?? 0);
                        $maxLoanPayment = $disposableIncome * 0.4; // 40% of disposable income
                    @endphp
                    <span class="text-sm font-bold text-black {{ $isAvailable ? 'blur-sm' : '' }}">
                        @if($isAvailable)
                            TSh ***,***
                        @else
                            TSh {{ number_format($maxLoanPayment) }}
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>



</div>
