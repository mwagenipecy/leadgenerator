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
                    <span class="text-sm text-gray-500">Welcome, {{ auth()->user()->first_name ?? "" }}</span>
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
                                @if($qrCodeGenerated && $qrCodeSvg)
                                    <!-- Actual QR Code -->
                                    <div class="flex justify-center mb-4">
                                        {!! $qrCodeSvg !!}
                                    </div>

                                    <!-- Session Information -->
                                    <div class="mt-4 space-y-2">
                                        <div class="text-sm text-gray-600">
                                            Session Code: <span class="font-mono font-semibold text-gray-900">{{ $sessionCode }}</span>
                                        </div>
                                        
                                        <!-- Timer -->
                                        <div class="text-sm text-gray-500" id="timer-container">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Expires in: <span id="countdown" class="font-mono">{{ $this->getFormattedTimeRemaining() }}</span>
                                        </div>
                                        
                                        <!-- Time remaining progress bar -->
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div id="timer-progress" class="bg-brand-red h-2 rounded-full transition-all duration-1000" style="width: 100%"></div>
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
                <span>QR codes expire in 6 minutes for security. Your verification is encrypted end-to-end.</span>
            </div>
        </div>
    </main>

    <!-- JavaScript for real-time polling, timer and validation -->
    <script>
        // Store PHP variables safely in JavaScript
        window.verificationData = {
            expirationTimestamp: {{ $this->getExpirationTimestamp() ?: 0 }},
            qrCodeGenerated: {{ $qrCodeGenerated ? 'true' : 'false' }},
            verificationStep: '{{ $verificationStep }}',
            timeRemaining: {{ $timeRemaining ?: 0 }},
            verificationToken: '{{ $verificationToken }}'
        };

        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded');
            
            // Multiple initialization strategies
            setTimeout(initializeVerificationSystem, 100);
            
            if (window.Livewire) {
                console.log('Livewire already available');
                setTimeout(initializeVerificationSystem, 200);
            }
            
            document.addEventListener('livewire:initialized', function() {
                console.log('Livewire initialized event fired');
                setTimeout(initializeVerificationSystem, 100);
            });
            
            // Fallback initialization
            setTimeout(function() {
                if (window.Livewire && window.Livewire.all && window.Livewire.all().length > 0) {
                    console.log('Fallback initialization');
                    initializeVerificationSystem();
                }
            }, 1000);
        });

        function initializeVerificationSystem() {
            // Prevent multiple initializations
            if (window.verificationSystemInitialized) {
                console.log('Verification system already initialized');
                return;
            }

            // Check if required elements exist
            const livewireComponent = window.Livewire?.all?.()?.[0];
            if (!livewireComponent) {
                console.log('Livewire component not ready, retrying...');
                setTimeout(initializeVerificationSystem, 500);
                return;
            }

            window.verificationSystemInitialized = true;
            
            let pollInterval = null;
            let timerInterval = null;
            let syncInterval = null;
            let expirationTimestamp = window.verificationData.expirationTimestamp || 0;
            let isExpired = false;
            let lastSyncTime = Date.now();
            
            console.log('Initializing verification system...', {
                expirationTimestamp,
                qrCodeGenerated: window.verificationData.qrCodeGenerated,
                verificationStep: window.verificationData.verificationStep
            });
            
            // Real-time timer that syncs with server
            class LiveTimer {
                constructor() {
                    this.isRunning = false;
                    this.syncEvery = 30000; // Sync with server every 30 seconds
                    this.lastSync = Date.now();
                }

                start() {
                    if (this.isRunning) return;
                    this.isRunning = true;
                    console.log('Starting live timer...');
                    this.updateDisplay();
                    this.startTimer();
                    this.startSyncInterval();
                }

                stop() {
                    this.isRunning = false;
                    console.log('Stopping live timer...');
                    if (timerInterval) {
                        clearInterval(timerInterval);
                        timerInterval = null;
                    }
                    if (syncInterval) {
                        clearInterval(syncInterval);
                        syncInterval = null;
                    }
                }

                startTimer() {
                    if (timerInterval) clearInterval(timerInterval);
                    
                    timerInterval = setInterval(() => {
                        this.updateDisplay();
                    }, 1000);
                }

                startSyncInterval() {
                    if (syncInterval) clearInterval(syncInterval);
                    
                    // Sync with server every 30 seconds
                    syncInterval = setInterval(() => {
                        this.syncWithServer();
                    }, this.syncEvery);
                }

                updateDisplay() {
                    if (!expirationTimestamp || isExpired) return;

                    const now = Date.now();
                    const timeRemaining = Math.max(0, Math.floor((expirationTimestamp - now) / 1000));
                    
                    if (timeRemaining <= 0) {
                        this.handleExpiration();
                        return;
                    }

                    // Update countdown display
                    const minutes = Math.floor(timeRemaining / 60);
                    const seconds = timeRemaining % 60;
                    const countdownEl = document.getElementById('countdown');
                    if (countdownEl) {
                        countdownEl.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                    }

                    // Update progress bar
                    const progressEl = document.getElementById('timer-progress');
                    if (progressEl) {
                        const totalTime = 360; // 6 minutes
                        const progressPercent = (timeRemaining / totalTime) * 100;
                        progressEl.style.width = `${Math.max(0, progressPercent)}%`;
                        
                        // Change color as time runs out
                        if (progressPercent < 25) {
                            progressEl.className = 'bg-red-500 h-2 rounded-full transition-all duration-1000';
                        } else if (progressPercent < 50) {
                            progressEl.className = 'bg-yellow-500 h-2 rounded-full transition-all duration-1000';
                        } else {
                            progressEl.className = 'bg-brand-red h-2 rounded-full transition-all duration-1000';
                        }
                    }

                    // Auto-sync more frequently when time is running low
                    const timeSinceLastSync = now - this.lastSync;
                    if ((timeRemaining < 60 && timeSinceLastSync > 10000) || // Every 10 seconds when < 1 minute
                        (timeRemaining < 30 && timeSinceLastSync > 5000)) {  // Every 5 seconds when < 30 seconds
                        this.syncWithServer();
                    }
                }

                async syncWithServer() {
                    try {
                        this.lastSync = Date.now();
                        
                        const livewireComponent = window.Livewire?.all?.()?.[0];
                        if (!livewireComponent || !livewireComponent.call) {
                            console.warn('Livewire component not ready for sync');
                            return;
                        }
                        
                        const response = await livewireComponent.call('syncTimerWithServer');
                        
                        if (response && response.expiresAt) {
                            expirationTimestamp = response.expiresAt;
                            isExpired = response.expired;
                            
                            if (isExpired || response.timeRemaining <= 0) {
                                this.handleExpiration();
                                return;
                            }
                        }
                    } catch (error) {
                        console.error('Timer sync error:', error);
                    }
                }

                handleExpiration() {
                    isExpired = true;
                    this.stop();
                    
                    const countdownEl = document.getElementById('countdown');
                    if (countdownEl) {
                        countdownEl.textContent = '0:00';
                        const timerContainer = countdownEl.closest('#timer-container');
                        if (timerContainer) {
                            timerContainer.classList.add('text-red-500');
                        }
                    }
                    
                    const progressEl = document.getElementById('timer-progress');
                    if (progressEl) {
                        progressEl.style.width = '0%';
                        progressEl.className = 'bg-red-500 h-2 rounded-full transition-all duration-1000';
                    }
                    
                    // Notify server about expiration
                    const livewireComponent = window.Livewire?.all?.()?.[0];
                    if (livewireComponent && livewireComponent.call) {
                        livewireComponent.call('updateTimer').catch(error => {
                            console.error('Error updating timer on server:', error);
                        });
                    }
                }
            }

            const liveTimer = new LiveTimer();

            // Polling for verification status
            function startPolling() {
                if (pollInterval || isExpired) return;
                
                console.log('Starting verification polling...');
                pollInterval = setInterval(async () => {
                    try {
                        const livewireComponent = window.Livewire?.all?.()?.[0];
                        if (livewireComponent && livewireComponent.call) {
                            await livewireComponent.call('checkConnection');
                        }
                    } catch (error) {
                        console.error('Polling error:', error);
                    }
                }, 5000); // Poll every 5 seconds
            }

            function stopPolling() {
                if (pollInterval) {
                    console.log('Stopping verification polling...');
                    clearInterval(pollInterval);
                    pollInterval = null;
                }
            }

            // Safe event listener registration using Livewire's event system
            function registerLivewireEvents() {
                try {
                    if (window.Livewire && window.Livewire.on) {
                        window.Livewire.on('start-polling', () => {
                            console.log('Received start-polling event');
                            startPolling();
                            liveTimer.start();
                        });

                        window.Livewire.on('stop-polling', () => {
                            console.log('Received stop-polling event');
                            stopPolling();
                            liveTimer.stop();
                        });

                        window.Livewire.on('phone-connected', () => {
                            console.log('Phone connected! Continuing to poll for verification completion...');
                            // Don't stop polling yet - wait for verification completion
                        });

                        window.Livewire.on('verification-completed', () => {
                            console.log('Verification completed!');
                            stopPolling();
                            liveTimer.stop();
                        });

                        window.Livewire.on('redirect-after-timeout', () => {
                            console.log('Redirecting after timeout...');
                            stopPolling();
                            liveTimer.stop();
                            
                            setTimeout(() => {
                                const livewireComponent = window.Livewire?.all?.()?.[0];
                                if (livewireComponent && livewireComponent.call) {
                                    livewireComponent.call('redirectToOptions').catch(error => {
                                        console.error('Error redirecting:', error);
                                        // Fallback redirect
                                        window.location.href = '/verification-options';
                                    });
                                }
                            }, 3000);
                        });

                        console.log('Livewire events registered successfully');
                    } else {
                        console.warn('Livewire.on not available, retrying...');
                        setTimeout(registerLivewireEvents, 1000);
                    }
                } catch (error) {
                    console.error('Error registering Livewire events:', error);
                }
            }

            registerLivewireEvents();

            // Browser event listeners with null checks
            function registerBrowserEvents() {
                // Page visibility handling
                if (document && typeof document.addEventListener === 'function') {
                    document.addEventListener('visibilitychange', () => {
                        if (!document.hidden && !isExpired) {
                            console.log('Page visible - syncing with server...');
                            liveTimer.syncWithServer();
                            
                            // Restart polling if it was stopped
                            if (!pollInterval && window.verificationData.verificationStep !== 'complete') {
                                startPolling();
                            }
                        } else if (document.hidden) {
                            console.log('Page hidden - maintaining background sync...');
                        }
                    });
                }

                // Window event listeners
                if (window && typeof window.addEventListener === 'function') {
                    window.addEventListener('focus', () => {
                        if (!isExpired) {
                            console.log('Window focused - checking status...');
                            liveTimer.syncWithServer();
                        }
                    });

                    window.addEventListener('online', () => {
                        if (!isExpired) {
                            console.log('Network reconnected - syncing...');
                            liveTimer.syncWithServer();
                            if (!pollInterval && window.verificationData.verificationStep !== 'complete') {
                                startPolling();
                            }
                        }
                    });

                    window.addEventListener('offline', () => {
                        console.log('Network disconnected - will resume when online');
                    });

                    window.addEventListener('beforeunload', () => {
                        stopPolling();
                        liveTimer.stop();
                    });
                }

                // Livewire navigation cleanup
                if (document && typeof document.addEventListener === 'function') {
                    document.addEventListener('livewire:navigating', () => {
                        stopPolling();
                        liveTimer.stop();
                    });
                }
            }

            registerBrowserEvents();

            // Initialize if QR code is already generated
            if (window.verificationData.qrCodeGenerated && !isExpired) {
                console.log('QR Code is generated, starting system...');
                
                // Small delay to ensure all DOM elements are ready
                setTimeout(() => {
                    // Sync time first
                    liveTimer.syncWithServer().then(() => {
                        if (!isExpired && window.verificationData.timeRemaining > 0) {
                            liveTimer.start();
                            
                            const step = window.verificationData.verificationStep;
                            if (step === 'waiting' || step === 'connected') {
                                startPolling();
                            }
                        }
                    }).catch(error => {
                        console.error('Error during initial sync:', error);
                        // Start timer anyway with current data
                        if (!isExpired && window.verificationData.timeRemaining > 0) {
                            liveTimer.start();
                            const step = window.verificationData.verificationStep;
                            if (step === 'waiting' || step === 'connected') {
                                startPolling();
                            }
                        }
                    });
                }, 1000);
            }

            // Auto-refresh QR code if it becomes invalid
            setInterval(async () => {
                if (window.verificationData.qrCodeGenerated && !isExpired && window.verificationData.verificationToken) {
                    try {
                        const response = await fetch('/api/verify-token/' + window.verificationData.verificationToken);
                        if (!response.ok) {
                            console.log('Token invalid - regenerating...');
                            const livewireComponent = window.Livewire?.all?.()?.[0];
                            if (livewireComponent && livewireComponent.call) {
                                livewireComponent.call('regenerateQRCode').catch(error => {
                                    console.error('Error regenerating QR code:', error);
                                });
                            }
                        }
                    } catch (error) {
                        console.error('Token validation error:', error);
                    }
                }
            }, 60000); // Check every minute

            console.log('QR Code Verification system initialized successfully');
        }
    </script>
    </script>
</div>