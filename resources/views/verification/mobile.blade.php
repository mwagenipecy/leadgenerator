{{-- resources/views/verification/mobile.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mobile Verification - Lead Generator</title>
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
                        'brand-dark-red': '#A00E11',
                    },
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
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="text-xl font-bold font-poppins text-black">
                        Lead<span class="text-brand-red">Generator</span>
                    </div>
                    <div class="text-sm text-gray-500">
                        Mobile Verification
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 px-4 py-8">
            <div class="max-w-md mx-auto">
                
                <!-- Welcome Section -->
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-brand-red/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">Complete Verification</h1>
                    <p class="text-gray-600 text-sm">
                        Hello {{ $verification->user->full_name }}, please capture your photo to complete verification.
                    </p>
                </div>

                <!-- Verification Form -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <form id="mobile-verification-form" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Photo Capture -->
                        <div class="text-center">
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Capture Your Photo</h3>
                                <p class="text-gray-600 text-sm">Choose the type of photo you want to capture</p>
                            </div>

                            <!-- Photo Options -->
                            <div class="space-y-4 mb-6">
                                <div>
                                    <input type="file" id="id-document" name="photo" accept="image/*" capture="environment" class="hidden" data-type="id_document">
                                    <button 
                                        type="button" 
                                        onclick="document.getElementById('id-document').click()"
                                        class="w-full bg-brand-red text-white px-6 py-4 rounded-lg font-semibold hover:bg-brand-dark-red transition-colors flex items-center justify-center space-x-3"
                                    >
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 114 0v2m-4 0a2 2 0 104 0"/>
                                        </svg>
                                        <span>📄 Capture ID Document</span>
                                    </button>
                                </div>
                                
                                <div>
                                    <input type="file" id="fingerprint" name="photo" accept="image/*" capture="environment" class="hidden" data-type="fingerprint">
                                    <button 
                                        type="button" 
                                        onclick="document.getElementById('fingerprint').click()"
                                        class="w-full bg-gray-800 text-white px-6 py-4 rounded-lg font-semibold hover:bg-gray-700 transition-colors flex items-center justify-center space-x-3"
                                    >
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0h10v16a2 2 0 01-2 2H9a2 2 0 01-2-2V4z"/>
                                        </svg>
                                        <span>👆 Capture Fingerprint</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Photo Preview -->
                            <div id="photo-preview" class="hidden mb-6">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <img id="preview-image" src="" alt="Captured photo" class="w-full h-64 object-cover rounded-lg border">
                                    <p class="text-sm text-gray-600 mt-2">Photo captured successfully</p>
                                </div>
                            </div>

                            <!-- Error Message -->
                            <div id="error-message" class="hidden mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-red-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <h3 class="text-sm font-medium text-red-800">Error</h3>
                                        <p id="error-text" class="text-sm text-red-700 mt-1"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button 
                                type="submit" 
                                id="submit-btn"
                                class="w-full bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                disabled
                            >
                                <span id="submit-text">Complete Verification</span>
                            </button>
                        </div>
                    </form>

                    <!-- Instructions -->
                    <div class="mt-6 bg-blue-50 rounded-lg p-4">
                        <h4 class="font-semibold text-blue-900 mb-2">Guidelines:</h4>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>• Ensure good lighting</li>
                            <li>• Keep document flat and steady</li>
                            <li>• Make sure text is clearly visible</li>
                            <li>• Avoid shadows and glare</li>
                        </ul>
                    </div>
                </div>

                <!-- Connection Status -->
                <div class="mt-6 text-center">
                    <div class="inline-flex items-center space-x-2 text-sm text-gray-500 bg-white px-4 py-2 rounded-lg border border-gray-200">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        <span>Connected to desktop session</span>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- JavaScript -->
    <script>
        let selectedPhotoType = '';
        let capturedPhoto = null;

        // Handle file input changes
        document.getElementById('id-document').addEventListener('change', function(e) {
            handlePhotoCapture(e, 'id_document');
        });

        document.getElementById('fingerprint').addEventListener('change', function(e) {
            handlePhotoCapture(e, 'fingerprint');
        });

        function handlePhotoCapture(event, type) {
            const file = event.target.files[0];
            if (!file) return;

            selectedPhotoType = type;
            capturedPhoto = file;

            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-image').src = e.target.result;
                document.getElementById('photo-preview').classList.remove('hidden');
                document.getElementById('submit-btn').disabled = false;
                document.getElementById('error-message').classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }

        function showError(message) {
            document.getElementById('error-text').textContent = message;
            document.getElementById('error-message').classList.remove('hidden');
        }

        function hideError() {
            document.getElementById('error-message').classList.add('hidden');
        }

        // Handle form submission
        document.getElementById('mobile-verification-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!capturedPhoto || !selectedPhotoType) {
                showError('Please capture a photo first');
                return;
            }

            const formData = new FormData();
            formData.append('photo', capturedPhoto);
            formData.append('photo_type', selectedPhotoType);
            formData.append('_token', document.querySelector('input[name="_token"]').value);

            // Update UI to show processing
            const submitBtn = document.getElementById('submit-btn');
            const submitText = document.getElementById('submit-text');
            
            submitBtn.disabled = true;
            submitText.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
                Processing...
            `;
            hideError();

            // Submit verification
            fetch(window.location.href, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    document.body.innerHTML = `
                        <div class="min-h-screen flex items-center justify-center bg-gray-50 px-4">
                            <div class="text-center bg-white p-8 rounded-2xl shadow-lg max-w-md w-full">
                                <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <h2 class="text-2xl font-bold text-green-800 mb-2">Verification Complete!</h2>
                                <p class="text-green-700 mb-6">Your identity has been successfully verified.</p>
                                <p class="text-gray-600 text-sm">You can now return to your computer to continue.</p>
                            </div>
                        </div>
                    `;
                } else {
                    showError(data.message || 'Verification failed. Please try again.');
                    submitBtn.disabled = false;
                    submitText.textContent = 'Complete Verification';
                }
            })
            .catch(error => {
                console.error('Verification error:', error);
                showError('An error occurred. Please try again.');
                submitBtn.disabled = false;
                submitText.textContent = 'Complete Verification';
            });
        });
    </script>
</body>
</html>