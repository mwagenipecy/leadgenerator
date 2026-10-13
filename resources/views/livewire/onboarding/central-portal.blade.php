@php
    $headings = [
        'phone' => [__('auth.login_or_register'), __('auth.central_phone_subtitle')],
        'direct_login' => [__('auth.welcome_back'), __('auth.central_direct_login_subtitle')],
        'otp' => [__('auth.verify_identity'), __('auth.otp_sent_to') . ' ' . $phone],
        'login' => [__('auth.welcome_back'), __('auth.central_login_subtitle')],
        'nin' => [__('auth.verify_identity'), __('auth.central_nin_subtitle')],
        'nin_qa' => [__('auth.central_nin_qa_title'), __('auth.central_nin_qa_subtitle')],
        'set_password' => [
            __('auth.central_set_password_title'),
            $nextStep === 'NEW_USER' ? __('auth.central_set_password_new_user') : __('auth.central_set_password_onboard'),
        ],
        'blocked' => [__('auth.central_blocked_title'), ''],
    ];

    [$pageHeading, $pageSubheading] = $headings[$step] ?? [__('auth.login_or_register'), ''];
@endphp

<div>
    <!-- Header -->
    <div class="text-center mb-8">
        <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-3">{{ $pageHeading }}</h2>
        @if ($pageSubheading)
            <p class="text-gray-600">{{ $pageSubheading }}</p>
        @endif
    </div>

    <!-- Card -->
    <div class="bg-white rounded-2xl p-8 border border-gray-100">

        @if ($infoMessage)
            <div class="mb-4 rounded-lg bg-blue-50 border border-blue-200 text-blue-700 text-sm px-4 py-3">{{ $infoMessage }}</div>
        @endif

        @if ($errorMessage)
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">{{ $errorMessage }}</div>
        @endif

        {{-- STEP: phone number entry (first time / new user) --}}
        @if ($step === 'phone')
            <form wire:submit.prevent="sendPhoneOtp" class="space-y-6">
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">{{ __('auth.phone') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <input
                            id="phone"
                            type="tel"
                            inputmode="tel"
                            wire:model="phone"
                            required
                            autofocus
                            maxlength="13"
                            pattern="^\+?[0-9]{9,13}$"
                            title="{{ __('auth.phone') }}"
                            placeholder="07XXXXXXXX"
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-transparent placeholder-gray-400 sm:text-sm"
                        >
                    </div>
                    @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <button type="submit" wire:loading.attr="disabled" wire:target="sendPhoneOtp"
                            class="w-full bg-brand-red text-white py-3 rounded-lg font-semibold hover:bg-brand-dark-red focus:ring-4 focus:ring-brand-red/30 transition-all duration-300 transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">
                        <span wire:loading.remove wire:target="sendPhoneOtp">{{ __('auth.continue') }}</span>
                        <span wire:loading wire:target="sendPhoneOtp">{{ __('auth.sending_code') }}</span>
                    </button>
                </div>

                <button type="button" wire:click="switchToDirectLogin"
                        class="w-full text-sm text-brand-red hover:text-brand-dark-red transition-colors text-center">
                    {{ __('auth.has_web_password') }}
                </button>
            </form>
        @endif

        {{-- STEP: "Has web access" shortcut - direct phone + password login --}}
        @if ($step === 'direct_login')
            <form wire:submit.prevent="directLogin" class="space-y-6">
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">{{ __('auth.phone') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <input
                            id="phone"
                            type="tel"
                            inputmode="tel"
                            wire:model="phone"
                            required
                            autofocus
                            maxlength="13"
                            pattern="^\+?[0-9]{9,13}$"
                            placeholder="07XXXXXXXX"
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-transparent placeholder-gray-400 sm:text-sm"
                        >
                    </div>
                    @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="direct_password" class="block text-sm font-medium text-gray-700 mb-2">{{ __('auth.password') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input
                            id="direct_password"
                            type="password"
                            wire:model="password"
                            required
                            maxlength="255"
                            class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-transparent placeholder-gray-400 sm:text-sm"
                        >
                        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePassword('direct_password')">
                            <svg id="eye-icon-direct_password" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <button type="submit" wire:loading.attr="disabled" wire:target="directLogin"
                            class="w-full bg-brand-red text-white py-3 rounded-lg font-semibold hover:bg-brand-dark-red focus:ring-4 focus:ring-brand-red/30 transition-all duration-300 transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">
                        {{ __('auth.login') }}
                    </button>
                </div>

                <button type="button" wire:click="switchToPhoneEntry" class="w-full text-sm text-gray-600 hover:text-brand-red transition-colors text-center">
                    ← {{ __('common.back') }}
                </button>
            </form>
        @endif

        {{-- STEP: OTP entry --}}
        @if ($step === 'otp')
            <form wire:submit.prevent="verifyOtp" class="space-y-6">
                <div>
                    <label for="otp" class="block text-sm font-medium text-gray-700 mb-4 text-center">
                        {{ __('auth.verification_code') }}
                    </label>
                    <input
                        id="otp"
                        type="text"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        maxlength="6"
                        wire:model="otp"
                        required
                        autofocus
                        placeholder="123456"
                        class="w-full rounded-lg border border-gray-300 tracking-[0.5em] text-center text-xl font-bold py-3 focus:border-brand-red focus:ring-2 focus:ring-brand-red/30 focus:outline-none"
                    >
                    @error('otp') <p class="mt-1 text-sm text-red-600 text-center">{{ $message }}</p> @enderror
                </div>

                <div>
                    <button type="submit" wire:loading.attr="disabled" wire:target="verifyOtp"
                            class="w-full bg-brand-red text-white py-3 rounded-lg font-semibold hover:bg-brand-dark-red focus:ring-4 focus:ring-brand-red/30 transition-all duration-300 transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">
                        <span wire:loading.remove wire:target="verifyOtp">{{ __('auth.verify_code') }}</span>
                        <span wire:loading wire:target="verifyOtp">{{ __('auth.verifying') }}</span>
                    </button>
                </div>

                <div class="flex justify-between text-sm">
                    <button type="button" wire:click="switchToPhoneEntry" class="text-gray-600 hover:text-brand-red transition-colors">
                        {{ __('auth.change_number') }}
                    </button>
                    <button type="button" wire:click="resendOtp" wire:loading.attr="disabled" wire:target="resendOtp" class="font-medium text-brand-red hover:text-brand-dark-red transition-colors disabled:opacity-50">
                        {{ __('auth.resend_code') }}
                    </button>
                </div>
            </form>
        @endif

        {{-- STEP: LOGIN branch - existing web user --}}
        @if ($step === 'login')
            <form wire:submit.prevent="loginWithPassword" class="space-y-6">
                <div>
                    <label for="login_password" class="block text-sm font-medium text-gray-700 mb-2">{{ __('auth.password') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input
                            id="login_password"
                            type="password"
                            wire:model="password"
                            required
                            autofocus
                            maxlength="255"
                            class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-transparent placeholder-gray-400 sm:text-sm"
                        >
                        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePassword('login_password')">
                            <svg id="eye-icon-login_password" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <button type="submit" wire:loading.attr="disabled" wire:target="loginWithPassword"
                            class="w-full bg-brand-red text-white py-3 rounded-lg font-semibold hover:bg-brand-dark-red focus:ring-4 focus:ring-brand-red/30 transition-all duration-300 transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">
                        {{ __('auth.login') }}
                    </button>
                </div>
            </form>
        @endif

        {{-- STEP: NEW_USER branch - NIN entry --}}
        @if ($step === 'nin')
            <form wire:submit.prevent="startNinVerification" class="space-y-6">
                <div>
                    <label for="nin" class="block text-sm font-medium text-gray-700 mb-2">{{ __('auth.nin_label') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V7a2 2 0 00-2-2H4zm0 2h16v10H4V7zm2 2v2h4V9H6zm0 4v2h8v-2H6z"></path>
                            </svg>
                        </div>
                        <input
                            id="nin"
                            type="text"
                            inputmode="numeric"
                            pattern="[0-9]*"
                            maxlength="20"
                            wire:model="nin"
                            required
                            autofocus
                            placeholder="19900101XXXXXXXXXX"
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-transparent placeholder-gray-400 sm:text-sm"
                        >
                    </div>
                    @error('nin') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <button type="submit" wire:loading.attr="disabled" wire:target="startNinVerification"
                            class="w-full bg-brand-red text-white py-3 rounded-lg font-semibold hover:bg-brand-dark-red focus:ring-4 focus:ring-brand-red/30 transition-all duration-300 transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">
                        {{ __('auth.continue') }}
                    </button>
                </div>
            </form>
        @endif

        {{-- STEP: NEW_USER branch - NIN question & answer --}}
        @if ($step === 'nin_qa')
            <form wire:submit.prevent="submitNinAnswer" class="space-y-6">
                <div class="rounded-lg bg-gray-50 border border-gray-100 px-4 py-3">
                    <p class="text-sm font-medium text-gray-800">{{ $questionEn }}</p>
                    @if ($questionSw)
                        <p class="text-sm text-gray-500 mt-1">{{ $questionSw }}</p>
                    @endif
                </div>

                <div>
                    <label for="answer" class="block text-sm font-medium text-gray-700 mb-2">{{ __('auth.your_answer') }}</label>
                    <input
                        id="answer"
                        type="text"
                        wire:model="answer"
                        required
                        autofocus
                        maxlength="255"
                        class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-transparent placeholder-gray-400 sm:text-sm"
                    >
                    @error('answer') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <button type="submit" wire:loading.attr="disabled" wire:target="submitNinAnswer"
                            class="w-full bg-brand-red text-white py-3 rounded-lg font-semibold hover:bg-brand-dark-red focus:ring-4 focus:ring-brand-red/30 transition-all duration-300 transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">
                        {{ __('auth.submit_answer') }}
                    </button>
                </div>

                <p class="text-xs text-gray-400 text-center">{{ __('auth.attempt') }} {{ $ninAttempts + 1 }} {{ __('common.of') }} 3</p>
            </form>
        @endif

        {{-- STEP: set password - shared by NEW_USER and ONBOARD_CHANNEL --}}
        @if ($step === 'set_password')
            <form wire:submit.prevent="setPassword" class="space-y-6">
                <div>
                    <label for="newPassword" class="block text-sm font-medium text-gray-700 mb-2">{{ __('auth.new_password') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input
                            id="newPassword"
                            type="password"
                            wire:model="newPassword"
                            required
                            autofocus
                            minlength="8"
                            maxlength="255"
                            pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}"
                            title="{{ __('auth.password_requirements_complex') }}"
                            class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-transparent placeholder-gray-400 sm:text-sm"
                        >
                        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePassword('newPassword')">
                            <svg id="eye-icon-newPassword" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    @error('newPassword') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    <p class="mt-1 text-xs text-gray-500">{{ __('auth.password_requirements_complex') }}</p>
                </div>

                <div>
                    <label for="newPasswordConfirmation" class="block text-sm font-medium text-gray-700 mb-2">{{ __('auth.confirm_new_password') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input
                            id="newPasswordConfirmation"
                            type="password"
                            wire:model="newPasswordConfirmation"
                            required
                            minlength="8"
                            maxlength="255"
                            class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-red focus:border-transparent placeholder-gray-400 sm:text-sm"
                        >
                        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePassword('newPasswordConfirmation')">
                            <svg id="eye-icon-newPasswordConfirmation" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    @error('newPasswordConfirmation') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <button type="submit" wire:loading.attr="disabled" wire:target="setPassword"
                            class="w-full bg-brand-red text-white py-3 rounded-lg font-semibold hover:bg-brand-dark-red focus:ring-4 focus:ring-brand-red/30 transition-all duration-300 transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">
                        {{ __('auth.finish') }}
                    </button>
                </div>
            </form>
        @endif

        {{-- STEP: blocked / contact support --}}
        @if ($step === 'blocked')
            <div class="text-center space-y-4">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-brand-red/10 rounded-full mb-2">
                    <svg class="w-8 h-8 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-sm text-gray-600">{{ $infoMessage ?: __('auth.central_blocked_default') }}</p>

                <a href="{{ route('customer-help.index') }}"
                   class="inline-block w-full bg-brand-red hover:bg-brand-dark-red text-white font-medium py-3 rounded-lg transition-all duration-300 transform hover:scale-[1.02]">
                    {{ __('auth.contact_support') }}
                </a>

                <button type="button" wire:click="switchToPhoneEntry" class="w-full text-sm text-gray-600 hover:text-brand-red transition-colors">
                    {{ __('auth.start_over') }}
                </button>
            </div>
        @endif

    </div>

    <!-- Security Notice -->
    <div class="mt-6 text-center">
        <div class="flex items-center justify-center space-x-2 text-sm text-gray-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
            <span>Your data is protected with 256-bit SSL encryption</span>
        </div>
    </div>

    <!-- Lender / Admin Login -->
    <p class="text-center text-sm text-gray-500 mt-4">
        <a href="{{ route('login') }}" class="text-brand-red hover:text-brand-dark-red transition-colors">{{ __('auth.lender_admin_login') }}</a>
    </p>

    <!-- Powered By -->
    <div class="mt-6 text-center">
        <p class="text-xs text-gray-400">Powered by CreditInfo</p>
    </div>
</div>
