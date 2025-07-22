{{-- resources/views/livewire/admin/settings-management.blade.php --}}
<div>
    <div class="p-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">System Settings</h1>
                    <p class="text-gray-600 text-lg">Configure billing, commissions, and system preferences</p>
                </div>
                <div class="flex items-center space-x-2 bg-green-50 px-4 py-2 rounded-full">
                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                    <span class="text-sm font-medium text-green-700">Settings Active</span>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-3xl mb-6 flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('message') }}</span>
            </div>
        @endif

        <!-- Settings Tabs -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-8">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 px-8" aria-label="Tabs">
                    <button wire:click="setActiveTab('commission')" 
                            class="border-transparent {{ $activeTab === 'commission' ? 'text-red-600 border-b-2 border-red-500' : 'text-gray-500 hover:text-gray-700' }} py-6 px-1 text-sm font-medium transition-colors">
                        Commission Settings
                    </button>
                    <button wire:click="setActiveTab('payment')" 
                            class="border-transparent {{ $activeTab === 'payment' ? 'text-red-600 border-b-2 border-red-500' : 'text-gray-500 hover:text-gray-700' }} py-6 px-1 text-sm font-medium transition-colors">
                        Payment Settings
                    </button>
                    <button wire:click="setActiveTab('business')" 
                            class="border-transparent {{ $activeTab === 'business' ? 'text-red-600 border-b-2 border-red-500' : 'text-gray-500 hover:text-gray-700' }} py-6 px-1 text-sm font-medium transition-colors">
                        Business Info
                    </button>
                </nav>
            </div>

            <!-- Commission Settings Tab -->
            @if($activeTab === 'commission')
                <div class="p-8">
                    <form wire:submit.prevent="saveGeneralSettings">
                        <!-- Default Commission Configuration -->
                        <div class="mb-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-4">Default Commission Configuration</h3>
                            <p class="text-gray-600 mb-6">Set default commission rates for all lenders. Individual lender settings will override these defaults.</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <!-- Commission Type -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Commission Type</label>
                                    <select wire:model.live="default_commission_type" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                        <option value="percentage">Percentage of Loan</option>
                                        <option value="fixed">Fixed Amount</option>
                                    </select>
                                </div>

                                <!-- Commission Percentage -->
                                @if($default_commission_type === 'percentage')
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Commission Percentage (%)</label>
                                        <div class="relative">
                                            <input wire:model="default_commission_percentage" type="number" step="0.1" min="0" max="100" 
                                                   class="w-full border border-gray-300 rounded-xl px-3 py-2 pr-8 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                            <span class="absolute right-3 top-2 text-gray-500">%</span>
                                        </div>
                                        @error('default_commission_percentage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                @else
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Fixed Commission Amount</label>
                                        <div class="relative">
                                            <input wire:model="default_commission_fixed_amount" type="number" step="0.01" min="0" 
                                                   class="w-full border border-gray-300 rounded-xl px-3 py-2 pl-12 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                            <span class="absolute left-3 top-2 text-gray-500">{{ $system_currency }}</span>
                                        </div>
                                        @error('default_commission_fixed_amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                @endif

                                <!-- Calculation Base -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Calculate Commission Based On</label>
                                    <select wire:model="commission_calculation_base" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                        <option value="loan_amount">Total Loan Amount</option>
                                        <option value="interest_amount">Interest Amount</option>
                                        <option value="monthly_payment">Monthly Payment</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Commission Limits -->
                        <div class="mb-8">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Commission Limits</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Commission Amount</label>
                                    <div class="relative">
                                        <input wire:model="minimum_commission_amount" type="number" step="0.01" min="0" 
                                               class="w-full border border-gray-300 rounded-xl px-3 py-2 pl-12 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                        <span class="absolute left-3 top-2 text-gray-500">{{ $system_currency }}</span>
                                    </div>
                                    @error('minimum_commission_amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Commission Amount (Optional)</label>
                                    <div class="relative">
                                        <input wire:model="maximum_commission_amount" type="number" step="0.01" min="0" 
                                               class="w-full border border-gray-300 rounded-xl px-3 py-2 pl-12 focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                               placeholder="No limit">
                                        <span class="absolute left-3 top-2 text-gray-500">{{ $system_currency }}</span>
                                    </div>
                                    @error('maximum_commission_amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Collection Settings -->
                        <div class="mb-8">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Collection Settings</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Collection Frequency</label>
                                    <select wire:model="commission_collection_frequency" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                        <option value="per_loan">Per Loan (Immediate)</option>
                                        <option value="monthly">Monthly</option>
                                        <option value="quarterly">Quarterly</option>
                                        <option value="annually">Annually</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Due Days</label>
                                    <input wire:model="payment_due_days" type="number" min="1" max="365" 
                                           class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    @error('payment_due_days') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Auto Collection Toggle -->
                            <div class="mt-4">
                                <label class="flex items-center">
                                    <input wire:model="auto_collection_enabled" type="checkbox" 
                                           class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Enable automatic commission collection</span>
                                </label>
                            </div>
                        </div>

                        <!-- Commission Preview Calculator -->
                        <div class="mb-8 bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Commission Calculator Preview</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                                <div class="bg-white rounded-xl p-4">
                                    <p class="text-sm text-gray-600">For TSh 1,000,000 Loan</p>
                                    <p class="text-2xl font-bold text-blue-600">
                                        TSh {{ number_format($this->calculateCommissionPreview(1000000, $default_commission_type, $default_commission_percentage, $default_commission_fixed_amount)) }}
                                    </p>
                                </div>
                                <div class="bg-white rounded-xl p-4">
                                    <p class="text-sm text-gray-600">For TSh 5,000,000 Loan</p>
                                    <p class="text-2xl font-bold text-blue-600">
                                        TSh {{ number_format($this->calculateCommissionPreview(5000000, $default_commission_type, $default_commission_percentage, $default_commission_fixed_amount)) }}
                                    </p>
                                </div>
                                <div class="bg-white rounded-xl p-4">
                                    <p class="text-sm text-gray-600">For TSh 10,000,000 Loan</p>
                                    <p class="text-2xl font-bold text-blue-600">
                                        TSh {{ number_format($this->calculateCommissionPreview(10000000, $default_commission_type, $default_commission_percentage, $default_commission_fixed_amount)) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="bg-red-600 text-white px-8 py-3 rounded-xl font-semibold hover:bg-red-700 transition-all duration-200 shadow-lg shadow-red-600/25">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Save Commission Settings
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Payment Settings Tab -->
            @if($activeTab === 'payment')
                <div class="p-8">
                    <form wire:submit.prevent="savePaymentSettings">
                        <!-- Payment Configuration -->
                        <div class="mb-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-4">Payment Configuration</h3>
                            <p class="text-gray-600 mb-6">Configure payment terms, penalties, and collection settings</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Due Days</label>
                                    <input wire:model="payment_due_days" type="number" min="1" max="365" 
                                           class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    @error('payment_due_days') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Grace Period (Days)</label>
                                    <input wire:model="grace_period_days" type="number" min="0" max="30" 
                                           class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    @error('grace_period_days') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Late Payment Penalty (%)</label>
                                    <div class="relative">
                                        <input wire:model="late_payment_penalty_percentage" type="number" step="0.1" min="0" max="50" 
                                               class="w-full border border-gray-300 rounded-xl px-3 py-2 pr-8 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                        <span class="absolute right-3 top-2 text-gray-500">%</span>
                                    </div>
                                    @error('late_payment_penalty_percentage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Reminder Days Before Due</label>
                                    <input wire:model="reminder_days_before_due" type="number" min="1" max="30" 
                                           class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    @error('reminder_days_before_due') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Payment Methods -->
                        <div class="mb-8">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Accepted Payment Methods</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <label class="flex items-center p-4 border border-gray-300 rounded-xl hover:bg-gray-50 cursor-pointer">
                                    <input wire:model="selected_payment_methods" type="checkbox" value="bank_transfer" 
                                           class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">Bank Transfer</div>
                                        <div class="text-xs text-gray-500">Direct bank-to-bank transfers</div>
                                    </div>
                                </label>

                                <label class="flex items-center p-4 border border-gray-300 rounded-xl hover:bg-gray-50 cursor-pointer">
                                    <input wire:model="selected_payment_methods" type="checkbox" value="mobile_money" 
                                           class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">Mobile Money</div>
                                        <div class="text-xs text-gray-500">M-Pesa, Tigo Pesa, Airtel Money</div>
                                    </div>
                                </label>

                                <label class="flex items-center p-4 border border-gray-300 rounded-xl hover:bg-gray-50 cursor-pointer">
                                    <input wire:model="selected_payment_methods" type="checkbox" value="cash" 
                                           class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">Cash Payment</div>
                                        <div class="text-xs text-gray-500">Physical cash payments</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Notification Settings -->
                        <div class="mb-8">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Notification Settings</h4>
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Notification Email</label>
                                        <input wire:model="notification_email" type="email" 
                                               class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                               placeholder="admin@example.com">
                                        @error('notification_email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input wire:model="send_commission_notifications" type="checkbox" 
                                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-700">Send commission notifications to lenders</span>
                                    </label>

                                    <label class="flex items-center">
                                        <input wire:model="send_payment_reminders" type="checkbox" 
                                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-700">Send payment reminders before due date</span>
                                    </label>

                                    <label class="flex items-center">
                                        <input wire:model="send_overdue_notices" type="checkbox" 
                                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-700">Send overdue payment notices</span>
                                    </label>

                                    <label class="flex items-center">
                                        <input wire:model="sms_notifications_enabled" type="checkbox" 
                                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-700">Enable SMS notifications</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="bg-red-600 text-white px-8 py-3 rounded-xl font-semibold hover:bg-red-700 transition-all duration-200 shadow-lg shadow-red-600/25">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Save Payment Settings
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Business Info Tab -->
            @if($activeTab === 'business')
                <div class="p-8">
                    <form wire:submit.prevent="saveBusinessSettings">
                        <!-- Business Information -->
                        <div class="mb-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-4">Business Information</h3>
                            <p class="text-gray-600 mb-6">Configure your business details and system preferences</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Business Name *</label>
                                    <input wire:model="business_name" type="text" 
                                           class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    @error('business_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Business Email</label>
                                    <input wire:model="business_email" type="email" 
                                           class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    @error('business_email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Business Phone</label>
                                    <input wire:model="business_phone" type="text" 
                                           class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    @error('business_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Business Website</label>
                                    <input wire:model="business_website" type="url" 
                                           class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    @error('business_website') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Registration Number</label>
                                    <input wire:model="business_registration_number" type="text" 
                                           class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tax ID Number</label>
                                    <input wire:model="tax_identification_number" type="text" 
                                           class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                </div>
                            </div>

                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Business Address</label>
                                <textarea wire:model="business_address" rows="3" 
                                          class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500"></textarea>
                            </div>
                        </div>

                        <!-- System Configuration -->
                        <div class="mb-8">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">System Configuration</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">System Currency</label>
                                    <select wire:model="system_currency" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                        <option value="TZS">Tanzanian Shilling (TZS)</option>
                                        <option value="USD">US Dollar (USD)</option>
                                        <option value="EUR">Euro (EUR)</option>
                                        <option value="KES">Kenyan Shilling (KES)</option>
                                        <option value="UGX">Ugandan Shilling (UGX)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tax Rate (%)</label>
                                    <div class="relative">
                                        <input wire:model="tax_rate" type="number" step="0.1" min="0" max="100" 
                                               class="w-full border border-gray-300 rounded-xl px-3 py-2 pr-8 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                        <span class="absolute right-3 top-2 text-gray-500">%</span>
                                    </div>
                                    @error('tax_rate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Business Preview -->
                        <div class="mb-8 bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Business Information Preview</h4>
                            <div class="bg-white rounded-xl p-6">
                                <div class="text-center">
                                    <h5 class="text-xl font-bold text-gray-900">{{ $business_name ?: 'Your Business Name' }}</h5>
                                    <p class="text-gray-600 mt-2">{{ $business_address ?: 'Business Address' }}</p>
                                    <div class="flex justify-center space-x-6 mt-4 text-sm text-gray-500">
                                        <span>📧 {{ $business_email ?: 'business@example.com' }}</span>
                                        <span>📞 {{ $business_phone ?: '+255 XXX XXX XXX' }}</span>
                                        <span>💰 Currency: {{ $system_currency }}</span>
                                        <span>📊 Tax: {{ $tax_rate }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="bg-red-600 text-white px-8 py-3 rounded-xl font-semibold hover:bg-red-700 transition-all duration-200 shadow-lg shadow-red-600/25">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Save Business Settings
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>

        <!-- Lender-Specific Commission Settings -->
        @if($activeTab === 'commission')
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-white to-gray-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-1">Lender-Specific Commission Settings</h3>
                            <p class="text-gray-600">Override default settings for individual lenders</p>
                        </div>
                        <button wire:click="openLenderModal" 
                                class="bg-red-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-red-700 transition-all duration-200 shadow-lg shadow-red-600/25">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add Lender Setting
                        </button>
                    </div>
                </div>

                <!-- Lender Settings Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-8 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lender</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Commission Type</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Rate/Amount</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Limits</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($lenderCommissions as $commission)
                                <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl flex items-center justify-center shadow-md">
                                                <span class="text-white text-sm font-bold">{{ substr($commission->lender->company_name, 0, 2) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900 group-hover:text-red-600 transition-colors">{{ $commission->lender->company_name }}</div>
                                                <div class="text-xs text-gray-500">{{ $commission->lender->license_number ?: 'No License' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-6 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold
                                            {{ $commission->commission_type === 'percentage' ? 'bg-blue-100 text-blue-800 border border-blue-200' : 'bg-green-100 text-green-800 border border-green-200' }}">
                                            @if($commission->commission_type === 'percentage')
                                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                </svg>
                                                Percentage
                                            @else
                                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                                </svg>
                                                Fixed Amount
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-6 whitespace-nowrap">
                                        @if($commission->commission_type === 'percentage')
                                            <div class="text-sm font-bold text-gray-900">{{ $commission->commission_percentage }}%</div>
                                            <div class="text-xs text-gray-500">of loan amount</div>
                                        @else
                                            <div class="text-sm font-bold text-gray-900">{{ $system_currency }} {{ number_format($commission->commission_fixed_amount) }}</div>
                                            <div class="text-xs text-gray-500">per loan</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-6 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            Min: {{ $system_currency }} {{ number_format($commission->minimum_amount) }}
                                        </div>
                                <!-- @if(!is_null($commission->maximum_amount))
                                <div class="text-xs text-gray-500">
                                    Max: {{ $system_currency }} {{ number_format($commission->maximum_amount) }}
                                </div>
                            @else
                                <div class="text-xs text-gray-500">No maximum limit</div>
                            @endif -->
                                    </td>
                                    <td class="px-6 py-6 whitespace-nowrap">
                                        <button wire:click="toggleLenderStatus({{ $commission->lender_id }})"
                                            class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold cursor-pointer transition-colors
                                                {{ $commission->is_active ? 'bg-green-100 text-green-800 border border-green-200 hover:bg-green-200' : 'bg-red-100 text-red-800 border border-red-200 hover:bg-red-200' }}">
                                            <div class="w-2 h-2 rounded-full mr-2 {{ $commission->is_active ? 'bg-green-400' : 'bg-red-400' }}"></div>
                                            {{ $commission->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </td>
                                    <td class="px-6 py-6 whitespace-nowrap">
                                        <div class="flex items-center space-x-2">
                                            <button wire:click="openLenderModal({{ $commission->lender_id }})" 
                                                class="text-blue-600 hover:text-blue-700 p-2 rounded-xl hover:bg-blue-50 transition-all duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>
                                            <button wire:click="deleteLenderSetting({{ $commission->lender_id }})" 
                                                onclick="return confirm('Are you sure you want to delete this commission setting?')"
                                                class="text-red-600 hover:text-red-700 p-2 rounded-xl hover:bg-red-50 transition-all duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-8 py-12 text-center">
                                        <div class="w-20 h-20 bg-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-4">
                                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                        <h4 class="text-lg font-semibold text-gray-900 mb-2">No Custom Settings</h4>
                                        <p class="text-gray-500 mb-4">All lenders are using default commission settings.</p>
                                        <button wire:click="openLenderModal" 
                                            class="bg-red-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-red-700 transition-colors">
                                            Add First Custom Setting
                                        </button>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

      <!-- Lender Commission Modal -->
      @if($showLenderModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click.self="$set('showLenderModal', false)">
            <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-3xl shadow-lg rounded-3xl bg-white">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">
                        {{ $selectedLender ? 'Edit' : 'Add' }} Lender Commission Setting
                    </h3>
                    <button wire:click="$set('showLenderModal', false)" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveLenderSetting" class="space-y-6">
                    <!-- Select Lender -->
                    @if(!$selectedLender)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Lender *</label>
                            <select wire:model="selectedLender" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <option value="">Choose a lender...</option>
                                @foreach($lenders as $lender)
                                    @if(!$lenderCommissions->where('lender_id', $lender->id)->count())
                                        <option value="{{ $lender->id }}">{{ $lender->company_name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('selectedLender') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Commission Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Commission Type *</label>
                            <select wire:model.live="lender_commission_type" class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <option value="percentage">Percentage of Loan</option>
                                <option value="fixed">Fixed Amount per Loan</option>
                            </select>
                        </div>

                        <!-- Commission Rate/Amount -->
                        @if($lender_commission_type === 'percentage')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Commission Percentage (%) *</label>
                                <div class="relative">
                                    <input wire:model="lender_commission_percentage" type="number" step="0.1" min="0" max="100" 
                                           class="w-full border border-gray-300 rounded-xl px-3 py-2 pr-8 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <span class="absolute right-3 top-2 text-gray-500">%</span>
                                </div>
                                @error('lender_commission_percentage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        @else
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Fixed Commission Amount *</label>
                                <div class="relative">
                                    <input wire:model="lender_commission_fixed_amount" type="number" step="0.01" min="0" 
                                           class="w-full border border-gray-300 rounded-xl px-3 py-2 pl-12 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <span class="absolute left-3 top-2 text-gray-500">{{ $system_currency }}</span>
                                </div>
                                @error('lender_commission_fixed_amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <!-- Minimum Amount -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Commission Amount *</label>
                            <div class="relative">
                                <input wire:model="lender_minimum_amount" type="number" step="0.01" min="0" 
                                       class="w-full border border-gray-300 rounded-xl px-3 py-2 pl-12 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <span class="absolute left-3 top-2 text-gray-500">{{ $system_currency }}</span>
                            </div>
                            @error('lender_minimum_amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Maximum Amount -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Commission Amount (Optional)</label>
                            <div class="relative">
                                <input wire:model="lender_maximum_amount" type="number" step="0.01" min="0" 
                                       class="w-full border border-gray-300 rounded-xl px-3 py-2 pl-12 focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                       placeholder="No limit">
                                <span class="absolute left-3 top-2 text-gray-500">{{ $system_currency }}</span>
                            </div>
                            @error('lender_maximum_amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Special Terms -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Special Terms & Conditions</label>
                        <textarea wire:model="lender_special_terms" rows="3" 
                                  class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                  placeholder="Any special terms or conditions for this lender's commission..."></textarea>
                    </div>

                    <!-- Commission Preview -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Commission Preview</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                            <div class="bg-white rounded-xl p-4">
                                <p class="text-sm text-gray-600">For TSh 1,000,000 Loan</p>
                                <p class="text-xl font-bold text-green-600">
                                    TSh {{ number_format($this->calculateCommissionPreview(1000000, $lender_commission_type, $lender_commission_percentage, $lender_commission_fixed_amount)) }}
                                </p>
                            </div>
                            <div class="bg-white rounded-xl p-4">
                                <p class="text-sm text-gray-600">For TSh 5,000,000 Loan</p>
                                <p class="text-xl font-bold text-green-600">
                                    TSh {{ number_format($this->calculateCommissionPreview(5000000, $lender_commission_type, $lender_commission_percentage, $lender_commission_fixed_amount)) }}
                                </p>
                            </div>
                            <div class="bg-white rounded-xl p-4">
                                <p class="text-sm text-gray-600">For TSh 10,000,000 Loan</p>
                                <p class="text-xl font-bold text-green-600">
                                    TSh {{ number_format($this->calculateCommissionPreview(10000000, $lender_commission_type, $lender_commission_percentage, $lender_commission_fixed_amount)) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Active Status -->
                    <div class="flex items-center">
                        <input wire:model="lender_is_active" type="checkbox" id="lender_is_active" 
                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <label for="lender_is_active" class="ml-2 block text-sm text-gray-700">
                            This commission setting is active
                        </label>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4 pt-6">
                        <button type="button" wire:click="$set('showLenderModal', false)" 
                            class="bg-gray-100 text-gray-700 px-6 py-2 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                            class="bg-red-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-red-700 transition-colors">
                            {{ $selectedLender ? 'Update' : 'Create' }} Setting
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
