{{-- resources/views/livewire/qr-code-verification.blade.php --}}
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="text-2xl font-bold font-poppins text-black">
                    Lead<span class="text-brand-red">Generator</span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">Welcome, {{ auth()->user()->first_name }}</span>
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
                    <span class="{{ $isVerified ? 'text-green-600' : '' }} font-medium">QR Code Verification</span>
                </div>
            </div>
        </div>

        <!-- Header Section -->
        <div class="text-center mb-8">
            <div class="mx-auto w-20 h-20 bg-brand-red/10 rounded-2xl flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                </svg>
            </div>
            <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">QR Code Verification</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Scan the QR code with your phone to complete verification on your mobile device
            </p>
        </div>

        <!-- Verification Content -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="p-6 sm:p-8">
                
                @if($verificationStep === 'complete' && $isVerified)
                    <!-- Verification Complete -->
                    <div class="text-center py-8">
                        <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-green-800 mb-2">Verification Complete!</h2>
                        <p class="text-green-700 mb-6">Your identity has been successfully verified using your mobile device.</p>
                        <button 
                            wire:click="redirectToDashboard"
                            class="bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors"
                        >
                            Continue to Dashboard
                        </button>
                    </div>
                @else
                    <!-- QR Code Display -->
                    <div class="max-w-lg mx-auto">
                        @if($errorMessage)
                            <!-- Error Message -->
                            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-red-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-medium text-red-800">Error</h3>
                                        <p class="text-sm text-red-700 mt-1">{{ $errorMessage }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="text-center">
                            @if($qrCodeGenerated)
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                    @if($verificationStep === 'waiting')
                                        Scan with Your Phone
                                    @elseif($verificationStep === 'connected')
                                        Phone Connected!
                                    @endif
                                </h3>
                                <p class="text-gray-600 mb-6">
                                    @if($verificationStep === 'waiting')
                                        Use your phone's camera to scan the QR code below
                                    @elseif($verificationStep === 'connected')
                                        Complete the verification on your mobile device
                                    @endif
                                </p>
                            @else
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Generating QR Code...</h3>
                                <p class="text-gray-600 mb-6">Please wait while we create your secure verification link</p>
                            @endif
                        </div>

                        <!-- QR Code Container -->
                        <div class="bg-gray-50 rounded-xl p-6">
                            <div class="bg-white p-6 rounded-xl shadow-sm text-center">
                                @if($qrCodeGenerated)
                                    <!-- QR Code Visual -->
                                    <div class="w-64 h-64 mx-auto border-2 border-gray-200 rounded-lg overflow-hidden relative">
                                        <div class="w-full h-full bg-black p-4 relative" style="
                                            background-image: 
                                                repeating-linear-gradient(0deg, black 0px, black 3px, white 3px, white 6px),
                                                repeating-linear-gradient(90deg, black 0px, black 3px, white 3px, white 6px);
                                            background-size: 12px 12px;
                                        ">
                                            <!-- QR Code Center -->
                                            <div class="absolute inset-8 bg-white rounded flex items-center justify-center">
                                                <div class="text-center">
                                                    <div class="text-xs font-mono font-bold mb-2 text-black">{{ $sessionCode }}</div>
                                                    <div class="w-8 h-8 bg-black mx-auto rounded"></div>
                                                </div>
                                            </div>
                                            
                                            <!-- Corner Squares -->
                                            <div class="absolute top-2 left-2 w-8 h-8 bg-black rounded-sm"></div>
                                            <div class="absolute top-2 right-2 w-8 h-8 bg-black rounded-sm"></div>
                                            <div class="absolute bottom-2 left-2 w-8 h-8 bg-black rounded-sm"></div>
                                        </div>
                                    </div>

                                    <!-- Session Information -->
                                    <div class="mt-4 space-y-2">
                                        <div class="text-sm text-gray-600">
                                            Session Code: <span class="font-mono font-semibold text-gray-900">{{ $sessionCode }}</span>
                                        </div>
                                        
                                        <!-- Timer -->
                                        <div class="text-sm text-gray-500" id="timer">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Expires in: <span id="countdown">{{ $this->getFormattedTimeRemaining() }}</span>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="mt-4 space-y-3">
                                        <button 
                                            wire:click="regenerateQRCode"
                                            class="text-brand-red hover:text-red-700 font-medium text-sm"
                                        >
                                            🔄 Generate New Code
                                        </button>
                                    </div>
                                @else
                                    <!-- Loading State -->
                                    <div class="w-64 h-64 mx-auto border-2 border-gray-200 rounded-lg flex items-center justify-center">
                                        <div class="text-center">
                                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-brand-red mx-auto mb-4"></div>
                                            <p class="text-gray-500 text-sm">Generating secure code...</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Connection Status -->
                        @if($qrCodeGenerated)
                            <div class="mt-6 text-center">
                                @if($verificationStep === 'connected')
                                    <div class="flex items-center justify-center space-x-2 text-sm text-green-600 bg-green-50 px-4 py-3 rounded-lg border border-green-200">
                                        <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span>Phone connected! Waiting for verification completion...</span>
                                    </div>
                                @elseif($verificationStep === 'waiting')
                                    <div class="flex items-center justify-center space-x-2 text-sm text-blue-600 bg-blue-50 px-4 py-3 rounded-lg border border-blue-200">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                                        <span>Waiting for phone connection...</span>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Instructions -->
                        <div class="mt-8 bg-blue-50 rounded-lg p-6">
                            <h4 class="font-semibold text-blue-900 mb-3">How to scan:</h4>
                            <ol class="text-sm text-blue-800 space-y-2">
                                <li class="flex items-start">
                                    <span class="inline-block w-6 h-6 bg-blue-600 text-white text-xs rounded-full flex items-center justify-center mr-3 mt-0.5 font-semibold">1</span>
                                    <span>Open your phone's camera app</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="inline-block w-6 h-6 bg-blue-600 text-white text-xs rounded-full flex items-center justify-center mr-3 mt-0.5 font-semibold">2</span>
                                    <span>Point the camera at the QR code above</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="inline-block w-6 h-6 bg-blue-600 text-white text-xs rounded-full flex items-center justify-center mr-3 mt-0.5 font-semibold">3</span>
                                    <span>Tap the notification or link that appears</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="inline-block w-6 h-6 bg-blue-600 text-white text-xs rounded-full flex items-center justify-center mr-3 mt-0.5 font-semibold">4</span>
                                    <span>Complete the verification on your mobile browser</span>
                                </li>
                            </ol>
                        </div>

                        <!-- Alternative Options -->
                        <div class="mt-6 text-center">
                            <p class="text-sm text-gray-600 mb-3">Having trouble scanning?</p>
                            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                                <button 
                                    wire:click="backToMethodSelection"
                                    class="text-gray-600 hover:text-gray-800 text-sm font-medium px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                                >
                                    Try Different Method
                                </button>
                                <button 
                                    onclick="navigator.share ? navigator.share({url: '{{ $this->getQRCodeUrl() }}', title: 'NIDA Verification'}) : window.open('{{ $this->getQRCodeUrl() }}', '_blank')"
                                    class="text-brand-red hover:text-red-700 text-sm font-medium px-4 py-2 border border-brand-red rounded-lg hover:bg-red-50 transition-colors"
                                >
                                    Open Link Manually
                                </button>
                            </div>
                        </div>
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
                <span>QR codes expire in 10 minutes for security. Your verification is encrypted end-to-end.</span>
            </div>
        </div>
    </main>

    <!-- JavaScript for polling and timer -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            let pollInterval = null;
            let timerInterval = null;
            let timeRemaining = {{ $this->getTimeRemaining() }};

            // Start polling when component dispatches start-polling event
            Livewire.on('start-polling', () => {
                if (pollInterval) clearInterval(pollInterval);
                
                // Poll every 3 seconds
                pollInterval = setInterval(() => {
                    @this.checkConnection();
                }, 3000);
            });

            // Stop polling when phone connects
            Livewire.on('phone-connected', () => {
                if (pollInterval) {
                    clearInterval(pollInterval);
                    pollInterval = null;
                }
            });

            // Stop polling when verification completes
            Livewire.on('verification-completed', () => {
                if (pollInterval) {
                    clearInterval(pollInterval);
                    pollInterval = null;
                }
                if (timerInterval) {
                    clearInterval(timerInterval);
                    timerInterval = null;
                }
            });

            // Start timer countdown
            function startTimer() {
                if (timerInterval) clearInterval(timerInterval);
                
                timerInterval = setInterval(() => {
                    timeRemaining--;
                    
                    if (timeRemaining <= 0) {
                        clearInterval(timerInterval);
                        document.getElementById('countdown').textContent = '0:00';
                        return;
                    }
                    
                    const minutes = Math.floor(timeRemaining / 60);
                    const seconds = timeRemaining % 60;
                    document.getElementById('countdown').textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                }, 1000);
            }

            // Start timer if QR code is generated
            if (timeRemaining > 0) {
                startTimer();
            }

            // Stop polling and timer when component is destroyed
            document.addEventListener('livewire:navigating', () => {
                if (pollInterval) clearInterval(pollInterval);
                if (timerInterval) clearInterval(timerInterval);
            });
        });
    </script>
</div>