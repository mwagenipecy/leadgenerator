<div>
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-14 sm:h-16">
                <a href="/" class="inline-block">
                    <img src="{{ asset('logo/logoOnWhitebg.png') }}" alt="Logo" class="h-8 sm:h-10 md:h-12 w-auto">
                </a>
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <span class="hidden sm:inline text-xs sm:text-sm text-gray-500">Welcome, {{ auth()->user()?->first_name ?? 'User' }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-xs sm:text-sm text-brand-green hover:text-brand-green-light transition-colors font-medium">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto py-4 sm:py-6 md:py-8 px-4 sm:px-6 lg:px-8">
        <!-- Progress Indicator -->
        <div class="mb-6 sm:mb-8">
            <!-- Mobile Progress - Simplified -->
            <div class="md:hidden">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-gray-600">Step 3 of 3</span>
                    <span class="text-xs font-medium text-brand-green">Questionnaire</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-brand-green h-2 rounded-full" style="width: 100%"></div>
                </div>
            </div>

            <!-- Desktop Progress - Full View -->
            <div class="hidden md:flex items-center justify-center space-x-3 lg:space-x-4 text-xs lg:text-sm text-gray-600">
                <div class="flex items-center">
                    <div class="w-7 h-7 lg:w-8 lg:h-8 bg-green-500 rounded-full flex items-center justify-center mr-2">
                        <svg class="w-3 h-3 lg:w-4 lg:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span class="hidden lg:inline">Account Created</span>
                    <span class="lg:hidden">Account</span>
                </div>
                <div class="w-8 lg:w-16 h-0.5 bg-gray-300"></div>
                <div class="flex items-center">
                    <div class="w-7 h-7 lg:w-8 lg:h-8 bg-green-500 rounded-full flex items-center justify-center mr-2">
                        <svg class="w-3 h-3 lg:w-4 lg:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span class="hidden lg:inline">Method Selected</span>
                    <span class="lg:hidden">Method</span>
                </div>
                <div class="w-8 lg:w-16 h-0.5 bg-gray-300"></div>
                <div class="flex items-center">
                    <div class="w-7 h-7 lg:w-8 lg:h-8 {{ $isVerified ? 'bg-green-500' : 'bg-brand-green' }} rounded-full flex items-center justify-center mr-2">
                        @if($isVerified)
                            <svg class="w-3 h-3 lg:w-4 lg:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        @else
                            <span class="text-white font-medium text-xs lg:text-sm">3</span>
                        @endif
                    </div>
                    <span class="{{ $isVerified ? 'text-green-600' : '' }} font-medium hidden lg:inline">Questionnaire Verification</span>
                    <span class="{{ $isVerified ? 'text-green-600' : '' }} font-medium lg:hidden">Questionnaire</span>
                </div>
            </div>
        </div>

        <!-- Header Section -->
        <div class="text-center mb-6 sm:mb-8">
            <div class="mx-auto w-16 h-16 sm:w-20 sm:h-20 bg-brand-green/10 rounded-2xl flex items-center justify-center mb-4 sm:mb-6">
                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 sm:mb-4 px-2">NIDA Verification Questionnaire</h1>
            <p class="text-sm sm:text-base lg:text-lg text-gray-600 max-w-2xl mx-auto px-4">
                Answer the following 3 questions based on your NIDA records to verify your identity
            </p>
        </div>

        <!-- Verification Content -->
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="p-4 sm:p-6 md:p-8">
                @if ($isVerified)
                    <!-- Success State -->
                    <div class="text-center py-6 sm:py-8">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-bold text-green-800 mb-2 px-4">Verification Complete!</h2>
                        <p class="text-sm sm:text-base text-green-700 mb-6 px-4">{{ $successMessage }}</p>
                        <div class="space-y-3">
                            <button 
                                wire:click="goToProfile"
                                class="w-full bg-brand-green text-white px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg text-sm sm:text-base font-semibold hover:bg-brand-green-light transition-colors"
                            >
                                Complete Your Profile
                            </button>
                            <button 
                                wire:click="redirectToDashboard"
                                class="w-full bg-gray-600 text-white px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg text-sm sm:text-base font-semibold hover:bg-gray-700 transition-colors"
                            >
                                Go to Dashboard
                            </button>
                        </div>
                    </div>
                @elseif($showFinalResult && $correctAnswersCount < 2)
                    <!-- Final Result - Failed -->
                    <div class="text-center py-6 sm:py-8">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-sidebar-green rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-bold text-sidebar-green-800 mb-2 px-4">Verification Failed</h2>
                        <p class="text-sm sm:text-base text-sidebar-green-light mb-4 px-4">{{ $errorMessage }}</p>
                        <p class="text-xs sm:text-sm text-gray-600 mb-6 px-4">
                            You answered {{ $correctAnswersCount }} out of {{ $totalQuestions }} questions correctly. 
                            You need at least 2 correct answers to proceed.
                        </p>
                        <button 
                            wire:click="retryVerification"
                            class="w-full bg-brand-green text-white px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg text-sm sm:text-base font-semibold hover:bg-brand-green-light transition-colors"
                        >
                            Try Again
                        </button>
                    </div>
                @else
                    <!-- Sequential Questions Form -->
                    <div>
                        <!-- Progress Indicator -->
                        <div class="mb-4 sm:mb-6">
                            <div class="flex justify-between text-xs sm:text-sm text-gray-600 mb-2">
                                <span class="font-medium">Question {{ $currentQuestion }} of {{ $totalQuestions }}</span>
                                <span class="font-medium">{{ number_format($this->getProgressPercentage()) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-1.5 sm:h-2">
                                <div 
                                    class="bg-brand-green h-1.5 sm:h-2 rounded-full transition-all duration-500" 
                                    style="width: {{ $this->getProgressPercentage() }}%"
                                ></div>
                            </div>
                        </div>

                        <!-- Current Question -->
                        @if($currentQuestion <= $totalQuestions)
                            @php
                                $questionKey = $this->getCurrentQuestionKey();
                            @endphp
                            
                            <form wire:submit.prevent="submitCurrentQuestion" class="space-y-4 sm:space-y-6">
                                <!-- Question Title -->
                                <div class="text-center mb-4 sm:mb-6">
                                    <h3 class="text-base sm:text-lg md:text-xl font-semibold text-gray-900 mb-2 px-2">
                                        {{ $this->getCurrentQuestionText() }} <span class="text-sidebar-green">*</span>
                                    </h3>
                                    <p class="text-xs sm:text-sm text-gray-600 px-2">{{ $this->getQuestionHelper($questionKey) }}</p>
                                </div>

                                <!-- Answer Input -->
                                <div>
                                    @if($questionKey === 'dob_verification')
                                        <input 
                                            type="date" 
                                            wire:model.defer="questionnaireAnswers.{{ $questionKey }}"
                                            class="w-full px-3 py-2.5 sm:px-4 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-brand-green focus:border-brand-green @error('questionnaireAnswers.' . $questionKey) border-sidebar-green @enderror"
                                            max="{{ date('Y-m-d') }}"
                                            autofocus
                                        />
                                    @else
                                        <input 
                                            type="text" 
                                            wire:model.defer="questionnaireAnswers.{{ $questionKey }}"
                                            class="w-full px-3 py-2.5 sm:px-4 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-brand-green focus:border-brand-green @error('questionnaireAnswers.' . $questionKey) border-sidebar-green @enderror"
                                            placeholder="{{ $this->getCurrentQuestionPlaceholder() }}"
                                            maxlength="100"
                                            autofocus
                                        />
                                    @endif
                                    
                                    @error('questionnaireAnswers.' . $questionKey)
                                        <p class="mt-1 text-xs sm:text-sm text-sidebar-green">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Previous Question Result (if any) -->
                                @if($currentQuestion > 1)
                                    @php
                                        $prevQuestionKey = ['dob_verification', 'father_name', 'mother_name'][$currentQuestion - 2];
                                        $prevResult = $questionResults[$prevQuestionKey] ?? null;
                                    @endphp
                                    @if($prevResult !== null)
                                        <div class="rounded-lg p-3 sm:p-4 {{ $prevResult ? 'bg-green-50 border border-green-200' : 'bg-sidebar-green-50 border border-sidebar-green-200' }}">
                                            <div class="flex items-center">
                                                @if($prevResult)
                                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span class="text-xs sm:text-sm text-green-800 font-medium">Previous answer was correct!</span>
                                                @else
                                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-sidebar-green mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    <span class="text-xs sm:text-sm text-sidebar-green-800 font-medium">Previous answer was incorrect.</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endif

                                <!-- Submit Button -->
                                <div class="mt-6 sm:mt-8">
                                    <button 
                                        type="submit"
                                        wire:loading.attr="disabled"
                                        @disabled($isProcessing)
                                        class="w-full flex justify-center py-2.5 sm:py-3 px-4 sm:px-6 border border-transparent rounded-lg shadow-sm text-sm sm:text-base font-semibold text-white bg-brand-green hover:bg-brand-green-light focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-green disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                    >
                                        <span wire:loading.remove wire:target="submitCurrentQuestion">
                                            @if($currentQuestion < $totalQuestions)
                                                <span class="hidden sm:inline">Submit Answer & Continue</span>
                                                <span class="sm:hidden">Submit & Continue</span>
                                            @else
                                                Submit Final Answer
                                            @endif
                                        </span>
                                        <span wire:loading wire:target="submitCurrentQuestion" class="flex items-center">
                                            <svg class="animate-spin -ml-1 mr-2 sm:mr-3 h-4 w-4 sm:h-5 sm:w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Verifying...
                                        </span>
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Security Notice -->
        <div class="mt-6 sm:mt-8 text-center px-2">
            <div class="inline-flex items-center space-x-2 text-xs sm:text-sm text-gray-500 bg-white px-3 sm:px-4 py-2 rounded-lg border border-gray-200">
                <svg class="w-3 h-3 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <span class="text-center">Your data is encrypted and securely processed</span>
            </div>
        </div>
    </main>

    <!-- Loading Overlay - Only shows during form submission -->
    <div wire:loading.flex wire:target="submitCurrentQuestion,retryVerification,goToProfile,redirectToDashboard" class="fixed inset-0 bg-gray-900 bg-opacity-50 items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4">
            <div class="text-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-brand-green mx-auto mb-4"></div>
                <p class="text-gray-600">Verifying your identity...</p>
            </div>
        </div>
    </div>
</div>
</div>
