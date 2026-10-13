<div>
    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Email Address *</label>

    @if ($step === 'idle')
        <div class="flex gap-2">
            <input wire:model="email" type="email" wire:keydown.enter.prevent="submitEmail"
                   class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
            <button type="button" wire:click="submitEmail" wire:loading.attr="disabled" wire:target="submitEmail"
                    class="shrink-0 px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base rounded-lg bg-sidebar-green text-white hover:opacity-90 disabled:opacity-50">
                <span wire:loading.remove wire:target="submitEmail">Verify</span>
                <span wire:loading wire:target="submitEmail">Sending…</span>
            </button>
        </div>
        @error('email') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
    @else
        <div class="rounded-lg border border-gray-300 p-3 sm:p-4 space-y-3">
            <p class="text-sm text-gray-600">Enter the 6-digit code sent to <span class="font-medium text-gray-900">{{ $email }}</span>.</p>
            <input wire:model="otp" type="text" inputmode="numeric" maxlength="6" placeholder="000000" wire:keydown.enter.prevent="confirmOtp"
                   class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
            @error('otp') <span class="text-sidebar-green text-sm block">{{ $message }}</span> @enderror
            <div class="flex items-center gap-3">
                <button type="button" wire:click="confirmOtp" wire:loading.attr="disabled" wire:target="confirmOtp"
                        class="px-3 sm:px-4 py-2 text-sm rounded-lg bg-sidebar-green text-white hover:opacity-90 disabled:opacity-50">
                    <span wire:loading.remove wire:target="confirmOtp">Confirm</span>
                    <span wire:loading wire:target="confirmOtp">Checking…</span>
                </button>
                <button type="button" wire:click="resendCode" wire:loading.attr="disabled" wire:target="resendCode"
                        class="text-sm text-sidebar-green hover:underline">Resend code</button>
                <button type="button" wire:click="cancel" class="text-sm text-gray-500 hover:underline">Cancel</button>
            </div>
        </div>
    @endif

    @if ($infoMessage)
        <p class="text-xs sm:text-sm text-blue-600 mt-1">{{ $infoMessage }}</p>
    @endif
    @if ($errorMessage)
        <p class="text-xs sm:text-sm text-red-600 mt-1">{{ $errorMessage }}</p>
    @endif
</div>
