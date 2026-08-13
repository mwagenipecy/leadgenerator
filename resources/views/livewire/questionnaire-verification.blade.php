<div>
    <style>
        .floating-navbar-shell {
            border-radius: 1.15rem;
            background: linear-gradient(120deg,
                    rgba(255, 255, 255, 0.2),
                    rgba(255, 255, 255, 0.08));
            border: 1px solid rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.09);
            transition: all 0.3s ease;
        }

        .floating-navbar-shell.navbar-scrolled {
            background: linear-gradient(120deg,
                    rgba(255, 255, 255, 0.24),
                    rgba(255, 255, 255, 0.12));
            border-color: rgba(255, 255, 255, 0.48);
            box-shadow: 0 14px 30px rgba(0, 0, 0, 0.12);
        }

        .floating-navbar-shell .nav-scroll-link,
        .floating-navbar-shell #language-switcher-button {
            color: #C40F11;
        }

        .floating-navbar-shell.navbar-scrolled .nav-scroll-link,
        .floating-navbar-shell.navbar-scrolled #language-switcher-button {
            color: #C40F11;
        }
    </style>

    <div class="min-h-screen bg-gray-50">

        <x-landing.navbar :is-home="false" />

        <!-- Main Content -->
        <main class="max-w-4xl mx-auto pt-24 pb-12 sm:pt-26 sm:pb-14 md:pt-28 md:pb-16 px-4 sm:px-6 lg:px-8">

            <!-- Back Button -->
            <div class="mb-4 sm:mb-6">
                <button type="button" wire:click="backToMethodSelection"
                    class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>

                    Back
                </button>
            </div>

            <!-- Progress Indicator -->
            <div class="mb-6 sm:mb-8">

                <!-- Mobile -->
                <div class="md:hidden">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-medium text-gray-600">
                            Step 3 of 3
                        </span>

                        <span
                            class="text-xs font-medium
                            {{ $isVerified ? 'text-green-600' : 'text-brand-green' }}">
                            {{ $isVerified ? 'Verified' : 'Verification' }}
                        </span>
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div
                            class="h-2 rounded-full transition-all duration-500
                            {{ $isVerified ? 'bg-green-500 w-full' : 'bg-brand-green w-2/3' }}">
                        </div>
                    </div>
                </div>

                <!-- Desktop -->
                <div
                    class="hidden md:flex items-center justify-center space-x-3 lg:space-x-4 text-xs lg:text-sm text-gray-600">

                    <!-- Account -->
                    <div class="flex items-center">
                        <div
                            class="w-7 h-7 lg:w-8 lg:h-8 bg-green-500 rounded-full flex items-center justify-center mr-2">
                            <svg class="w-3 h-3 lg:w-4 lg:h-4 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>

                        <span class="hidden lg:inline">
                            Account Created
                        </span>

                        <span class="lg:hidden">
                            Account
                        </span>
                    </div>

                    <div class="w-8 lg:w-16 h-0.5 bg-gray-300"></div>

                    <!-- Method -->
                    <div class="flex items-center">
                        <div
                            class="w-7 h-7 lg:w-8 lg:h-8 bg-green-500 rounded-full flex items-center justify-center mr-2">
                            <svg class="w-3 h-3 lg:w-4 lg:h-4 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>

                        <span class="hidden lg:inline">
                            Method Selected
                        </span>

                        <span class="lg:hidden">
                            Method
                        </span>
                    </div>

                    <div class="w-8 lg:w-16 h-0.5 bg-gray-300"></div>

                    <!-- Verification -->
                    <div class="flex items-center">
                        <div
                            class="w-7 h-7 lg:w-8 lg:h-8 rounded-full flex items-center justify-center mr-2
                            {{ $isVerified ? 'bg-green-500' : 'bg-brand-green' }}">
                            @if ($isVerified)
                                <svg class="w-3 h-3 lg:w-4 lg:h-4 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            @else
                                <span class="text-white font-medium text-xs lg:text-sm">
                                    3
                                </span>
                            @endif
                        </div>

                        <span
                            class="{{ $isVerified ? 'text-green-600' : '' }}
                            font-medium hidden lg:inline">
                            NIDA Verification
                        </span>

                        <span
                            class="{{ $isVerified ? 'text-green-600' : '' }}
                            font-medium lg:hidden">
                            Verification
                        </span>
                    </div>

                </div>
            </div>

            <!-- Header -->
            <div class="text-center mb-6 sm:mb-8">

                <div
                    class="mx-auto w-16 h-16 sm:w-20 sm:h-20 bg-brand-green/10 rounded-2xl flex items-center justify-center mb-4 sm:mb-6">

                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-brand-green" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>

                </div>

                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 sm:mb-4 px-2">
                    NIDA Verification
                </h1>

                <p class="text-sm sm:text-base lg:text-lg text-gray-600 max-w-2xl mx-auto px-4">
                    Answer the security questions provided by NIDA to verify your identity.
                </p>

            </div>

            <!-- Verification Content -->
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100 overflow-hidden">

                <div class="p-4 sm:p-6 md:p-8">

                    {{-- =====================================================
                         SUCCESS
                    ====================================================== --}}
                    @if ($isVerified)

                        <div class="text-center py-6 sm:py-8">

                            <div
                                class="w-16 h-16 sm:w-20 sm:h-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">

                                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>

                            </div>

                            <h2 class="text-xl sm:text-2xl font-bold text-green-800 mb-2 px-4">
                                Verification Complete!
                            </h2>

                            <p class="text-sm sm:text-base text-green-700 mb-6 px-4">
                                {{ $successMessage }}
                            </p>

                            <div class="space-y-3">

                                <button wire:click="goToProfile"
                                    class="w-full bg-brand-green text-white px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg text-sm sm:text-base font-semibold hover:bg-brand-green-light transition-colors">
                                    Complete Your Profile
                                </button>

                                <button wire:click="redirectToDashboard"
                                    class="w-full bg-gray-600 text-white px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg text-sm sm:text-base font-semibold hover:bg-gray-700 transition-colors">
                                    Go to Dashboard
                                </button>

                            </div>

                        </div>

                        {{-- =====================================================
                         FAILURE
                    ====================================================== --}}
                    @elseif($showFinalResult && $errorMessage)
                        <div class="text-center py-6 sm:py-8">

                            <div
                                class="w-16 h-16 sm:w-20 sm:h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">

                                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-red-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>

                            </div>

                            <h2 class="text-xl sm:text-2xl font-bold text-red-800 mb-2 px-4">
                                Verification Failed
                            </h2>

                            <p class="text-sm sm:text-base text-red-700 mb-6 px-4">
                                {{ $errorMessage }}
                            </p>

                            <button wire:click="retryVerification" wire:loading.attr="disabled"
                                class="w-full bg-brand-green text-white px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg text-sm sm:text-base font-semibold hover:bg-brand-green-light transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                <span wire:loading.remove wire:target="retryVerification">
                                    Try Again
                                </span>

                                <span wire:loading wire:target="retryVerification"
                                    class="flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>

                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>

                                    Starting...
                                </span>
                            </button>

                        </div>

                        {{-- =====================================================
                         NIDA QUESTION
                    ====================================================== --}}
                    @else
                        <div>

                            {{-- NIDA does not tell us how many questions
                                 will be asked, therefore don't show a
                                 percentage or "Question X of Y". --}}
                            <div class="mb-6">

                                <div class="flex justify-between items-center text-xs sm:text-sm text-gray-600 mb-2">

                                    <span class="font-medium">
                                        @if ($answeredQuestions > 0)
                                            {{ $answeredQuestions }} question{{ $answeredQuestions === 1 ? '' : 's' }}
                                            answered
                                        @else
                                            Verification Question
                                        @endif
                                    </span>

                                    <span class="font-medium text-brand-green">
                                        NIDA
                                    </span>

                                </div>

                                <div class="w-full bg-gray-200 rounded-full h-1.5 sm:h-2">
                                    <div class="bg-brand-green h-1.5 sm:h-2 rounded-full transition-all duration-500"
                                        style="width: 100%"></div>
                                </div>

                            </div>

                            {{-- Error message that does not represent
                                 the final verification failure state. --}}
                            @if ($errorMessage && !$showFinalResult)
                                <div class="mb-5 rounded-lg bg-red-50 border border-red-200 p-3 sm:p-4">

                                    <div class="flex items-start">

                                        <svg class="w-5 h-5 text-red-600 mr-2 flex-shrink-0 mt-0.5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>

                                        <p class="text-xs sm:text-sm text-red-700">
                                            {{ $errorMessage }}
                                        </p>

                                    </div>

                                </div>
                            @endif

                            {{-- Current question --}}
                            @if ($questionEn || $questionSw)

                                <form wire:key="nida-question-form-{{ $rqCode }}" wire:submit.prevent="submitCurrentQuestion" class="space-y-5 sm:space-y-6">

                                    <!-- Question -->
                                    <div class="text-center mb-5 sm:mb-6">

                                        @if ($questionEn)
                                            <h3
                                                class="text-base sm:text-lg md:text-xl font-semibold text-gray-900 mb-3 px-2">
                                                {{ $questionEn }}

                                                <span class="text-red-500">*</span>
                                            </h3>
                                        @endif

                                        @if ($questionSw)
                                            <div class="rounded-lg bg-gray-50 border border-gray-200 px-4 py-3 mx-2">

                                                <p class="text-xs sm:text-sm text-gray-600 italic">
                                                    {{ $questionSw }}
                                                </p>

                                            </div>
                                        @endif

                                    </div>

                                    <!-- Answer -->
                                    <div>

                                        <label for="currentAnswer"
                                            class="block text-sm font-medium text-gray-700 mb-2">
                                            Your Answer
                                        </label>

                                        <input id="currentAnswer" type="text" wire:model.live="currentAnswer"
                                            autocomplete="off" maxlength="255" autofocus
                                            placeholder="{{ $this->getCurrentQuestionPlaceholder() }}"
                                            class="w-full px-3 py-2.5 sm:px-4 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-brand-green focus:border-brand-green @error('currentAnswer') border-red-500 @enderror" />

                                        @error('currentAnswer')
                                            <p class="mt-1 text-xs sm:text-sm text-red-600">
                                                {{ $message }}
                                            </p>
                                        @enderror

                                    </div>

                                    <!-- Helper -->
                                    <div class="rounded-lg bg-blue-50 border border-blue-100 p-3 sm:p-4">

                                        <div class="flex items-start">

                                            <svg class="w-5 h-5 text-blue-600 mr-2 flex-shrink-0 mt-0.5"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                                            </svg>

                                            <p class="text-xs sm:text-sm text-blue-700">
                                                {{ $this->getQuestionHelper() }}
                                            </p>

                                        </div>

                                    </div>

                                    <!-- Submit -->
                                    <div class="mt-6 sm:mt-8">

                                        <button type="submit" wire:loading.attr="disabled"
                                            @disabled($isProcessing)
                                            class="w-full flex justify-center py-2.5 sm:py-3 px-4 sm:px-6 border border-transparent rounded-lg shadow-sm text-sm sm:text-base font-semibold text-white bg-brand-green hover:bg-brand-green-light focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-green disabled:opacity-50 disabled:cursor-not-allowed transition-colors">

                                            <span wire:loading.remove wire:target="submitCurrentQuestion">
                                                <span class="hidden sm:inline">
                                                    Submit Answer
                                                </span>

                                                <span class="sm:hidden">
                                                    Submit
                                                </span>
                                            </span>

                                            <span wire:loading wire:target="submitCurrentQuestion"
                                                class="flex items-center">
                                                <svg class="animate-spin -ml-1 mr-2 sm:mr-3 h-4 w-4 sm:h-5 sm:w-5 text-white"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>

                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>

                                                Verifying...
                                            </span>

                                        </button>

                                    </div>

                                </form>
                            @elseif($isProcessing)
                                <!-- Loading first question -->
                                <div class="text-center py-8 sm:py-10">

                                    <div
                                        class="animate-spin rounded-full h-10 w-10 border-b-2 border-brand-green mx-auto mb-4">
                                    </div>

                                    <p class="text-sm sm:text-base text-gray-600">
                                        Connecting to NIDA and loading your verification question...
                                    </p>

                                </div>
                            @else
                                <!-- No question available -->
                                <div class="text-center py-8">

                                    <div
                                        class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">

                                        <svg class="w-7 h-7 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.73-3L13.73 4a2 2 0 00-3.46 0L3.34 16a2 2 0 001.73 3z" />
                                        </svg>

                                    </div>

                                    <p class="text-sm text-gray-600 mb-4">
                                        Unable to load a NIDA verification question.
                                    </p>

                                    <button wire:click="retryVerification"
                                        class="bg-brand-green text-white px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-brand-green-light transition-colors">
                                        Try Again
                                    </button>

                                </div>

                            @endif

                        </div>

                    @endif

                </div>
            </div>

            <!-- Security Notice -->
            <div class="mt-6 sm:mt-8 text-center px-2">

                <div
                    class="inline-flex items-center space-x-2 text-xs sm:text-sm text-gray-500 bg-white px-3 sm:px-4 py-2 rounded-lg border border-gray-200">

                    <svg class="w-3 h-3 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>

                    <span class="text-center">
                        Your data is encrypted and securely processed
                    </span>

                </div>

            </div>

        </main>

        <div class="mt-4 md:mt-6">
            <x-landing.footer />
        </div>

        <!-- Loading Overlay -->
        <div wire:loading.flex wire:target="submitCurrentQuestion,retryVerification,goToProfile,redirectToDashboard"
            class="fixed inset-0 bg-gray-900 bg-opacity-50 items-center justify-center z-50">

            <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4">

                <div class="text-center">

                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-brand-green mx-auto mb-4"></div>

                    <p class="text-gray-600">
                        Verifying your identity...
                    </p>

                </div>

            </div>

        </div>

    </div>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');

            if (!menu) {
                return;
            }

            menu.classList.toggle('hidden');
        }

        const handleNavbarScroll = () => {
            const navbar = document.getElementById('mainNavbar');

            const navbarShell = navbar ?
                navbar.querySelector('.floating-navbar-shell') :
                null;

            if (!navbarShell) {
                return;
            }

            if (window.scrollY > 20) {
                navbarShell.classList.add('navbar-scrolled');
            } else {
                navbarShell.classList.remove('navbar-scrolled');
            }
        };

        window.addEventListener('scroll', handleNavbarScroll);

        document.addEventListener(
            'DOMContentLoaded',
            handleNavbarScroll
        );

        document.addEventListener('click', function(event) {
            const menu = document.getElementById('mobileMenu');

            if (!menu) {
                return;
            }

            const button = event.target.closest(
                'button[onclick="toggleMobileMenu()"]'
            );

            if (
                !menu.contains(event.target) &&
                !button &&
                !menu.classList.contains('hidden')
            ) {
                menu.classList.add('hidden');
            }
        });
    </script>
</div>
