<div>
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="text-2xl font-bold font-poppins text-black">
                    Lead<span class="text-brand-red">Generator</span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">Welcome, {{ auth()->user()?->first_name ?? 'User' }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-brand-red hover:text-red-700 transition-colors">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Progress Indicator -->
        <div class="mb-8">
            <div class="flex items-center justify-center space-x-4 text-sm text-gray-600">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-2">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span>Account Created</span>
                </div>
                <div class="w-16 h-0.5 bg-gray-300"></div>
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-2">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span>Method Selected</span>
                </div>
                <div class="w-16 h-0.5 bg-gray-300"></div>
                <div class="flex items-center">
                    <div class="w-8 h-8 {{ $isVerified ? 'bg-green-500' : 'bg-brand-red' }} rounded-full flex items-center justify-center mr-2">
                        @if($isVerified)
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        @else
                            <span class="text-white font-medium">3</span>
                        @endif
                    </div>
                    <span class="{{ $isVerified ? 'text-green-600' : '' }} font-medium">Questionnaire Verification</span>
                </div>
            </div>
        </div>

        <!-- Header Section -->
        <div class="text-center mb-8">
            <div class="mx-auto w-20 h-20 bg-brand-red/10 rounded-2xl flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">NIDA Verification Questionnaire</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Answer the following 3 questions based on your NIDA records to verify your identity
            </p>
        </div>

        <!-- Verification Content -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="p-6 sm:p-8">
                @if ($isVerified)
                    <!-- Success State -->
                    <div class="text-center py-8">
                        <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-green-800 mb-2">Verification Complete!</h2>
                        <p class="text-green-700 mb-6">{{ $successMessage }}</p>
                        <div class="space-y-3">
                            <button 
                                wire:click="goToProfile"
                                class="w-full bg-brand-red text-white px-8 py-3 rounded-lg font-semibold hover:bg-red-700 transition-colors"
                            >
                                Complete Your Profile
                            </button>
                            <button 
                                wire:click="redirectToDashboard"
                                class="w-full bg-gray-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-700 transition-colors"
                            >
                                Go to Dashboard
                            </button>
                        </div>
                    </div>
                @elseif($showFinalResult && $correctAnswersCount < 2)
                    <!-- Final Result - Failed -->
                    <div class="text-center py-8">
                        <div class="w-20 h-20 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-red-800 mb-2">Verification Failed</h2>
                        <p class="text-red-700 mb-4">{{ $errorMessage }}</p>
                        <p class="text-gray-600 mb-6">
                            You answered {{ $correctAnswersCount }} out of {{ $totalQuestions }} questions correctly. 
                            You need at least 2 correct answers to proceed.
                        </p>
                        <button 
                            wire:click="retryVerification"
                            class="w-full bg-red-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-red-700 transition-colors"
                        >
                            Try Again
                        </button>
                    </div>
                @else
                    <!-- Sequential Questions Form -->
                    <div>
                        <!-- Progress Indicator -->
                        <div class="mb-6">
                            <div class="flex justify-between text-sm text-gray-600 mb-2">
                                <span>Question {{ $currentQuestion }} of {{ $totalQuestions }}</span>
                                <span>{{ number_format($this->getProgressPercentage()) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div 
                                    class="bg-brand-red h-2 rounded-full transition-all duration-500" 
                                    style="width: {{ $this->getProgressPercentage() }}%"
                                ></div>
                            </div>
                        </div>

                        <!-- Current Question -->
                        @if($currentQuestion <= $totalQuestions)
                            @php
                                $questionKey = $this->getCurrentQuestionKey();
                            @endphp
                            
                            <form wire:submit.prevent="submitCurrentQuestion" class="space-y-6">
                                <!-- Question Title -->
                                <div class="text-center mb-6">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                        {{ $this->getCurrentQuestionText() }} <span class="text-red-500">*</span>
                                    </h3>
                                    <p class="text-sm text-gray-600">{{ $this->getQuestionHelper($questionKey) }}</p>
                                </div>

                                <!-- Answer Input -->
                                <div>
                                    @if($questionKey === 'dob_verification')
                                        <input 
                                            type="date" 
                                            wire:model.live="questionnaireAnswers.{{ $questionKey }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-brand-red focus:border-brand-red @error('questionnaireAnswers.' . $questionKey) border-red-500 @enderror"
                                            max="{{ date('Y-m-d') }}"
                                            autofocus
                                        />
                                    @else
                                        <input 
                                            type="text" 
                                            wire:model.live="questionnaireAnswers.{{ $questionKey }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-brand-red focus:border-brand-red @error('questionnaireAnswers.' . $questionKey) border-red-500 @enderror"
                                            placeholder="{{ $this->getCurrentQuestionPlaceholder() }}"
                                            maxlength="100"
                                            autofocus
                                        />
                                    @endif
                                    
                                    @error('questionnaireAnswers.' . $questionKey)
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Previous Question Result (if any) -->
                                @if($currentQuestion > 1)
                                    @php
                                        $prevQuestionKey = ['dob_verification', 'father_name', 'mother_name'][$currentQuestion - 2];
                                        $prevResult = $questionResults[$prevQuestionKey] ?? null;
                                    @endphp
                                    @if($prevResult !== null)
                                        <div class="rounded-lg p-4 {{ $prevResult ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                                            <div class="flex items-center">
                                                @if($prevResult)
                                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span class="text-green-800 font-medium">Previous answer was correct!</span>
                                                @else
                                                    <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    <span class="text-red-800 font-medium">Previous answer was incorrect.</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endif

                                <!-- Submit Button -->
                                <div class="mt-8">
                                    <button 
                                        type="submit"
                                        wire:loading.attr="disabled"
                                        @disabled($isProcessing || empty($questionnaireAnswers[$questionKey]))
                                        class="w-full flex justify-center py-3 px-6 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-brand-red hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-red disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                    >
                                        <span wire:loading.remove wire:target="submitCurrentQuestion">
                                            @if($currentQuestion < $totalQuestions)
                                                Submit Answer & Continue
                                            @else
                                                Submit Final Answer
                                            @endif
                                        </span>
                                        <span wire:loading wire:target="submitCurrentQuestion" class="flex items-center">
                                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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
        <div class="mt-8 text-center">
            <div class="inline-flex items-center space-x-2 text-sm text-gray-500 bg-white px-4 py-2 rounded-lg border border-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <span>Your data is encrypted and securely processed</span>
            </div>
        </div>
    </main>

    <!-- Loading Overlay -->
    <div wire:loading.flex class="fixed inset-0 bg-gray-900 bg-opacity-50 items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4">
            <div class="text-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-brand-red mx-auto mb-4"></div>
                <p class="text-gray-600">Verifying your identity...</p>
            </div>
        </div>
    </div>
</div>
</div>
