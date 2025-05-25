<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NIDA Verification - Lead Generator</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-red': '#C40F12',
                    }

        // Photo capture
        function startPhotoCapture(type) {
            // Create file input
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*';
            input.capture = 'environment'; // Use back camera
            
            input.onchange = function(event) {
                const file = event.target.files[0];
                if (file) {
                    handlePhotoCapture(file, type);
                }
            };
            
            input.click();
        }

        // Handle captured photo
        function handlePhotoCapture(file, type) {
            const reader = new FileReader();
            reader.onload = function(e) {
                photoData = {
                    file: file,
                    data: e.target.result,
                    type: type
                };
                
                // Show preview and processing
                showPhotoPreview(e.target.result, type);
                
                // Simulate processing
                setTimeout(() => {
                    processPhoto(photoData);
                }, 2000);
            };
            reader.readAsDataURL(file);
        }

        // Show photo preview
        function showPhotoPreview(imageSrc, type) {
            const container = document.getElementById('phone-photo-container');
            const preview = document.createElement('div');
            preview.className = 'mt-6 p-4 bg-white rounded-xl border border-gray-200';
            preview.innerHTML = `
                <div class="text-center">
                    <h4 class="font-semibold text-gray-900 mb-3">Processing ${type === 'id_document' ? 'ID Document' : 'Fingerprint'}...</h4>
                    <div class="relative inline-block">
                        <img src="${imageSrc}" alt="Captured ${type}" class="w-64 h-48 object-cover rounded-lg border">
                        <div class="absolute inset-0 bg-brand-red/20 rounded-lg flex items-center justify-center">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-white"></div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mt-3">Analyzing image quality and extracting information...</p>
                </div>
            `;
            container.appendChild(preview);
        }

        // Process captured photo
        function processPhoto(photoData) {
            // Simulate API call to process photo
            fetch('/api/verify-photo', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    method: 'phone_photo',
                    photo_type: photoData.type,
                    photo_data: photoData.data
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showVerificationSuccess();
                } else {
                    showVerificationError(data.message);
                }
            })
            .catch(error => {
                console.error('Photo processing error:', error);
                showVerificationError('Failed to process photo. Please try again.');
            });
        }

        // Generate QR Code
        function generateQRCode() {
            const qrCodeEl = document.getElementById('qr-code');
            const sessionEl = document.getElementById('session-code');
            
            // Simulate QR code generation
            fetch('/api/generate-verification-link', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => response.json())
            .then(data => {
                verificationToken = data.token;
                sessionEl.textContent = data.session_code;
                
                // Create QR code visual
                qrCodeEl.innerHTML = `
                    <div class="w-full h-full bg-black p-4 rounded-lg relative" style="
                        background-image: 
                            repeating-linear-gradient(0deg, black 0px, black 3px, white 3px, white 6px),
                            repeating-linear-gradient(90deg, black 0px, black 3px, white 3px, white 6px);
                        background-size: 12px 12px;
                    ">
                        <div class="absolute inset-8 bg-white rounded flex items-center justify-center">
                            <div class="text-center">
                                <div class="text-xs font-mono font-bold mb-2">${data.session_code}</div>
                                <div class="w-8 h-8 bg-black mx-auto"></div>
                            </div>
                        </div>
                    </div>
                `;
                
                startPollingForConnection();
            })
            .catch(error => {
                console.error('QR generation error:', error);
                qrCodeEl.innerHTML = `
                    <div class="text-center text-red-500 p-4">
                        <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm">Failed to generate QR code</p>
                        <button onclick="generateQRCode()" class="text-brand-red hover:underline mt-1">Try again</button>
                    </div>
                `;
            });
        }

        // Poll for phone connection
        function startPollingForConnection() {
            const statusEl = document.getElementById('qr-status');
            let attempts = 0;
            const maxAttempts = 60; // 5 minutes
            
            const poll = setInterval(() => {
                fetch(`/api/check-connection/${verificationToken}`)
                .then(response => response.json())
                .then(data => {
                    if (data.connected) {
                        clearInterval(poll);
                        statusEl.innerHTML = `
                            <div class="flex items-center justify-center space-x-2 text-sm text-green-600 bg-green-50 px-4 py-2 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Phone connected! Waiting for verification...</span>
                            </div>
                        `;
                        
                        if (data.verified) {
                            showVerificationSuccess();
                        } else {
                            waitForVerification();
                        }
                    }
                    
                    attempts++;
                    if (attempts >= maxAttempts) {
                        clearInterval(poll);
                        statusEl.innerHTML = `
                            <div class="flex items-center justify-center space-x-2 text-sm text-gray-600 bg-gray-100 px-4 py-2 rounded-lg">
                                <span>Session expired. </span>
                                <button onclick="generateQRCode()" class="text-brand-red hover:underline">Generate new code</button>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Connection poll error:', error);
                });
            }, 5000);
        }

        // Wait for verification completion
        function waitForVerification() {
            const poll = setInterval(() => {
                fetch(`/api/check-verification/${verificationToken}`)
                .then(response => response.json())
                .then(data => {
                    if (data.verified) {
                        clearInterval(poll);
                        showVerificationSuccess();
                    }
                })
                .catch(error => {
                    console.error('Verification poll error:', error);
                });
            }, 3000);
        }

        // Handle questionnaire submission
        document.getElementById('questionnaire-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const answers = Object.fromEntries(formData);
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Verifying answers...';
            
            fetch('/api/verify-questionnaire', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    method: 'questionnaire',
                    answers: answers
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showVerificationSuccess();
                } else {
                    showVerificationError(data.message || 'Some answers do not match our records. Please verify and try again.');
                }
            })
            .catch(error => {
                console.error('Questionnaire verification error:', error);
                showVerificationError('Verification failed. Please try again.');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        });

        // Show verification success
        function showVerificationSuccess() {
            // Hide method selection
            document.getElementById('verification-methods').style.display = 'none';
            document.getElementById('phone-photo-container').classList.add('hidden');
            document.getElementById('qr-container').classList.add('hidden');
            document.getElementById('questionnaire-container').classList.add('hidden');
            
            // Show success message
            document.getElementById('verification-progress').classList.remove('hidden');
            
            // Update progress indicator
            const progressItems = document.querySelectorAll('.flex.items-center');
            progressItems[2].querySelector('div').classList.remove('bg-gray-300');
            progressItems[2].querySelector('div').classList.add('bg-green-500');
            progressItems[2].querySelector('span').innerHTML = `
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            `;
        }

        // Show verification error
        function showVerificationError(message) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'mt-6 p-4 bg-red-50 border border-red-200 rounded-lg';
            errorDiv.innerHTML = `
                <div class="flex">
                    <svg class="w-5 h-5 text-red-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <h3 class="text-sm font-medium text-red-800">Verification Failed</h3>
                        <p class="text-sm text-red-700 mt-1">${message}</p>
                        <button onclick="location.reload()" class="mt-2 text-sm text-red-600 hover:text-red-800 underline">
                            Try Again
                        </button>
                    </div>
                </div>
            `;
            
            // Insert error after the current container
            const containers = ['phone-photo-container', 'qr-container', 'questionnaire-container'];
            for (const containerId of containers) {
                const container = document.getElementById(containerId);
                if (!container.classList.contains('hidden')) {
                    container.appendChild(errorDiv);
                    break;
                }
            }
        }

        // Redirect to dashboard
        function redirectToDashboard() {
            window.location.href = '/dashboard';
        }

        // Logout function
        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = '/logout';
            }
        }
    </script>

    <style>
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</body>
</html>,
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                        'poppins': ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 font-inter min-h-screen">

    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="text-2xl font-bold font-poppins text-black">
                    Lead<span class="text-brand-red">Generator</span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">Welcome, {{ auth()->user()->first_name ?? 'User' }}</span>
                    <button onclick="logout()" class="text-sm text-brand-red hover:text-red-700">
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="text-center mb-12">
            <div class="mx-auto w-16 h-16 bg-brand-red/10 rounded-full flex items-center justify-center mb-6">
                <svg class="w-8 h-8 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">NIDA Verification</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Complete your identity verification by capturing your fingerprint. This ensures secure access to your account.
            </p>
        </div>

        <!-- Verification Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            
            <!-- Device Detection -->
            <div id="device-notice" class="bg-blue-50 border-b border-blue-200 p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-blue-800">Device Detected</p>
                        <p id="device-type" class="text-sm text-blue-600"></p>
                    </div>
                </div>
            </div>

            <!-- Verification Methods -->
            <div class="p-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6 text-center">Choose Verification Method</h2>
                
                <div class="grid md:grid-cols-2 gap-6 mb-8">
                    
                    <!-- QR Code Method (Desktop) -->
                    <div id="qr-method" class="verification-method border-2 border-gray-200 rounded-xl p-6 cursor-pointer hover:border-brand-red transition-all duration-300" onclick="selectMethod('qr-code')">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-brand-red/10 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">QR Code Verification</h3>
                            <p class="text-gray-600 text-sm mb-4">Use your phone to scan QR code and capture fingerprint</p>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <span class="text-xs text-gray-500">Recommended for desktop users</span>
                            </div>
                        </div>
                    </div>

                    <!-- Direct Capture Method (Mobile) -->
                    <div id="direct-method" class="verification-method border-2 border-gray-200 rounded-xl p-6 cursor-pointer hover:border-brand-red transition-all duration-300" onclick="selectMethod('direct-capture')">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-brand-red/10 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0h10v16a2 2 0 01-2 2H9a2 2 0 01-2-2V4z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Direct Capture</h3>
                            <p class="text-gray-600 text-sm mb-4">Use your device camera to capture fingerprint directly</p>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <span class="text-xs text-gray-500">Recommended for mobile users</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- QR Code Container -->
                <div id="qr-container" class="hidden">
                    <div class="text-center mb-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Scan QR Code</h3>
                        <p class="text-gray-600">Use your phone's camera to scan the code below</p>
                    </div>
                    
                    <div class="bg-gray-50 rounded-xl p-8 text-center">
                        <div class="inline-block bg-white p-6 rounded-xl shadow-sm">
                            <div id="qr-code" class="w-64 h-64 border-2 border-gray-300 rounded-lg flex items-center justify-center">
                                <div class="text-center">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-brand-red mx-auto mb-2"></div>
                                    <p class="text-sm text-gray-500">Generating QR Code...</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6">
                            <p class="text-sm text-gray-600 mb-4">
                                Scan this code with your phone to continue verification on your mobile device
                            </p>
                            <button id="regenerate-qr" onclick="generateQRCode()" class="text-brand-red hover:text-red-700 font-medium">
                                Regenerate Code
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Direct Capture Container -->
                <div id="capture-container" class="hidden">
                    <div class="text-center mb-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Fingerprint Capture</h3>
                        <p class="text-gray-600">Position your finger as shown below to capture your fingerprint</p>
                    </div>
                    
                    <div class="bg-gray-50 rounded-xl p-8">
                        <!-- Capture Interface -->
                        <div class="max-w-md mx-auto">
                            <div class="relative">
                                <!-- Fingerprint Scanner Visualization -->
                                <div class="w-64 h-64 mx-auto bg-gradient-to-br from-brand-red/10 to-brand-red/5 rounded-full border-4 border-dashed border-brand-red/30 flex items-center justify-center relative overflow-hidden">
                                    <!-- Scanning Animation -->
                                    <div id="scan-animation" class="hidden absolute inset-0">
                                        <div class="absolute w-full h-1 bg-brand-red opacity-60 animate-pulse" style="top: 50%; animation: scan 2s linear infinite;"></div>
                                    </div>
                                    
                                    <!-- Status Display -->
                                    <div id="capture-status" class="text-center z-10">
                                        <svg class="w-20 h-20 text-brand-red mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0h10a2 2 0 012 2v12a2 2 0 01-2 2H9a2 2 0 01-2-2V6a2 2 0 012-2z"></path>
                                        </svg>
                                        <p class="text-brand-red font-semibold">Place finger here</p>
                                        <p class="text-gray-500 text-sm mt-1">Ready to scan</p>
                                    </div>
                                </div>
                                
                                <!-- Instructions -->
                                <div class="mt-6 bg-white rounded-lg p-4 border border-gray-200">
                                    <h4 class="font-semibold text-gray-900 mb-2">Instructions:</h4>
                                    <ol class="text-sm text-gray-600 space-y-1">
                                        <li>1. Place your index finger flat on the scanner</li>
                                        <li>2. Keep your finger still during scanning</li>
                                        <li>3. Wait for the green checkmark</li>
                                        <li>4. Remove your finger when prompted</li>
                                    </ol>
                                </div>
                            </div>
                            
                            <!-- Capture Controls -->
                            <div class="mt-8 text-center">
                                <button 
                                    id="start-capture" 
                                    onclick="startCapture()" 
                                    class="bg-brand-red text-white px-8 py-3 rounded-lg font-semibold hover:bg-red-700 focus:ring-4 focus:ring-brand-red/30 transition-all duration-300 transform hover:scale-105"
                                >
                                    Start Fingerprint Capture
                                </button>
                                <p class="text-xs text-gray-500 mt-2">
                                    Your biometric data is encrypted and secure
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Progress Status -->
                <div id="verification-progress" class="hidden mt-8 p-6 bg-green-50 border border-green-200 rounded-xl">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <div>
                            <p class="font-semibold text-green-800">Verification Complete!</p>
                            <p class="text-green-700 text-sm">Your identity has been successfully verified.</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button onclick="redirectToDashboard()" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            Continue to Dashboard
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Notice -->
        <div class="mt-8 text-center">
            <div class="inline-flex items-center space-x-2 text-sm text-gray-500 bg-white px-4 py-2 rounded-lg border border-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                <span>Your biometric data is encrypted with AES-256 encryption</span>
            </div>
        </div>
    </main>

    <!-- JavaScript -->
    <script>
        let selectedMethod = '';
        let verificationToken = '';
        let captureInProgress = false;

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            detectDevice();
            // Auto-select method based on device type for better UX
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            if (isMobile) {
                document.getElementById('direct-method').click();
            }
        });

        // Device detection
        function detectDevice() {
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            const deviceTypeEl = document.getElementById('device-type');
            
            if (isMobile) {
                deviceTypeEl.textContent = 'Mobile device detected - Direct capture is recommended';
                // Reorder methods to show direct capture first on mobile
                const directMethod = document.getElementById('direct-method');
                const qrMethod = document.getElementById('qr-method');
                directMethod.parentNode.insertBefore(directMethod, qrMethod);
            } else {
                deviceTypeEl.textContent = 'Desktop detected - QR code scanning is recommended';
            }
        }

        // Method selection
        function selectMethod(method) {
            selectedMethod = method;
            
            // Update UI
            document.querySelectorAll('.verification-method').forEach(el => {
                el.classList.remove('border-brand-red', 'bg-red-50');
                el.classList.add('border-gray-200');
            });
            
            // Hide all containers
            document.getElementById('qr-container').classList.add('hidden');
            document.getElementById('capture-container').classList.add('hidden');
            
            // Show selected method
            if (method === 'qr-code') {
                document.getElementById('qr-method').classList.remove('border-gray-200');
                document.getElementById('qr-method').classList.add('border-brand-red', 'bg-red-50');
                document.getElementById('qr-container').classList.remove('hidden');
                generateQRCode();
            } else if (method === 'direct-capture') {
                document.getElementById('direct-method').classList.remove('border-gray-200');
                document.getElementById('direct-method').classList.add('border-brand-red', 'bg-red-50');
                document.getElementById('capture-container').classList.remove('hidden');
            }
        }

        // Generate QR Code
        function generateQRCode() {
            const qrCodeEl = document.getElementById('qr-code');
            
            // Generate verification token
            verificationToken = generateToken();
            
            // Simulate QR code generation
            setTimeout(() => {
                qrCodeEl.innerHTML = `
                    <div class="text-center">
                        <div class="text-xs text-gray-500 mb-2">Token: ${verificationToken}</div>
                        <div class="bg-black w-48 h-48 mx-auto rounded-lg relative" style="
                            background-image: 
                                repeating-linear-gradient(0deg, black 0px, black 3px, white 3px, white 6px),
                                repeating-linear-gradient(90deg, black 0px, black 3px, white 3px, white 6px);
                            background-size: 12px 12px;
                        ">
                            <div class="absolute inset-4 bg-white rounded flex items-center justify-center">
                                <div class="w-8 h-8 bg-black"></div>
                            </div>
                        </div>
                        <div class="text-xs text-gray-500 mt-2">Scan to continue on mobile</div>
                    </div>
                `;
                
                // Start checking for mobile scan
                checkForMobileScan();
            }, 1500);
        }

        // Generate random token
        function generateToken() {
            return Math.random().toString(36).substring(2, 8).toUpperCase();
        }

        // Check for mobile scan (simulation)
        function checkForMobileScan() {
            // In real implementation, this would use WebSockets or polling
            // For demo, we'll simulate a scan after random delay
            setTimeout(() => {
                if (selectedMethod === 'qr-code') {
                    simulateVerificationSuccess();
                }
            }, Math.random() * 10000 + 5000); // Random delay between 5-15 seconds
        }

        // Start fingerprint capture
        function startCapture() {
            if (captureInProgress) return;
            
            captureInProgress = true;
            const button = document.getElementById('start-capture');
            const status = document.getElementById('capture-status');
            const animation = document.getElementById('scan-animation');
            
            // Update button state
            button.disabled = true;
            button.textContent = 'Capturing...';
            button.classList.add('opacity-50', 'cursor-not-allowed');
            
            // Show scanning animation
            animation.classList.remove('hidden');
            
            // Update status
            status.innerHTML = `
                <svg class="w-20 h-20 text-brand-red mx-auto mb-3 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                <p class="text-brand-red font-semibold">Scanning...</p>
                <p class="text-gray-500 text-sm mt-1">Keep finger still</p>
            `;
            
            // Simulate capture process
            setTimeout(() => {
                completeCapture();
            }, 4000);
        }

        // Complete fingerprint capture
        function completeCapture() {
            const status = document.getElementById('capture-status');
            const animation = document.getElementById('scan-animation');
            
            // Hide animation
            animation.classList.add('hidden');
            
            // Show success
            status.innerHTML = `
                <svg class="w-20 h-20 text-green-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <p class="text-green-600 font-semibold">Capture Successful!</p>
                <p class="text-gray-500 text-sm mt-1">Fingerprint verified</p>
            `;
            
            captureInProgress = false;
            
            // Process verification
            setTimeout(() => {
                simulateVerificationSuccess();
            }, 1500);
        }

        // Simulate verification success
        function simulateVerificationSuccess() {
            // Hide method containers
            document.querySelector('.grid').style.display = 'none';
            document.getElementById('qr-container').classList.add('hidden');
            document.getElementById('capture-container').classList.add('hidden');
            
            // Show success message
            document.getElementById('verification-progress').classList.remove('hidden');
            
            // In real implementation, you would send data to server here
            sendVerificationToServer();
        }

        // Send verification data to server
        function sendVerificationToServer() {
            const verificationData = {
                method: selectedMethod,
                token: verificationToken,
                timestamp: new Date().toISOString(),
                fingerprint_hash: generateFingerprintHash()
            };
            
            // Simulate API call
            fetch('/api/verify-nida', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify(verificationData)
            })
            .then(response => response.json())
            .then(data => {
                console.log('Verification successful:', data);
            })
            .catch(error => {
                console.error('Verification error:', error);
                // Show error message
                alert('Verification failed. Please try again.');
                location.reload();
            });
        }

        // Generate fingerprint hash (simulation)
        function generateFingerprintHash() {
            return 'fp_' + Math.random().toString(36).substring(2, 15);
        }

        // Redirect to dashboard
        function redirectToDashboard() {
            window.location.href = '/dashboard';
        }

        // Logout function
        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = '/logout';
            }
        }

        // Add CSS for scan animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes scan {
                0% { top: 0; }
                50% { top: 90%; }
                100% { top: 0; }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>