<div class="min-h-screen flex items-center justify-center bg-gray-50 px-4 py-10">
    <div class="w-full max-w-md">
        <div class="text-center mb-6">
            <img src="{{ asset('landing/applicationIcon.png') }}" alt="Fanikisha" class="h-12 mx-auto mb-3">
            <h1 class="text-xl font-semibold text-gray-900">{{ __('auth.login_or_register') ?? 'Log in or create an account' }}</h1>
            <p class="text-sm text-gray-500 mt-1">We verify every account by phone number for your security.</p>
        </div>

        <div class="bg-white shadow rounded-xl p-6 sm:p-8">

            @if ($infoMessage)
                <div class="mb-4 rounded-lg bg-blue-50 text-blue-700 text-sm px-4 py-3">{{ $infoMessage }}</div>
            @endif

            @if ($errorMessage)
                <div class="mb-4 rounded-lg bg-red-50 text-red-700 text-sm px-4 py-3">{{ $errorMessage }}</div>
            @endif

            {{-- STEP: phone number entry (first time / new user) --}}
            @if ($step === 'phone')
                <form wire:submit.prevent="sendPhoneOtp" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone number</label>
                        <input type="tel" wire:model="phone" placeholder="07XXXXXXXX"
                               class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red">
                        @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" wire:loading.attr="disabled"
                            class="w-full bg-brand-red hover:bg-brand-dark-red text-white font-medium py-2.5 rounded-lg transition">
                        <span wire:loading.remove>Continue</span>
                        <span wire:loading>Sending code…</span>
                    </button>

                    <button type="button" wire:click="switchToDirectLogin"
                            class="w-full text-sm text-brand-red hover:underline text-center">
                        Already have a web password? Log in directly
                    </button>
                </form>
            @endif

            {{-- STEP: "Has web access" shortcut - direct phone + password login --}}
            @if ($step === 'direct_login')
                <form wire:submit.prevent="directLogin" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone number</label>
                        <input type="tel" wire:model="phone" placeholder="07XXXXXXXX"
                               class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red">
                        @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" wire:model="password"
                               class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red">
                        @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" wire:loading.attr="disabled"
                            class="w-full bg-brand-red hover:bg-brand-dark-red text-white font-medium py-2.5 rounded-lg transition">
                        Log in
                    </button>

                    <button type="button" wire:click="switchToPhoneEntry"
                            class="w-full text-sm text-gray-500 hover:underline text-center">
                        Back
                    </button>
                </form>
            @endif

            {{-- STEP: OTP entry --}}
            @if ($step === 'otp')
                <form wire:submit.prevent="verifyOtp" class="space-y-4">
                    <p class="text-sm text-gray-600">Enter the 6-digit code sent to <span class="font-medium">{{ $phone }}</span>.</p>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Verification code</label>
                        <input type="text" inputmode="numeric" maxlength="6" wire:model="otp" placeholder="123456"
                               class="w-full rounded-lg border-gray-300 tracking-widest text-center focus:border-brand-red focus:ring-brand-red">
                        @error('otp') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" wire:loading.attr="disabled"
                            class="w-full bg-brand-red hover:bg-brand-dark-red text-white font-medium py-2.5 rounded-lg transition">
                        Verify
                    </button>

                    <div class="flex justify-between text-sm">
                        <button type="button" wire:click="switchToPhoneEntry" class="text-gray-500 hover:underline">Change number</button>
                        <button type="button" wire:click="resendOtp" class="text-brand-red hover:underline">Resend code</button>
                    </div>
                </form>
            @endif

            {{-- STEP: LOGIN branch - existing web user --}}
            @if ($step === 'login')
                <form wire:submit.prevent="loginWithPassword" class="space-y-4">
                    <p class="text-sm text-gray-600">Welcome back! Enter your password to continue.</p>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" wire:model="password"
                               class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red">
                        @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" wire:loading.attr="disabled"
                            class="w-full bg-brand-red hover:bg-brand-dark-red text-white font-medium py-2.5 rounded-lg transition">
                        Log in
                    </button>
                </form>
            @endif

            {{-- STEP: NEW_USER branch - NIN entry --}}
            @if ($step === 'nin')
                <form wire:submit.prevent="startNinVerification" class="space-y-4">
                    <p class="text-sm text-gray-600">Let's verify your identity. Enter your 20-digit NIDA number (NIN).</p>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIDA number (NIN)</label>
                        <input type="text" inputmode="numeric" maxlength="20" wire:model="nin"
                               class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red">
                        @error('nin') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" wire:loading.attr="disabled"
                            class="w-full bg-brand-red hover:bg-brand-dark-red text-white font-medium py-2.5 rounded-lg transition">
                        Continue
                    </button>
                </form>
            @endif

            {{-- STEP: NEW_USER branch - NIN question & answer --}}
            @if ($step === 'nin_qa')
                <form wire:submit.prevent="submitNinAnswer" class="space-y-4">
                    <div class="rounded-lg bg-gray-50 px-4 py-3">
                        <p class="text-sm font-medium text-gray-800">{{ $questionEn }}</p>
                        @if ($questionSw)
                            <p class="text-sm text-gray-500 mt-1">{{ $questionSw }}</p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Your answer</label>
                        <input type="text" wire:model="answer"
                               class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red">
                        @error('answer') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" wire:loading.attr="disabled"
                            class="w-full bg-brand-red hover:bg-brand-dark-red text-white font-medium py-2.5 rounded-lg transition">
                        Submit answer
                    </button>

                    <p class="text-xs text-gray-400 text-center">Attempt {{ $ninAttempts + 1 }} of 3</p>
                </form>
            @endif

            {{-- STEP: set password - shared by NEW_USER and ONBOARD_CHANNEL --}}
            @if ($step === 'set_password')
                <form wire:submit.prevent="setPassword" class="space-y-4">
                    <p class="text-sm text-gray-600">
                        @if ($nextStep === 'NEW_USER')
                            Your identity is verified. Set a password to finish creating your account.
                        @else
                            Your phone is verified. Set a password for web access.
                        @endif
                    </p>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">New password</label>
                        <input type="password" wire:model="newPassword"
                               class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red">
                        @error('newPassword') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        <p class="mt-1 text-xs text-gray-400">At least 8 characters, with an uppercase letter, a lowercase letter, and a number.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm password</label>
                        <input type="password" wire:model="newPasswordConfirmation"
                               class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red">
                        @error('newPasswordConfirmation') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" wire:loading.attr="disabled"
                            class="w-full bg-brand-red hover:bg-brand-dark-red text-white font-medium py-2.5 rounded-lg transition">
                        Finish
                    </button>
                </form>
            @endif

            {{-- STEP: blocked / contact support --}}
            @if ($step === 'blocked')
                <div class="text-center space-y-4">
                    <div class="mx-auto h-12 w-12 rounded-full bg-red-50 flex items-center justify-center">
                        <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-600">{{ $infoMessage ?: 'We could not complete this request. Please contact support.' }}</p>

                    <a href="{{ route('customer-help.index') }}"
                       class="inline-block w-full bg-brand-red hover:bg-brand-dark-red text-white font-medium py-2.5 rounded-lg transition">
                        Contact support
                    </a>

                    <button type="button" wire:click="switchToPhoneEntry" class="w-full text-sm text-gray-500 hover:underline">
                        Start over
                    </button>
                </div>
            @endif

        </div>

        <p class="text-center text-sm text-gray-500 mt-6">
            <a href="{{ route('login') }}" class="text-brand-red hover:underline">Lender or admin? Log in here</a>
        </p>
    </div>
</div>
