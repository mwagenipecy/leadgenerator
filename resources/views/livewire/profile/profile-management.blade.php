{{-- The Master doesn't talk, he acts. --}}
<div class="min-h-screen bg-gray-50">
<div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
    <!-- Header -->
    <div class="mb-6 sm:mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-1 sm:mb-2">My Profile</h1>
                <p class="text-gray-600 text-sm sm:text-base lg:text-lg">Complete your profile for faster loan applications</p>
            </div>
            <div class="flex justify-start sm:justify-end">
                <!-- Profile Completion Circle -->
                <div class="flex items-center space-x-3 sm:space-x-4">
                    <div class="relative w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                            <circle cx="50" cy="50" r="40" stroke="#e5e7eb" stroke-width="8" fill="none"/>
                            <circle cx="50" cy="50" r="40" 
                                    stroke="{{ $completionPercentage >= 70 ? '#10b981' : ($completionPercentage >= 40 ? '#f59e0b' : '#1D753F') }}" 
                                    stroke-width="8" fill="none" stroke-linecap="round"
                                    stroke-dasharray="{{ 2 * pi() * 40 }}" 
                                    stroke-dashoffset="{{ 2 * pi() * 40 * (1 - $completionPercentage / 100) }}"/>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-sm sm:text-base lg:text-lg font-bold {{ $completionPercentage >= 70 ? 'text-green-600' : ($completionPercentage >= 40 ? 'text-yellow-600' : 'text-sidebar-green') }}">
                                {{ $completionPercentage }}%
                            </span>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-500">Profile Completion</p>
                        <p class="text-sm sm:text-base lg:text-lg font-bold {{ $completionPercentage >= 70 ? 'text-green-600' : ($completionPercentage >= 40 ? 'text-yellow-600' : 'text-sidebar-green') }}">
                            {{ $completionPercentage >= 70 ? 'Complete' : ($completionPercentage >= 40 ? 'Good Progress' : 'Needs Work') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="mb-4 sm:mb-6 bg-green-50 border border-green-200 text-green-700 px-3 sm:px-4 py-2 sm:py-3 rounded-lg text-sm sm:text-base" role="alert">
                <div class="flex items-center">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="flex-1">{{ session('message') }}</span>
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-4 sm:mb-6 bg-sidebar-green-50 border border-sidebar-green-200 text-sidebar-green-light px-3 sm:px-4 py-2 sm:py-3 rounded-lg text-sm sm:text-base" role="alert">
                <div class="flex items-center">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span class="flex-1">{{ session('error') }}</span>
                </div>
            </div>
        @endif
    </div>

    <!-- Mobile Section Selector -->
    <div class="lg:hidden mb-4 sm:mb-6">
        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Select Section</label>
        <select wire:model.live="currentStep" 
                class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green text-sm sm:text-base">
            <option value="overview">Overview</option>
            @if(auth()->user()->registration_type === 'company')
                <option value="company">Company Details</option>
            @endif
            <option value="personal">Personal Info</option>
            <option value="address">Address</option>
            <option value="employment">Employment</option>
            <option value="financial">Financial</option>
            <option value="bank">Banking</option>
            <option value="emergency">Emergency Contact</option>
        </select>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 sm:gap-5 lg:gap-6 lg:gap-8">
        <!-- Left Sidebar - Navigation (Desktop Only) -->
        <div class="hidden lg:block lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                <div class="p-4 lg:p-4 sm:p-5 lg:p-6 border-b border-gray-100 bg-gradient-to-r from-sidebar-green to-sidebar-green-light text-white">
                    <h3 class="text-base lg:text-lg font-bold">Profile Sections</h3>
                    <p class="text-white text-xs lg:text-sm opacity-90">Complete all sections</p>
                </div>
                
                <nav class="p-2">
                    @php
                        $sections = [
                            'overview' => ['name' => 'Overview', 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
                            'personal' => ['name' => 'Personal Info', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                            'address' => ['name' => 'Address', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                            'employment' => ['name' => 'Employment', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                            'financial' => ['name' => 'Financial', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1'],
                            'bank' => ['name' => 'Banking', 'icon' => 'M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z'],
                            'emergency' => ['name' => 'Emergency Contact', 'icon' => 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z']
                        ];
                        
                        // Add company section for company users
                        if (auth()->user()->registration_type === 'company') {
                            $sections = array_slice($sections, 0, 1, true) + 
                                ['company' => ['name' => 'Company Details', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4']] + 
                                array_slice($sections, 1, null, true);
                        }
                    @endphp

                    @foreach($sections as $key => $section)
                        <button wire:click="goToStep('{{ $key }}')" 
                                class="w-full flex items-center px-3 lg:px-4 py-2 lg:py-3 rounded-lg text-left transition-all duration-200 mb-1 text-sm lg:text-base {{ $currentStep === $key ? 'bg-sidebar-green text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-4 h-4 lg:w-5 lg:h-5 mr-2 lg:mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $section['icon'] }}"/>
                            </svg>
                            <span class="font-medium">{{ $section['name'] }}</span>
                            @if($currentStep === $key)
                                <svg class="w-3 h-3 lg:w-4 lg:h-4 ml-auto flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            @endif
                        </button>
                    @endforeach
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3">
            {{-- OVERVIEW --}}
            @if($currentStep === 'overview')
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-4 sm:p-5 lg:p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">Profile Overview</h2>
                        <p class="text-sm sm:text-base text-gray-600">Your complete profile status and quick actions</p>
                    </div>
                    
                    <div class="p-6">
                        <!-- Profile Completion Status -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 lg:gap-6 mb-8">
                            @foreach($sections as $key => $section)
                                @if($key !== 'overview')
                                    @php
                                        $isComplete = false; // You can add completion logic here
                                        $completionIcon = $isComplete ? 'M5 13l4 4L19 7' : 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
                                        $completionColor = $isComplete ? 'green' : 'gray';
                                    @endphp
                                    <div wire:click="goToStep('{{ $key }}')" 
                                         class="border border-gray-200 rounded-xl p-4 cursor-pointer hover:border-sidebar-green hover:bg-gray-50 transition-all duration-200">
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="w-10 h-10 bg-{{ $completionColor }}-100 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-{{ $completionColor }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $completionIcon }}"/>
                                                </svg>
                                            </div>
                                            <span class="text-xs font-medium px-2 py-1 rounded-full {{ $isComplete ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                                <!-- {{ $isComplete ? 'Complete' : 'Pending' }} -->
                                            </span>
                                        </div>
                                        <h3 class="font-bold text-gray-900 mb-1">{{ $section['name'] }}</h3>
                                        <p class="text-sm text-gray-600">
                                            @switch($key)
                                                @case('personal') Basic personal information @break
                                                @case('address') Current and permanent address @break
                                                @case('employment') Employment and business details @break
                                                @case('financial') Income and expense information @break
                                                @case('bank') Banking and account details @break
                                                @case('emergency') Emergency contact information @break
                                            @endswitch
                                        </p>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <!-- Company Quick Card (if company user) -->
                        @if(auth()->user()->registration_type === 'company')
                        <div wire:click="goToStep('company')" 
                             class="bg-gradient-to-r from-teal-50 to-cyan-50 rounded-xl p-5 mb-6 cursor-pointer hover:shadow-md transition-all duration-200 border border-teal-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 bg-teal-100 rounded-xl flex items-center justify-center">
                                        <svg class="w-7 h-7 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900 text-lg">{{ auth()->user()->company_name ?? 'Company Details' }}</h3>
                                        <p class="text-sm text-gray-600">View and manage your company information</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    @if(auth()->user()->isCompanyVerified())
                                        <span class="px-3 py-1.5 bg-green-100 text-green-800 text-xs font-semibold rounded-full flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Verified
                                        </span>
                                    @elseif(auth()->user()->isCompanyVerificationPending())
                                        <span class="px-3 py-1.5 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">Pending</span>
                                    @elseif(auth()->user()->isCompanyVerificationRejected())
                                        <span class="px-3 py-1.5 bg-red-100 text-red-800 text-xs font-semibold rounded-full">Rejected</span>
                                    @else
                                        <span class="px-3 py-1.5 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full">Not Verified</span>
                                    @endif
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Quick Actions -->
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl sm:rounded-2xl p-4 sm:p-5 lg:p-6 border border-purple-200">
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                <a href="{{ route('loan-application.create') }}" 
                                   class="flex items-center justify-center px-6 py-3 bg-sidebar-green text-white rounded-lg font-semibold hover:bg-sidebar-green-light transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    Pre-Qualify for Loan
                                </a>
                                <a href="{{ route('user.loan.application') }}" 
                                   class="flex items-center justify-center px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    View Applications
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            {{-- COMPANY DETAILS (Only for Company Users) --}}
            @elseif($currentStep === 'company' && auth()->user()->registration_type === 'company')
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-4 sm:p-5 lg:p-6 border-b border-gray-100 bg-gradient-to-r from-teal-50 to-cyan-50">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">Company Details</h2>
                                <p class="text-sm sm:text-base text-gray-600">Your registered company information</p>
                            </div>
                            @if(auth()->user()->isCompanyVerified())
                                <span class="px-4 py-2 bg-green-100 text-green-800 text-sm font-semibold rounded-full flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Verified
                                </span>
                            @elseif(auth()->user()->isCompanyVerificationPending())
                                <span class="px-4 py-2 bg-yellow-100 text-yellow-800 text-sm font-semibold rounded-full flex items-center gap-2">
                                    <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Pending Review
                                </span>
                            @elseif(auth()->user()->isCompanyVerificationRejected())
                                <span class="px-4 py-2 bg-red-100 text-red-800 text-sm font-semibold rounded-full flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Rejected
                                </span>
                            @else
                                <span class="px-4 py-2 bg-gray-100 text-gray-800 text-sm font-semibold rounded-full">Not Verified</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="p-4 sm:p-5 lg:p-6 space-y-6">
                        <!-- Company Basic Info -->
                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                Company Information
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="bg-white rounded-lg p-4 border border-gray-100">
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Company Name</label>
                                    <p class="text-gray-900 font-semibold text-lg">{{ auth()->user()->company_name ?? 'Not Provided' }}</p>
                                </div>
                                <div class="bg-white rounded-lg p-4 border border-gray-100">
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Company TIN</label>
                                    <p class="text-gray-900 font-semibold text-lg font-mono">{{ auth()->user()->company_tin ?? 'Not Provided' }}</p>
                                </div>
                                <div class="bg-white rounded-lg p-4 border border-gray-100">
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Country of Registration</label>
                                    <p class="text-gray-900 font-semibold text-lg">{{ auth()->user()->country ?? 'Not Provided' }}</p>
                                </div>
                                <div class="bg-white rounded-lg p-4 border border-gray-100">
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Registration Type</label>
                                    <p class="text-gray-900 font-semibold text-lg capitalize">{{ auth()->user()->registration_type ?? 'Not Provided' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Company Representative Info -->
                        <div class="bg-blue-50 rounded-xl p-5 border border-blue-200">
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Company Representative
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="bg-white rounded-lg p-4 border border-blue-100">
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Representative Name</label>
                                    <p class="text-gray-900 font-semibold text-lg">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</p>
                                </div>
                                <div class="bg-white rounded-lg p-4 border border-blue-100">
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Email Address</label>
                                    <p class="text-gray-900 font-semibold text-lg">{{ auth()->user()->email }}</p>
                                </div>
                                @if(auth()->user()->isFromTanzania())
                                    <div class="bg-white rounded-lg p-4 border border-blue-100">
                                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Representative NIDA Number</label>
                                        <p class="text-gray-900 font-semibold text-lg font-mono">{{ auth()->user()->company_contact_nida ?? 'Not Provided' }}</p>
                                    </div>
                                @else
                                    <div class="bg-white rounded-lg p-4 border border-blue-100">
                                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Passport Number</label>
                                        <p class="text-gray-900 font-semibold text-lg font-mono">{{ auth()->user()->passport_number ?? 'Not Provided' }}</p>
                                    </div>
                                @endif
                                <div class="bg-white rounded-lg p-4 border border-blue-100">
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Phone Number</label>
                                    <p class="text-gray-900 font-semibold text-lg">{{ auth()->user()->phone ?? 'Not Provided' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Verification Status Details -->
                        <div class="rounded-xl p-5 border {{ auth()->user()->isCompanyVerified() ? 'bg-green-50 border-green-200' : (auth()->user()->isCompanyVerificationPending() ? 'bg-yellow-50 border-yellow-200' : (auth()->user()->isCompanyVerificationRejected() ? 'bg-red-50 border-red-200' : 'bg-gray-50 border-gray-200')) }}">
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 {{ auth()->user()->isCompanyVerified() ? 'text-green-600' : (auth()->user()->isCompanyVerificationPending() ? 'text-yellow-600' : 'text-gray-600') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                Verification Status
                            </h3>
                            
                            @if(auth()->user()->isCompanyVerified())
                                <div class="bg-white rounded-lg p-4 border border-green-100">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-green-800 font-bold text-lg">Company Verified</p>
                                            <p class="text-green-600 text-sm">Your company has been verified and approved</p>
                                        </div>
                                    </div>
                                    @if(auth()->user()->company_verified_at)
                                        <p class="text-sm text-gray-600 mt-2">
                                            <span class="font-medium">Verified on:</span> 
                                            {{ \Carbon\Carbon::parse(auth()->user()->company_verified_at)->format('F d, Y \a\t h:i A') }}
                                        </p>
                                    @endif
                                </div>
                            @elseif(auth()->user()->isCompanyVerificationPending())
                                <div class="bg-white rounded-lg p-4 border border-yellow-100">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-yellow-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-yellow-800 font-bold text-lg">Verification Pending</p>
                                            <p class="text-yellow-600 text-sm">Your documents are being reviewed by our team</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-2">This usually takes 1-3 business days. You will be notified once the review is complete.</p>
                                </div>
                            @elseif(auth()->user()->isCompanyVerificationRejected())
                                <div class="bg-white rounded-lg p-4 border border-red-100">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-red-800 font-bold text-lg">Verification Rejected</p>
                                            <p class="text-red-600 text-sm">Your company verification was not approved</p>
                                        </div>
                                    </div>
                                    @if(auth()->user()->company_verification_notes)
                                        <div class="mt-3 p-3 bg-red-50 rounded-lg border border-red-200">
                                            <p class="text-sm font-medium text-red-800">Reason:</p>
                                            <p class="text-sm text-red-700 mt-1">{{ auth()->user()->company_verification_notes }}</p>
                                        </div>
                                    @endif
                                    <a href="{{ route('company.kyc') }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                        Re-submit Documents
                                    </a>
                                </div>
                            @else
                                <div class="bg-white rounded-lg p-4 border border-gray-200">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-gray-800 font-bold text-lg">Not Yet Verified</p>
                                            <p class="text-gray-600 text-sm">Please complete company verification to access all features</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('company.kyc') }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-teal-600 text-white rounded-lg font-medium hover:bg-teal-700 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Start Verification
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row justify-between gap-3">
                        <button wire:click="goToStep('overview')" 
                                class="w-full sm:w-auto bg-gray-100 text-gray-700 px-4 sm:px-6 py-2 sm:py-3 rounded-lg text-sm sm:text-base font-semibold hover:bg-gray-200 transition-colors">
                            Back to Overview
                        </button>
                        <button wire:click="goToStep('personal')" 
                                class="w-full sm:w-auto bg-sidebar-green text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg text-sm sm:text-base font-semibold hover:bg-sidebar-green-light transition-colors">
                            Continue to Personal Info
                        </button>
                    </div>
                </div>

            {{-- PERSONAL INFORMATION --}}
            @elseif($currentStep === 'personal')
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-4 sm:p-5 lg:p-6 border-b border-gray-100 bg-gradient-to-r from-green-50 to-emerald-50">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">Personal Information</h2>
                        <p class="text-gray-600">Basic personal details and identification</p>
                    </div>
                    
                    <div class="p-4 sm:p-5 lg:p-6 space-y-4 sm:space-y-5 lg:space-y-6">
                        <!-- Name Section -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 lg:gap-6">
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">First Name *</label>
                                <input type="text" value="{{ $first_name }}" disabled
                                       class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed">
                                <p class="text-xs sm:text-sm text-gray-500 mt-1">This field is automatically filled from your account</p>
                            </div>
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Middle Name</label>
                                <input wire:model="middle_name" type="text" 
                                       class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                @error('middle_name') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Last Name *</label>
                                <input type="text" value="{{ $last_name }}" disabled
                                       class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed">
                                <p class="text-xs sm:text-sm text-gray-500 mt-1">This field is automatically filled from your account</p>
                            </div>
                        </div>

                        <!-- Personal Details -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 lg:gap-6">
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Date of Birth *</label>
                                <input wire:model.defer="date_of_birth" type="date" 
                                       max="{{ date('Y-m-d') }}"
                                       class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                @error('date_of_birth') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Gender *</label>
                                <select wire:model="gender" 
                                        class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                    <option value="">Select gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                                @error('gender') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Marital Status *</label>
                                <select wire:model="marital_status" 
                                        class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                    <option value="">Select status</option>
                                    @foreach($maritalStatuses as $key => $status)
                                        <option value="{{ $key }}">{{ $status }}</option>
                                    @endforeach
                                </select>
                                @error('marital_status') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 lg:gap-6">
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">National ID (NIDA) *</label>
                                <input type="text" value="{{ $national_id }}" disabled
                                       class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed">
                                <p class="text-xs sm:text-sm text-gray-500 mt-1">This field is automatically filled from your account</p>
                            </div>
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Phone Number *</label>
                                <input wire:model="phone_number" type="tel" 
                                       class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                @error('phone_number') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Email Address *</label>
                                <input wire:model="email" type="email" 
                                       class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                @error('email') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Read-only fields notice -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <h4 class="text-sm font-medium text-blue-800">Information Note</h4>
                                    <p class="text-sm text-blue-700 mt-1">Some fields (First Name, Last Name, and National ID) are automatically filled from your verified account information and cannot be edited here. If you need to update these details, please contact support.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row justify-between gap-3">
                        <button wire:click="goToStep('overview')" 
                                class="w-full sm:w-auto bg-gray-100 text-gray-700 px-4 sm:px-6 py-2 sm:py-3 rounded-lg text-sm sm:text-base font-semibold hover:bg-gray-200 transition-colors">
                            Back to Overview
                        </button>
                        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                            <button wire:click="saveCurrentStep" 
                                    class="w-full sm:w-auto bg-green-600 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg text-sm sm:text-base font-semibold hover:bg-green-700 transition-colors">
                                Save
                            </button>
                            <button wire:click="saveAndContinue('address')" 
                                    class="w-full sm:w-auto bg-sidebar-green text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg text-sm sm:text-base font-semibold hover:bg-sidebar-green-light transition-colors">
                                Save & Continue
                            </button>
                        </div>
                    </div>
                </div>

            {{-- ADDRESS INFORMATION --}}
            @elseif($currentStep === 'address')
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-4 sm:p-5 lg:p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">Address Information</h2>
                        <p class="text-sm sm:text-base text-gray-600">Your current and permanent address details</p>
                    </div>
                    
                    <div class="p-6 space-y-8">
                        <!-- Current Address -->
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-4">Current Address</h3>
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Street Address *</label>
                                    <textarea wire:model="current_address" rows="3" 
                                              class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green" 
                                              placeholder="Enter your current street address"></textarea>
                                    @error('current_address') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 lg:gap-6">
                                    <div>
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">City *</label>
                                        <input wire:model="current_city" type="text" 
                                               class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                        @error('current_city') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Region *</label>
                                        <input wire:model="current_region" type="text" 
                                               class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                          @error('current_region') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Postal Code</label>
                                        <input wire:model="current_postal_code" type="text" 
                                               class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                        @error('current_postal_code') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Years at Current Address *</label>
                                    <input wire:model="years_at_current_address" type="number" min="0" max="50" 
                                           class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                    @error('years_at_current_address') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Permanent Address -->
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-base sm:text-lg font-bold text-gray-900">Permanent Address</h3>
                                <label class="flex items-center cursor-pointer">
                                    <input wire:model.live="is_permanent_same_as_current" type="checkbox" 
                                           class="text-sidebar-green focus:ring-sidebar-green rounded">
                                    <span class="ml-2 text-sm font-medium text-gray-700">Same as current address</span>
                                </label>
                            </div>
                            
                            @if(!$is_permanent_same_as_current)
                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Permanent Address</label>
                                        <textarea wire:model="permanent_address" rows="3" 
                                                  class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green" 
                                                  placeholder="Enter your permanent address"></textarea>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 sm:gap-5 lg:gap-6">
                                        <div>
                                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">City</label>
                                            <input wire:model="permanent_city" type="text" 
                                                   class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                        </div>
                                        <div>
                                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Region</label>
                                            <input wire:model="permanent_region" type="text" 
                                                   class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                                    <p class="text-sm text-green-700">
                                        ✓ Permanent address will be the same as your current address
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row justify-between gap-3">
                        <button wire:click="goToStep('personal')" 
                                class="w-full sm:w-auto bg-gray-100 text-gray-700 px-4 sm:px-6 py-2 sm:py-3 rounded-lg text-sm sm:text-base font-semibold hover:bg-gray-200 transition-colors">
                            Previous
                        </button>
                        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                            <button wire:click="saveCurrentStep" 
                                    class="w-full sm:w-auto bg-green-600 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg text-sm sm:text-base font-semibold hover:bg-green-700 transition-colors">
                                Save
                            </button>
                            <button wire:click="saveAndContinue('employment')" 
                                    class="w-full sm:w-auto bg-sidebar-green text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg text-sm sm:text-base font-semibold hover:bg-sidebar-green-light transition-colors">
                                Save & Continue
                            </button>
                        </div>
                    </div>
                </div>

            {{-- EMPLOYMENT INFORMATION --}}
            @elseif($currentStep === 'employment')
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-4 sm:p-5 lg:p-6 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-pink-50">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">Employment Information</h2>
                        <p class="text-sm sm:text-base text-gray-600">Your work and business details</p>
                    </div>
                    
                    <div class="p-4 sm:p-5 lg:p-6 space-y-4 sm:space-y-5 lg:space-y-6">
                        <!-- Employment Status -->
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Employment Status *</label>
                            <select wire:model.live="employment_status" 
                                    class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                <option value="">Select employment status</option>
                                <option value="employed">Employed</option>
                                <option value="self_employed">Sole trader </option>
                                <!-- <option value="unemployed">Unemployed</option>
                                <option value="retired">Retired</option>
                                <option value="student">Student</option> -->
                            </select>
                            @error('employment_status') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Employment Details (if employed) -->
                        @if($employment_status === 'employed')
                            <div class="bg-blue-50 rounded-xl sm:rounded-2xl p-4 sm:p-5 lg:p-6 border border-blue-100">
                                <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-4">Employment Details</h3>
                                <div class="space-y-6">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 sm:gap-5 lg:gap-6">
                                        <div>
                                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Employer Name *</label>
                                            <input wire:model="employer_name" type="text" 
                                                   class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                            @error('employer_name') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Job Title *</label>
                                            <input wire:model="job_title" type="text" 
                                                   class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                            @error('job_title') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 sm:gap-5 lg:gap-6">
                                        <div>
                                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Employment Sector</label>
                                            <select wire:model="employment_sector" 
                                                    class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                                <option value="">Select sector</option>
                                                @foreach($employmentSectors as $key => $sector)
                                                    <option value="{{ $key }}">{{ $sector }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Months with Current Employer</label>
                                            <input wire:model="months_with_current_employer" type="number" min="0" 
                                                   class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Business Details (if self-employed) -->
                        @if($employment_status === 'self_employed')
                            <div class="bg-green-50 rounded-xl sm:rounded-2xl p-4 sm:p-5 lg:p-6 border border-green-100">
                                <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-4">Business Details</h3>
                                <div class="space-y-6">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 sm:gap-5 lg:gap-6">
                                        <div>
                                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Business Name *</label>
                                            <input wire:model="business_name" type="text" 
                                                   class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                            @error('business_name') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Business Type *</label>
                                            <select wire:model="business_type" 
                                                    class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                                <option value="">Select business type</option>
                                                @foreach($businessTypes as $key => $type)
                                                    <option value="{{ $key }}">{{ $type }}</option>
                                                @endforeach
                                            </select>
                                            @error('business_type') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 sm:gap-5 lg:gap-6">
                                        <div>
                                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Registration Number</label>
                                            <input wire:model="business_registration_number" type="text" 
                                                   class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                        </div>
                                        <div>
                                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Years in Business</label>
                                            <input wire:model="years_in_business" type="number" min="0" max="50" 
                                                   class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Business Address</label>
                                        <textarea wire:model="business_address" rows="2" 
                                                  class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green"></textarea>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row justify-between gap-3">
                        <button wire:click="goToStep('address')" 
                                class="w-full sm:w-auto bg-gray-100 text-gray-700 px-4 sm:px-6 py-2 sm:py-3 rounded-lg text-sm sm:text-base font-semibold hover:bg-gray-200 transition-colors">
                            Previous
                        </button>
                        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                            <button wire:click="saveCurrentStep" 
                                    class="w-full sm:w-auto bg-green-600 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg text-sm sm:text-base font-semibold hover:bg-green-700 transition-colors">
                                Save
                            </button>
                            <button wire:click="saveAndContinue('financial')" 
                                    class="w-full sm:w-auto bg-sidebar-green text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg text-sm sm:text-base font-semibold hover:bg-sidebar-green-light transition-colors">
                                Save & Continue
                            </button>
                        </div>
                    </div>
                </div>

            {{-- FINANCIAL INFORMATION --}}
            @elseif($currentStep === 'financial')
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-4 sm:p-5 lg:p-6 border-b border-gray-100 bg-gradient-to-r from-yellow-50 to-orange-50">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">Financial Information</h2>
                        <p class="text-sm sm:text-base text-gray-600">Your income, expenses, and financial obligations</p>
                    </div>
                    
                    <div class="p-4 sm:p-5 lg:p-6 space-y-4 sm:space-y-5 lg:space-y-6">
                        <!-- Income Section -->
                        <div class="bg-green-50 rounded-xl sm:rounded-2xl p-4 sm:p-5 lg:p-6 border border-green-100">
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-4">Monthly Income</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 sm:gap-5 lg:gap-6">
                                @if($employment_status === 'employed')
                                    <div>
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Monthly Salary (TSh) *</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">TSh</span>
                                            <input wire:model.live="monthly_salary" type="number" step="1000" min="0" 
                                                   class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                        </div>
                                        @error('monthly_salary') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                @endif
                                
                                @if($employment_status === 'self_employed')
                                    <div>
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Monthly Business Income (TSh) *</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">TSh</span>
                                            <input wire:model.live="monthly_business_income" type="number" step="1000" min="0" 
                                                   class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                        </div>
                                        @error('monthly_business_income') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                @endif
                                
                                <div>
                                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Other Monthly Income (TSh)</label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">TSh</span>
                                        <input wire:model.live="other_monthly_income" type="number" step="1000" min="0" 
                                               class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">Rental income, investments, etc.</p>
                                </div>
                            </div>
                            
                            <!-- Total Income Display -->
                            @if($total_monthly_income > 0)
                                <div class="mt-4 p-4 bg-green-100 rounded-lg">
                                    <div class="flex items-center justify-between">
                                        <span class="text-green-700 font-medium">Total Monthly Income:</span>
                                        <span class="text-xl font-bold text-green-800">TSh {{ number_format($total_monthly_income) }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Expenses Section -->
                        <div class="bg-orange-50 rounded-xl sm:rounded-2xl p-4 sm:p-5 lg:p-6 border border-orange-100">
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-4">Monthly Expenses & Obligations</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 sm:gap-5 lg:gap-6">
                                <div>
                                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Monthly Expenses (TSh) *</label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">TSh</span>
                                        <input wire:model="monthly_expenses" type="number" step="1000" min="0" 
                                               class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                    </div>
                                    @error('monthly_expenses') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Existing Loan Payments (TSh)</label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">TSh</span>
                                        <input wire:model="existing_loan_payments" type="number" step="1000" min="0" 
                                               class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                    </div>
                                </div>
                            </div>
                        </div>

                        

                        <!-- Financial Summary -->
                        @if($total_monthly_income > 0 && $monthly_expenses > 0)
                            <div class="bg-purple-50 rounded-xl sm:rounded-2xl p-4 sm:p-5 lg:p-6 border border-purple-100">
                                <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-4">Financial Summary</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                    <div class="text-center">
                                        <p class="text-sm text-gray-600">Net Income</p>
                                        <p class="text-xl font-bold {{ ($total_monthly_income - $monthly_expenses) > 0 ? 'text-green-600' : 'text-sidebar-green' }}">
                                            TSh {{ number_format($total_monthly_income - $monthly_expenses) }}
                                        </p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-sm text-gray-600">Available for Loans</p>
                                        <p class="text-xl font-bold text-blue-600">
                                            TSh {{ number_format(max(0, $total_monthly_income - $monthly_expenses - $existing_loan_payments)) }}
                                        </p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-sm text-gray-600">Current DSR</p>
                                        @php
                                            $currentDSR = $total_monthly_income > 0 ? ($existing_loan_payments / $total_monthly_income) * 100 : 0;
                                        @endphp
                                        <p class="text-xl font-bold {{ $currentDSR <= 30 ? 'text-green-600' : ($currentDSR <= 40 ? 'text-yellow-600' : 'text-sidebar-green') }}">
                                            {{ number_format($currentDSR, 1) }}%
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row justify-between gap-3">
                        <button wire:click="goToStep('employment')" 
                                class="w-full sm:w-auto bg-gray-100 text-gray-700 px-4 sm:px-6 py-2 sm:py-3 rounded-lg text-sm sm:text-base font-semibold hover:bg-gray-200 transition-colors">
                            Previous
                        </button>
                        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                            <button wire:click="saveCurrentStep" 
                                    class="w-full sm:w-auto bg-green-600 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg text-sm sm:text-base font-semibold hover:bg-green-700 transition-colors">
                                Save
                            </button>
                            <button wire:click="saveAndContinue('bank')" 
                                    class="w-full sm:w-auto bg-sidebar-green text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg text-sm sm:text-base font-semibold hover:bg-sidebar-green-light transition-colors">
                                Save & Continue
                            </button>
                        </div>
                    </div>
                </div>

            {{-- BANK INFORMATION --}}
            @elseif($currentStep === 'bank')
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-4 sm:p-5 lg:p-6 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-blue-50">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">Banking Information</h2>
                        <p class="text-sm sm:text-base text-gray-600">Your bank account and financial institution details</p>
                    </div>
                    
                    <div class="p-4 sm:p-5 lg:p-6 space-y-4 sm:space-y-5 lg:space-y-6">
                        <!-- Bank Account Question -->
                        <div class="bg-blue-50 rounded-xl sm:rounded-2xl p-4 sm:p-5 lg:p-6 border border-blue-100">
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-4">Do you have a bank account?</h3>
                            
                            <div class="flex gap-4">
                                <label class="flex items-center">
                                    <input wire:model.live="has_bank_account" type="radio" value="1" 
                                           class="mr-2 text-sidebar-green focus:ring-sidebar-green">
                                    <span class="text-gray-700">Yes, I have a bank account</span>
                                </label>
                                <label class="flex items-center">
                                    <input wire:model.live="has_bank_account" type="radio" value="0" 
                                           class="mr-2 text-sidebar-green focus:ring-sidebar-green">
                                    <span class="text-gray-700">No, I don't have a bank account</span>
                                </label>
                            </div>
                        </div>

                        <!-- Bank Details (if has account) -->
                        @if($has_bank_account)
                            <div class="space-y-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 sm:gap-5 lg:gap-6">
                                    <div>
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Bank Name *</label>
                                        <input wire:model="bank_name" type="text" 
                                               class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                        @error('bank_name') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Account Type *</label>
                                        <select wire:model="account_type" 
                                                class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                            <option value="">Select account type</option>
                                            <option value="savings">Savings Account</option>
                                            <option value="current">Current Account</option>
                                        </select>
                                        @error('account_type') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 sm:gap-5 lg:gap-6">
                                    <div>
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Account Number *</label>
                                        <input wire:model="account_number" type="text" 
                                               class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                        @error('account_number') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Account Holder Name *</label>
                                        <input wire:model="account_name" type="text" 
                                               class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                        @error('account_name') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Years with Bank</label>
                                    <input wire:model="years_with_bank" type="number" min="0" max="50" 
                                           class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                </div>
                            </div>
                        @elseif($has_bank_account === false || $has_bank_account === 0)
                            <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-yellow-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <h4 class="text-sm font-medium text-yellow-800">No Bank Account</h4>
                                        <p class="text-sm text-yellow-700 mt-1">You've indicated that you don't have a bank account. You may need to open one to receive loan disbursements.</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Disbursement Preference -->
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Preferred Disbursement Method</label>
                            <select wire:model="preferred_disbursement_method" 
                                    class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="mobile_money">Mobile Money</option>
                                <option value="cash">Cash Pickup</option>
                                <option value="check">Check</option>
                            </select>
                        </div>
                    </div>

                    <div class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row justify-between gap-3">
                        <button wire:click="goToStep('financial')" 
                                class="w-full sm:w-auto bg-gray-100 text-gray-700 px-4 sm:px-6 py-2 sm:py-3 rounded-lg text-sm sm:text-base font-semibold hover:bg-gray-200 transition-colors">
                            Previous
                        </button>
                        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                            <button wire:click="saveCurrentStep" 
                                    class="w-full sm:w-auto bg-green-600 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg text-sm sm:text-base font-semibold hover:bg-green-700 transition-colors">
                                Save
                            </button>
                            <button wire:click="saveAndContinue('emergency')" 
                                    class="w-full sm:w-auto bg-sidebar-green text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg text-sm sm:text-base font-semibold hover:bg-sidebar-green transition-colors">
                                Save & Continue
                            </button>
                        </div>
                    </div>
                </div>

            {{-- EMERGENCY CONTACT --}}
            @elseif($currentStep === 'emergency')
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-4 sm:p-5 lg:p-6 border-b border-gray-100 bg-gradient-to-r from-green-50 to-emerald-50">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">Emergency Contact</h2>
                        <p class="text-sm sm:text-base text-gray-600">Provide details of someone we can contact in case of emergency</p>
                    </div>
                    
                    <div class="p-4 sm:p-5 lg:p-6 space-y-4 sm:space-y-5 lg:space-y-6">
                        <!-- Required Notice -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 sm:p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2 sm:mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <h4 class="text-sm font-medium text-blue-800">Required Section</h4>
                                    <p class="text-xs sm:text-sm text-blue-700 mt-1">Emergency contact information is mandatory. Please provide complete details of someone we can reach in case of emergency.</p>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 sm:gap-5 lg:gap-6">
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Contact Name *</label>
                                <input wire:model="emergency_contact_name" type="text" 
                                       class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                @error('emergency_contact_name') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Relationship *</label>
                                <select wire:model="emergency_contact_relationship" 
                                        class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                    <option value="">Select relationship</option>
                                    <option value="spouse">Spouse</option>
                                    <option value="parent">Parent</option>
                                    <option value="sibling">Sibling</option>
                                    <option value="child">Child</option>
                                    <option value="friend">Friend</option>
                                    <option value="colleague">Colleague</option>
                                    <option value="other">Other</option>
                                </select>
                                @error('emergency_contact_relationship') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 sm:gap-5 lg:gap-6">
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Phone Number *</label>
                                <input wire:model="emergency_contact_phone" type="tel" 
                                       class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                                @error('emergency_contact_phone') <span class="text-sidebar-green text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Address</label>
                                <input wire:model="emergency_contact_address" type="text" 
                                       class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-sidebar-green focus:border-sidebar-green">
                            </div>
                        </div>
                    </div>

                    <div class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row justify-between gap-3">
                        <button wire:click="goToStep('bank')" 
                                class="w-full sm:w-auto bg-gray-100 text-gray-700 px-4 sm:px-6 py-2 sm:py-3 rounded-lg text-sm sm:text-base font-semibold hover:bg-gray-200 transition-colors">
                            Previous
                        </button>
                        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                            <button wire:click="saveCurrentStep" 
                                    class="w-full sm:w-auto bg-green-600 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg text-sm sm:text-base font-semibold hover:bg-green-700 transition-colors">
                                Save
                            </button>
                            <button wire:click="saveStep('emergency')" 
                                    class="w-full sm:w-auto bg-sidebar-green text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg text-sm sm:text-base font-semibold hover:bg-sidebar-green-light transition-colors">
                                Save Profile
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Profile Completion Reminder -->
    @if($completionPercentage < 70)
        <div class="mt-8 bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-2xl p-6">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-yellow-900 mb-2">Complete Your Profile</h3>
                    <p class="text-yellow-800 mb-4">
                        Your profile is {{ $completionPercentage }}% complete. Complete at least 70% of your profile to apply for loans and get better pre-qualification results.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        @if(empty($first_name) || empty($last_name))
                            <button wire:click="goToStep('personal')" 
                                    class="bg-yellow-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-yellow-700 transition-colors text-sm">
                                Complete Personal Info
                            </button>
                        @endif
                        @if(empty($current_address) || empty($current_city))
                            <button wire:click="goToStep('address')" 
                                    class="bg-yellow-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-yellow-700 transition-colors text-sm">
                                Add Address
                            </button>
                        @endif
                        @if(empty($employment_status))
                            <button wire:click="goToStep('employment')" 
                                    class="bg-yellow-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-yellow-700 transition-colors text-sm">
                                Add Employment
                            </button>
                        @endif
                        @if($total_monthly_income <= 0)
                            <button wire:click="goToStep('financial')" 
                                    class="bg-yellow-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-yellow-700 transition-colors text-sm">
                                Add Income
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
</div>