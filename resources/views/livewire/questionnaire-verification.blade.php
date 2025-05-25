<div>
<div>
    <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h1 class="text-xl font-semibold text-gray-900">Identity Verification</h1>
                    <button 
                        wire:click="backToMethodSelection"
                        class="text-gray-600 hover:text-gray-800 transition-colors"
                        title="Back to verification methods"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </button>
                </div>
                
                <!-- Progress Bar -->
                @if (!$isVerified)
                    <div class="mt-4">
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>Progress</span>
                            <span>{{ number_format($this->getProgressPercentage()) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div 
                                class="bg-blue-600 h-2 rounded-full transition-all duration-500" 
                                style="width: {{ $this->getProgressPercentage() }}%"
                            ></div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Content -->
            <div class="px-6 py-6">
                @if ($isVerified)
                    <!-- Success State -->
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-medium text-gray-900 mb-2">Verification Complete!</h2>
                        <p class="text-sm text-gray-600 mb-6">{{ $successMessage }}</p>
                        <button 
                            wire:click="redirectToDashboard"
                            class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                        >
                            Continue to Dashboard
                        </button>
                    </div>
                @else
                    <!-- Questionnaire Form -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 mb-2">Verification Questionnaire</h2>
                        <p class="text-sm text-gray-600 mb-6">Please answer the following questions to verify your identity with NIDA records.</p>

                        <form wire:submit.prevent="submitQuestionnaire" class="space-y-6">
                            
                            <!-- Date of Birth -->
                            <div>
                                <label for="dob_verification" class="block text-sm font-medium text-gray-700 mb-1">
                                    Date of Birth <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="date" 
                                    id="dob_verification"
                                    wire:model.live="questionnaireAnswers.dob_verification"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('questionnaireAnswers.dob_verification') border-red-500 @enderror"
                                    max="{{ date('Y-m-d') }}"
                                />
                                @error('questionnaireAnswers.dob_verification')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">{{ $this->getQuestionHelper('dob_verification') }}</p>
                            </div>

                            <!-- Birth Place -->
                            <div>
                                <label for="birth_place" class="block text-sm font-medium text-gray-700 mb-1">
                                    Place of Birth <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="birth_place"
                                    wire:model.live="questionnaireAnswers.birth_place"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('questionnaireAnswers.birth_place') border-red-500 @enderror"
                                    placeholder="e.g., Hospital name or city"
                                    maxlength="100"
                                />
                                @error('questionnaireAnswers.birth_place')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">{{ $this->getQuestionHelper('birth_place') }}</p>
                            </div>

                            <!-- Father's Name -->
                            <div>
                                <label for="father_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Father's Full Name <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="father_name"
                                    wire:model.live="questionnaireAnswers.father_name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('questionnaireAnswers.father_name') border-red-500 @enderror"
                                    placeholder="Enter father's full name"
                                    maxlength="100"
                                />
                                @error('questionnaireAnswers.father_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">{{ $this->getQuestionHelper('father_name') }}</p>
                            </div>

                            <!-- Mother's Name -->
                            <div>
                                <label for="mother_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Mother's Full Name <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="mother_name"
                                    wire:model.live="questionnaireAnswers.mother_name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('questionnaireAnswers.mother_name') border-red-500 @enderror"
                                    placeholder="Enter mother's full name"
                                    maxlength="100"
                                />
                                @error('questionnaireAnswers.mother_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">{{ $this->getQuestionHelper('mother_name') }}</p>
                            </div>

                            <!-- Birth Region -->
                            <div>
                                <label for="birth_region" class="block text-sm font-medium text-gray-700 mb-1">
                                    Birth Region <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    id="birth_region"
                                    wire:model.live="questionnaireAnswers.birth_region"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('questionnaireAnswers.birth_region') border-red-500 @enderror"
                                >
                                    <option value="">Select your birth region</option>
                                    @foreach($regions as $key => $region)
                                        <option value="{{ $key }}">{{ $region }}</option>
                                    @endforeach
                                </select>
                                @error('questionnaireAnswers.birth_region')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">{{ $this->getQuestionHelper('birth_region') }}</p>
                            </div>

                            <!-- Error Message -->
                            @if($errorMessage)
                                <div class="rounded-md bg-red-50 p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-red-800">Verification Failed</h3>
                                            <p class="mt-1 text-sm text-red-700">{{ $errorMessage }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Submit Button -->
                            <div class="flex flex-col space-y-3">
                                <button 
                                    type="submit"
                                    wire:loading.attr="disabled"
                                    @disabled($isProcessing || !$this->isFormComplete())
                                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                >
                                    <span wire:loading.remove wire:target="submitQuestionnaire">
                                        @if($this->isFormComplete())
                                            Verify Identity
                                        @else
                                            Complete All Fields to Continue
                                        @endif
                                    </span>
                                    <span wire:loading wire:target="submitQuestionnaire" class="flex items-center">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Verifying...
                                    </span>
                                </button>

                                @if($errorMessage)
                                    <button 
                                        type="button"
                                        wire:click="retryVerification"
                                        class="w-full py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                                    >
                                        Try Again
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-lg">
                <div class="flex items-center justify-center text-xs text-gray-500">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-9a2 2 0 00-2-2H6a2 2 0 00-2 2v9a2 2 0 002 2zm10-12V6a2 2 0 00-2-2H8a2 2 0 00-2 2v3m8 0V9a2 2 0 00-2-2H10a2 2 0 00-2 2v0"></path>
                    </svg>
                    Your data is encrypted and secure
                </div>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div 
            wire:loading.flex 
            wire:target="submitQuestionnaire"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center z-50"
        >
            <div class="bg-white p-6 rounded-lg shadow-xl">
                <div class="flex items-center space-x-3">
                    <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <div>
                        <p class="text-lg font-medium text-gray-900">Verifying your identity...</p>
                        <p class="text-sm text-gray-600">This may take a few moments</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Styles -->
    <style>
        /* Custom focus styles for better accessibility */
        input:focus, select:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Smooth transitions for progress bar */
        .progress-bar {
            transition: width 0.3s ease-in-out;
        }

        /* Custom form validation styles */
        .field-valid {
            border-color: #10b981;
        }

        .field-valid:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }
    </style>

    <!-- JavaScript for enhanced UX -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Add visual feedback for completed fields
            Livewire.on('fieldUpdated', (field) => {
                const input = document.getElementById(field);
                if (input && input.value.trim() !== '') {
                    input.classList.add('field-valid');
                } else {
                    input.classList.remove('field-valid');
                }
            });

            // Auto-focus next field after completion
            const fields = ['dob_verification', 'birth_place', 'father_name', 'mother_name', 'birth_region'];
            fields.forEach((field, index) => {
                const input = document.getElementById(field);
                if (input) {
                    input.addEventListener('blur', () => {
                        if (input.value.trim() !== '' && index < fields.length - 1) {
                            const nextField = document.getElementById(fields[index + 1]);
                            if (nextField && nextField.value.trim() === '') {
                                setTimeout(() => nextField.focus(), 100);
                            }
                        }
                    });
                }
            });
        });
    </script>
</div>

</div>
