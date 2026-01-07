<div class="min-h-screen bg-gray-50 py-8">
    <!-- Header Navigation (Simple) -->
    <div class="bg-white border-b border-gray-200 mb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-sidebar-green mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span class="text-xl font-bold text-gray-900">Lead<span class="text-sidebar-green">Generator</span></span>
                </div>
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-gray-900 text-sm font-medium">
                            Logout
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Company KYC Verification</h1>
            <p class="text-gray-600">Please complete the following steps to verify your company account</p>
        </div>

        @if (session()->has('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-800 text-sm">{{ session('success') }}</p>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-4 p-4 bg-sidebar-green-50 border border-sidebar-green-200 rounded-lg">
                <p class="text-sidebar-green-800 text-sm">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Step 1: NIDA Verification (Tanzania only) -->
        @if($isTanzania && $step === 1)
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6" wire:poll.5s="checkNidaStatus">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0 w-10 h-10 bg-sidebar-green text-white rounded-full flex items-center justify-center font-bold">
                    1
                </div>
                <div class="ml-4">
                    <h2 class="text-lg font-semibold text-gray-900">NIDA Verification</h2>
                    <p class="text-sm text-gray-600">Verify your identity using NIDA</p>
                </div>
            </div>

            @if($nidaVerificationCompleted)
                <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-green-800 font-medium">NIDA Verification Completed</span>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-sm text-gray-600 mb-4">You can now proceed to upload your company documents.</p>
                    <button wire:click="$set('step', 2)" class="bg-sidebar-green text-white px-6 py-2 rounded-lg hover:bg-brand-dark-red transition">
                        Continue to Document Upload
                    </button>
                </div>
            @else
                <div class="mt-4">
                    <p class="text-gray-700 mb-4">Please complete NIDA verification to proceed with company verification. You will be redirected to the NIDA verification page.</p>
                    
                    @if(empty(auth()->user()->company_contact_nida) && empty(auth()->user()->nida_number))
                        <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-yellow-800 text-sm">
                                <strong>Warning:</strong> NIDA number is missing. Please ensure your registration information is complete. 
                                Your representative NIDA number should be: <strong>{{ auth()->user()->company_contact_nida ?? 'Not set' }}</strong>
                            </p>
                        </div>
                    @endif
                    
                    <div class="flex items-center space-x-3">
                        <a 
                            href="{{ route('verification.options') }}"
                            class="bg-sidebar-green text-white px-6 py-2 rounded-lg hover:bg-brand-dark-red transition inline-block">
                            Start NIDA Verification
                        </a>
                        <button 
                            type="button"
                            wire:click="refreshStatus" 
                            wire:loading.attr="disabled"
                            class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition disabled:opacity-50">
                            <span wire:loading.remove wire:target="refreshStatus">Check Status</span>
                            <span wire:loading wire:target="refreshStatus">Checking...</span>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Status is automatically checked every 5 seconds. You can also click "Check Status" after completing NIDA verification.</p>
                </div>
            @endif
        </div>
        @endif

        <!-- Step 2: Document Upload -->
        @if($step === 2)
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex items-center mb-6">
                <div class="flex-shrink-0 w-10 h-10 bg-sidebar-green text-white rounded-full flex items-center justify-center font-bold">
                    {{ $isTanzania ? '2' : '1' }}
                </div>
                <div class="ml-4">
                    <h2 class="text-lg font-semibold text-gray-900">Upload Required Documents</h2>
                    <p class="text-sm text-gray-600">Upload all required documents for verification</p>
                </div>
            </div>

            <div class="space-y-6">
                @if($isTanzania)
                    <!-- Tanzania: BRELA and TIN Certificate -->
                    <!-- BRELA Document -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            BRELA Document *
                        </label>
                        <p class="text-xs text-gray-500 mb-3">Upload your BRELA registration document</p>
                        @if(isset($uploadedDocuments['brela']))
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg mb-2">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span class="text-sm text-gray-700">{{ $uploadedDocuments['brela']['name'] }}</span>
                                </div>
                                <button wire:click="removeDocument('brela')" class="text-sidebar-green hover:text-sidebar-green-800 text-sm">
                                    Remove
                                </button>
                            </div>
                        @else
                            <input type="file" wire:model="brelaDocument" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-sidebar-green file:text-white hover:file:bg-brand-dark-red">
                            @error('brelaDocument') <span class="text-sidebar-green text-xs">{{ $message }}</span> @enderror
                            @if($brelaDocument)
                                <button wire:click="uploadDocument('brela')" wire:loading.attr="disabled" wire:target="uploadDocument,brelaDocument" class="mt-2 bg-sidebar-green text-white px-4 py-2 rounded-lg hover:bg-brand-dark-red transition text-sm disabled:opacity-50">
                                    <span wire:loading.remove wire:target="uploadDocument,brelaDocument">Upload BRELA Document</span>
                                    <span wire:loading wire:target="uploadDocument,brelaDocument">Uploading...</span>
                                </button>
                            @endif
                        @endif
                    </div>

                    <!-- TIN Certificate -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            TIN Certificate *
                        </label>
                        <p class="text-xs text-gray-500 mb-3">Upload your TIN certificate document</p>
                        @if(isset($uploadedDocuments['tin_certificate']))
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg mb-2">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span class="text-sm text-gray-700">{{ $uploadedDocuments['tin_certificate']['name'] }}</span>
                                </div>
                                <button wire:click="removeDocument('tin_certificate')" class="text-sidebar-green hover:text-sidebar-green-800 text-sm">
                                    Remove
                                </button>
                            </div>
                        @else
                            <input type="file" wire:model="tinCertificate" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-sidebar-green file:text-white hover:file:bg-brand-dark-red">
                            @error('tinCertificate') <span class="text-sidebar-green text-xs">{{ $message }}</span> @enderror
                            @if($tinCertificate)
                                <button wire:click="uploadDocument('tin_certificate')" wire:loading.attr="disabled" wire:target="uploadDocument,tinCertificate" class="mt-2 bg-sidebar-green text-white px-4 py-2 rounded-lg hover:bg-brand-dark-red transition text-sm disabled:opacity-50">
                                    <span wire:loading.remove wire:target="uploadDocument,tinCertificate">Upload TIN Certificate</span>
                                    <span wire:loading wire:target="uploadDocument,tinCertificate">Uploading...</span>
                                </button>
                            @endif
                        @endif
                    </div>
                @else
                    <!-- Non-Tanzania: Passport, Company Documents, Personal KYC -->
                    <!-- Passport -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Passport *
                        </label>
                        <p class="text-xs text-gray-500 mb-3">Upload a copy of your passport</p>
                        @if(isset($uploadedDocuments['passport']))
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg mb-2">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                    </svg>
                                    <span class="text-sm text-gray-700">{{ $uploadedDocuments['passport']['name'] }}</span>
                                </div>
                                <button wire:click="removeDocument('passport')" class="text-sidebar-green hover:text-sidebar-green-800 text-sm">
                                    Remove
                                </button>
                            </div>
                        @else
                            <input type="file" wire:model="passportDocument" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-sidebar-green file:text-white hover:file:bg-brand-dark-red">
                            @error('passportDocument') <span class="text-sidebar-green text-xs">{{ $message }}</span> @enderror
                            @if($passportDocument)
                                <button wire:click="uploadDocument('passport')" wire:loading.attr="disabled" wire:target="uploadDocument,passportDocument" class="mt-2 bg-sidebar-green text-white px-4 py-2 rounded-lg hover:bg-brand-dark-red transition text-sm disabled:opacity-50">
                                    <span wire:loading.remove wire:target="uploadDocument,passportDocument">Upload Passport</span>
                                    <span wire:loading wire:target="uploadDocument,passportDocument">Uploading...</span>
                                </button>
                            @endif
                        @endif
                    </div>

                    <!-- Company Documents -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Company Documents *
                        </label>
                        <p class="text-xs text-gray-500 mb-3">Upload company registration documents</p>
                        @if(isset($uploadedDocuments['company_documents']))
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg mb-2">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <span class="text-sm text-gray-700">{{ $uploadedDocuments['company_documents']['name'] }}</span>
                                </div>
                                <button wire:click="removeDocument('company_documents')" class="text-sidebar-green hover:text-sidebar-green-800 text-sm">
                                    Remove
                                </button>
                            </div>
                        @else
                            <input type="file" wire:model="companyDocuments" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-sidebar-green file:text-white hover:file:bg-brand-dark-red">
                            @error('companyDocuments') <span class="text-sidebar-green text-xs">{{ $message }}</span> @enderror
                            @if($companyDocuments)
                                <button wire:click="uploadDocument('company_documents')" wire:loading.attr="disabled" wire:target="uploadDocument,companyDocuments" class="mt-2 bg-sidebar-green text-white px-4 py-2 rounded-lg hover:bg-brand-dark-red transition text-sm disabled:opacity-50">
                                    <span wire:loading.remove wire:target="uploadDocument,companyDocuments">Upload Company Documents</span>
                                    <span wire:loading wire:target="uploadDocument,companyDocuments">Uploading...</span>
                                </button>
                            @endif
                        @endif
                    </div>

                    <!-- Personal KYC -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Personal KYC Documents *
                        </label>
                        <p class="text-xs text-gray-500 mb-3">Upload personal KYC documents (ID, proof of address, etc.)</p>
                        @if(isset($uploadedDocuments['personal_kyc']))
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg mb-2">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <span class="text-sm text-gray-700">{{ $uploadedDocuments['personal_kyc']['name'] }}</span>
                                </div>
                                <button wire:click="removeDocument('personal_kyc')" class="text-sidebar-green hover:text-sidebar-green-800 text-sm">
                                    Remove
                                </button>
                            </div>
                        @else
                            <input type="file" wire:model="personalKyc" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-sidebar-green file:text-white hover:file:bg-brand-dark-red">
                            @error('personalKyc') <span class="text-sidebar-green text-xs">{{ $message }}</span> @enderror
                            @if($personalKyc)
                                <button wire:click="uploadDocument('personal_kyc')" wire:loading.attr="disabled" wire:target="uploadDocument,personalKyc" class="mt-2 bg-sidebar-green text-white px-4 py-2 rounded-lg hover:bg-brand-dark-red transition text-sm disabled:opacity-50">
                                    <span wire:loading.remove wire:target="uploadDocument,personalKyc">Upload Personal KYC</span>
                                    <span wire:loading wire:target="uploadDocument,personalKyc">Uploading...</span>
                                </button>
                            @endif
                        @endif
                    </div>
                @endif

                <!-- Submit Button -->
                <div class="pt-4">
                    <button wire:click="completeKyc" class="w-full bg-sidebar-green text-white px-6 py-3 rounded-lg hover:bg-brand-dark-red transition font-semibold">
                        Submit for Verification
                    </button>
                </div>
            </div>
        </div>
        @endif

        <!-- Step 3: Pending Verification -->
        @if($step === 3)
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6 text-center">
            <div class="mb-4">
                <svg class="w-16 h-16 text-yellow-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-900 mb-2">Verification Pending</h2>
            <p class="text-gray-600 mb-4">Your company verification documents have been submitted successfully. Our admin team will review your documents and verify your account shortly.</p>
            <p class="text-sm text-gray-500">You will be notified once your account is verified. You can log in after verification is complete.</p>
            <div class="mt-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sidebar-green hover:text-brand-dark-red font-medium">
                        Logout
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
