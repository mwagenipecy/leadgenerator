<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-red-50/30 py-8">
    <!-- Header Navigation (Simple) -->
    <div class="bg-white/80 backdrop-blur-sm border-b border-gray-100 shadow-sm mb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img src="{{ asset('/landing/redlogo.png') }}" alt="Lead Generator Logo" class="h-12 w-auto">
                </div>
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 text-sm font-medium transition-colors duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Logout
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 mb-6">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-red-600 to-red-700 rounded-xl flex items-center justify-center shadow-lg shadow-red-600/20">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Company KYC Verification</h1>
                    <p class="text-gray-500 mt-1">Complete the steps below to verify your company account</p>
                </div>
            </div>
            
            <!-- Progress indicator -->
            <div class="flex items-center gap-2 mt-6">
                @if($isTanzania)
                    <!-- Tanzania: 3 steps (NIDA, Documents, Complete) -->
                    <div class="flex-1 h-2 rounded-full {{ $step >= 1 ? 'bg-red-600' : 'bg-gray-200' }} transition-colors duration-300"></div>
                    <div class="flex-1 h-2 rounded-full {{ $step >= 2 ? 'bg-red-600' : 'bg-gray-200' }} transition-colors duration-300"></div>
                    <div class="flex-1 h-2 rounded-full {{ $step >= 3 ? 'bg-red-600' : 'bg-gray-200' }} transition-colors duration-300"></div>
                @else
                    <!-- Non-Tanzania: 2 steps (Documents, Complete) - Skip NIDA -->
                    <div class="flex-1 h-2 rounded-full {{ $step >= 2 ? 'bg-red-600' : 'bg-gray-200' }} transition-colors duration-300"></div>
                    <div class="flex-1 h-2 rounded-full {{ $step >= 3 ? 'bg-red-600' : 'bg-gray-200' }} transition-colors duration-300"></div>
                @endif
            </div>
        </div>

        @if (session()->has('success'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3">
                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <p class="text-red-800 text-sm font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3">
                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-red-800 text-sm font-medium">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Step 1: NIDA Verification (Tanzania only) -->
        @if($isTanzania && $step === 1)
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 mb-6" wire:poll.5s="checkNidaStatus">
            <div class="flex items-center gap-4 mb-6">
                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-red-600 to-red-700 text-white rounded-xl flex items-center justify-center font-bold text-lg shadow-lg shadow-red-600/20">
                    1
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">NIDA Verification</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Verify your identity using NIDA</p>
                </div>
            </div>

            @if($nidaVerificationCompleted)
                <div class="p-5 bg-red-50 border border-red-200 rounded-xl mb-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-red-800 font-semibold">NIDA Verification Completed</span>
                            <p class="text-sm text-red-600">Your identity has been verified successfully</p>
                        </div>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-5">You can now proceed to upload your company documents.</p>
                <button wire:click="$set('step', 2)" class="bg-gradient-to-r from-red-600 to-red-700 text-white px-6 py-3 rounded-xl hover:from-red-700 hover:to-red-600 transition-all duration-300 font-semibold shadow-lg shadow-red-600/30 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                    Continue to Document Upload
                </button>
            @else
                <div class="p-5 bg-blue-50 border border-blue-200 rounded-xl mb-5">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-blue-800 text-sm">Please complete NIDA verification to proceed with company verification. You will be redirected to the NIDA verification page.</p>
                    </div>
                </div>
                
                @if(empty(auth()->user()->company_contact_nida) && empty(auth()->user()->nida_number))
                    <div class="mb-5 p-4 bg-amber-50 border border-amber-200 rounded-xl flex items-start gap-3">
                        <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <p class="text-amber-800 text-sm">
                            <strong>Warning:</strong> NIDA number is missing. Please ensure your registration information is complete. 
                            Your representative NIDA number should be: <strong>{{ auth()->user()->company_contact_nida ?? 'Not set' }}</strong>
                        </p>
                    </div>
                @endif
                
                <div class="flex flex-wrap items-center gap-3">
                    <a 
                        href="{{ route('verification.options') }}"
                        class="bg-gradient-to-r from-red-600 to-red-700 text-white px-6 py-3 rounded-xl hover:from-red-700 hover:to-red-600 transition-all duration-300 font-semibold shadow-lg shadow-red-600/30 inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        Start NIDA Verification
                    </a>
                    <button 
                        type="button"
                        wire:click="refreshStatus" 
                        wire:loading.attr="disabled"
                        class="bg-gray-100 text-gray-700 px-6 py-3 rounded-xl hover:bg-gray-200 transition-all duration-200 disabled:opacity-50 font-medium flex items-center gap-2">
                        <span wire:loading.remove wire:target="refreshStatus">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </span>
                        <span wire:loading.remove wire:target="refreshStatus">Check Status</span>
                        <span wire:loading wire:target="refreshStatus" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Checking...
                        </span>
                    </button>
                </div>
                <p class="text-xs text-gray-400 mt-4 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Status is automatically checked every 5 seconds. You can also click "Check Status" after completing NIDA verification.
                </p>
            @endif
        </div>
        @endif

        <!-- Step 2: Document Upload -->
        @if($step === 2)
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 mb-6">
            <div class="flex items-center gap-4 mb-8">
                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-red-600 to-red-700 text-white rounded-xl flex items-center justify-center font-bold text-lg shadow-lg shadow-red-600/20">
                    {{ $isTanzania ? '2' : '1' }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Upload Required Documents</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Upload all required documents for verification</p>
                </div>
            </div>

            <div class="space-y-5">
                @if($isTanzania)
                    <!-- Tanzania: BRELA and TIN Certificate -->
                    <!-- BRELA Document -->
                    <div class="border-2 border-gray-100 rounded-xl p-5 bg-white hover:border-red-600/30 transition-colors duration-200">
                        <div class="flex items-start gap-3 mb-4">
                            <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-800">BRELA Document <span class="text-red-500">*</span></label>
                                <p class="text-xs text-gray-500 mt-0.5">Upload your BRELA registration document (PDF, JPG, PNG - Max 5MB)</p>
                            </div>
                        </div>
                        
                        @if(isset($uploadedDocuments['brela']))
                            <div class="flex items-center justify-between p-4 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">{{ $uploadedDocuments['brela']['name'] }}</p>
                                        <p class="text-xs text-red-600">Uploaded successfully</p>
                                    </div>
                                </div>
                                <button wire:click="removeDocument('brela')" class="text-red-500 hover:text-red-700 text-sm font-medium flex items-center gap-1 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Remove
                                </button>
                            </div>
                        @else
                            <div class="relative" wire:loading.class="pointer-events-none" wire:target="brelaDocument">
                                <label class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 hover:border-red-600 transition-all duration-200">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6" wire:loading.remove wire:target="brelaDocument">
                                        <svg class="w-10 h-10 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                        <p class="text-sm text-gray-600 font-medium">Click to upload or drag and drop</p>
                                        <p class="text-xs text-gray-400 mt-1">PDF, JPG, JPEG, PNG (Max 5MB)</p>
                                    </div>
                                    <div wire:loading wire:target="brelaDocument" class="flex flex-col items-center justify-center">
                                        <svg class="animate-spin h-8 w-8 text-red-600 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <p class="text-sm text-gray-600">Uploading document...</p>
                                    </div>
                                    <input type="file" wire:model="brelaDocument" accept=".pdf,.jpg,.jpeg,.png" class="hidden">
                                </label>
                            </div>
                            @error('brelaDocument') <p class="text-red-500 text-xs mt-2 flex items-center gap-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</p> @enderror
                        @endif
                    </div>

                    <!-- TIN Certificate -->
                    <div class="border-2 border-gray-100 rounded-xl p-5 bg-white hover:border-red-600/30 transition-colors duration-200">
                        <div class="flex items-start gap-3 mb-4">
                            <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                                </svg>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-800">TIN Certificate <span class="text-red-500">*</span></label>
                                <p class="text-xs text-gray-500 mt-0.5">Upload your TIN certificate document (PDF, JPG, PNG - Max 5MB)</p>
                            </div>
                        </div>
                        
                        @if(isset($uploadedDocuments['tin_certificate']))
                            <div class="flex items-center justify-between p-4 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">{{ $uploadedDocuments['tin_certificate']['name'] }}</p>
                                        <p class="text-xs text-red-600">Uploaded successfully</p>
                                    </div>
                                </div>
                                <button wire:click="removeDocument('tin_certificate')" class="text-red-500 hover:text-red-700 text-sm font-medium flex items-center gap-1 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Remove
                                </button>
                            </div>
                        @else
                            <div class="relative" wire:loading.class="pointer-events-none" wire:target="tinCertificate">
                                <label class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 hover:border-red-600 transition-all duration-200">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6" wire:loading.remove wire:target="tinCertificate">
                                        <svg class="w-10 h-10 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                        <p class="text-sm text-gray-600 font-medium">Click to upload or drag and drop</p>
                                        <p class="text-xs text-gray-400 mt-1">PDF, JPG, JPEG, PNG (Max 5MB)</p>
                                    </div>
                                    <div wire:loading wire:target="tinCertificate" class="flex flex-col items-center justify-center">
                                        <svg class="animate-spin h-8 w-8 text-red-600 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <p class="text-sm text-gray-600">Uploading document...</p>
                                    </div>
                                    <input type="file" wire:model="tinCertificate" accept=".pdf,.jpg,.jpeg,.png" class="hidden">
                                </label>
                            </div>
                            @error('tinCertificate') <p class="text-red-500 text-xs mt-2 flex items-center gap-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</p> @enderror
                        @endif
                    </div>
                @else
                    <!-- Non-Tanzania: Passport, Company Documents, Personal KYC -->
                    <!-- Passport -->
                    <div class="border-2 border-gray-100 rounded-xl p-5 bg-white hover:border-red-600/30 transition-colors duration-200">
                        <div class="flex items-start gap-3 mb-4">
                            <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                </svg>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-800">Passport <span class="text-red-500">*</span></label>
                                <p class="text-xs text-gray-500 mt-0.5">Upload a copy of your passport (PDF, JPG, PNG - Max 5MB)</p>
                            </div>
                        </div>
                        
                        @if(isset($uploadedDocuments['passport']))
                            <div class="flex items-center justify-between p-4 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">{{ $uploadedDocuments['passport']['name'] }}</p>
                                        <p class="text-xs text-red-600">Uploaded successfully</p>
                                    </div>
                                </div>
                                <button wire:click="removeDocument('passport')" class="text-red-500 hover:text-red-700 text-sm font-medium flex items-center gap-1 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Remove
                                </button>
                            </div>
                        @else
                            <div class="relative" wire:loading.class="pointer-events-none" wire:target="passportDocument">
                                <label class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 hover:border-red-600 transition-all duration-200">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6" wire:loading.remove wire:target="passportDocument">
                                        <svg class="w-10 h-10 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                        <p class="text-sm text-gray-600 font-medium">Click to upload or drag and drop</p>
                                        <p class="text-xs text-gray-400 mt-1">PDF, JPG, JPEG, PNG (Max 5MB)</p>
                                    </div>
                                    <div wire:loading wire:target="passportDocument" class="flex flex-col items-center justify-center">
                                        <svg class="animate-spin h-8 w-8 text-red-600 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <p class="text-sm text-gray-600">Uploading document...</p>
                                    </div>
                                    <input type="file" wire:model="passportDocument" accept=".pdf,.jpg,.jpeg,.png" class="hidden">
                                </label>
                            </div>
                            @error('passportDocument') <p class="text-red-500 text-xs mt-2 flex items-center gap-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</p> @enderror
                        @endif
                    </div>

                    <!-- Company Documents -->
                    <div class="border-2 border-gray-100 rounded-xl p-5 bg-white hover:border-red-600/30 transition-colors duration-200">
                        <div class="flex items-start gap-3 mb-4">
                            <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-800">Company Documents <span class="text-red-500">*</span></label>
                                <p class="text-xs text-gray-500 mt-0.5">Upload company registration documents (PDF, JPG, PNG - Max 5MB)</p>
                            </div>
                        </div>
                        
                        @if(isset($uploadedDocuments['company_documents']))
                            <div class="flex items-center justify-between p-4 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">{{ $uploadedDocuments['company_documents']['name'] }}</p>
                                        <p class="text-xs text-red-600">Uploaded successfully</p>
                                    </div>
                                </div>
                                <button wire:click="removeDocument('company_documents')" class="text-red-500 hover:text-red-700 text-sm font-medium flex items-center gap-1 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Remove
                                </button>
                            </div>
                        @else
                            <div class="relative" wire:loading.class="pointer-events-none" wire:target="companyDocuments">
                                <label class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 hover:border-red-600 transition-all duration-200">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6" wire:loading.remove wire:target="companyDocuments">
                                        <svg class="w-10 h-10 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                        <p class="text-sm text-gray-600 font-medium">Click to upload or drag and drop</p>
                                        <p class="text-xs text-gray-400 mt-1">PDF, JPG, JPEG, PNG (Max 5MB)</p>
                                    </div>
                                    <div wire:loading wire:target="companyDocuments" class="flex flex-col items-center justify-center">
                                        <svg class="animate-spin h-8 w-8 text-red-600 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <p class="text-sm text-gray-600">Uploading document...</p>
                                    </div>
                                    <input type="file" wire:model="companyDocuments" accept=".pdf,.jpg,.jpeg,.png" class="hidden">
                                </label>
                            </div>
                            @error('companyDocuments') <p class="text-red-500 text-xs mt-2 flex items-center gap-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</p> @enderror
                        @endif
                    </div>

                    <!-- Personal KYC -->
                    <div class="border-2 border-gray-100 rounded-xl p-5 bg-white hover:border-red-600/30 transition-colors duration-200">
                        <div class="flex items-start gap-3 mb-4">
                            <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-800">Personal KYC Documents <span class="text-red-500">*</span></label>
                                <p class="text-xs text-gray-500 mt-0.5">Upload personal KYC documents - ID, proof of address, etc. (PDF, JPG, PNG - Max 5MB)</p>
                            </div>
                        </div>
                        
                        @if(isset($uploadedDocuments['personal_kyc']))
                            <div class="flex items-center justify-between p-4 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">{{ $uploadedDocuments['personal_kyc']['name'] }}</p>
                                        <p class="text-xs text-red-600">Uploaded successfully</p>
                                    </div>
                                </div>
                                <button wire:click="removeDocument('personal_kyc')" class="text-red-500 hover:text-red-700 text-sm font-medium flex items-center gap-1 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Remove
                                </button>
                            </div>
                        @else
                            <div class="relative" wire:loading.class="pointer-events-none" wire:target="personalKyc">
                                <label class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 hover:border-red-600 transition-all duration-200">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6" wire:loading.remove wire:target="personalKyc">
                                        <svg class="w-10 h-10 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                        <p class="text-sm text-gray-600 font-medium">Click to upload or drag and drop</p>
                                        <p class="text-xs text-gray-400 mt-1">PDF, JPG, JPEG, PNG (Max 5MB)</p>
                                    </div>
                                    <div wire:loading wire:target="personalKyc" class="flex flex-col items-center justify-center">
                                        <svg class="animate-spin h-8 w-8 text-red-600 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <p class="text-sm text-gray-600">Uploading document...</p>
                                    </div>
                                    <input type="file" wire:model="personalKyc" accept=".pdf,.jpg,.jpeg,.png" class="hidden">
                                </label>
                            </div>
                            @error('personalKyc') <p class="text-red-500 text-xs mt-2 flex items-center gap-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</p> @enderror
                        @endif
                    </div>
                @endif

                <!-- Submit Button -->
                <div class="pt-6">
                    <button wire:click="completeKyc" wire:loading.attr="disabled" wire:target="completeKyc" class="w-full bg-gradient-to-r from-red-600 to-red-700 text-white px-6 py-4 rounded-xl hover:from-red-700 hover:to-red-600 transition-all duration-300 font-semibold text-lg shadow-lg shadow-red-600/30 hover:shadow-xl hover:shadow-red-600/40 disabled:opacity-50 flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="completeKyc">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                        <span wire:loading.remove wire:target="completeKyc">Submit for Verification</span>
                        <span wire:loading wire:target="completeKyc" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Submitting...
                        </span>
                    </button>
                </div>
            </div>
        </div>
        @endif

        <!-- Step 3: Pending Verification -->
        @if($step === 3)
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-10 mb-6 text-center">
            <div class="mb-6">
                <div class="w-20 h-20 bg-gradient-to-br from-amber-100 to-amber-200 rounded-2xl flex items-center justify-center mx-auto shadow-lg shadow-amber-100">
                    <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-3">Verification Pending</h2>
            <p class="text-gray-600 mb-4 max-w-md mx-auto">Your company verification documents have been submitted successfully. Our admin team will review your documents and verify your account shortly.</p>
            
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 max-w-md mx-auto">
                <div class="flex items-center gap-2 justify-center text-blue-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="text-sm font-medium">You will be notified once your account is verified</span>
                </div>
            </div>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 px-6 py-3 rounded-xl hover:bg-gray-200 transition-all duration-200 font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
        @endif
    </div>
</div>
