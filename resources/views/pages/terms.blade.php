<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('terms.terms_and_conditions') }} - Lead Generator</title>
    <link rel="icon" type="image/png" href="{{ asset('landing/applicationIcon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Language Switcher -->
            <div class="mb-4 flex justify-end">
                <x-language-switcher />
            </div>
            
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('terms.terms_and_conditions') }}</h1>
                <p class="text-gray-600">{{ __('terms.last_updated') }}: {{ now()->format('F d, Y') }}</p>
            </div>

            <!-- Content -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 prose prose-lg max-w-none">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('terms.acceptance_of_terms') }}</h2>
                <p class="text-gray-700 mb-6">
                    {{ __('terms.acceptance_content') }}
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('terms.use_license') }}</h2>
                <p class="text-gray-700 mb-4">
                    {{ __('terms.use_license_content') }}
                </p>
                <ul class="list-disc pl-6 mb-6 text-gray-700">
                    <li>{{ __('terms.modify_copy_materials') }}</li>
                    <li>{{ __('terms.use_commercial_purpose') }}</li>
                    <li>{{ __('terms.reverse_engineer') }}</li>
                    <li>{{ __('terms.remove_copyright') }}</li>
                </ul>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('terms.user_account') }}</h2>
                <p class="text-gray-700 mb-4">
                    {{ __('terms.user_account_content_1') }}
                </p>
                <p class="text-gray-700 mb-6">
                    {{ __('terms.user_account_content_2') }}
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('terms.loan_applications') }}</h2>
                <p class="text-gray-700 mb-4">
                    {{ __('terms.loan_applications_content') }}
                </p>
                <ul class="list-disc pl-6 mb-6 text-gray-700">
                    <li>{{ __('terms.information_true_accurate') }}</li>
                    <li>{{ __('terms.loan_approval_subject') }}</li>
                    <li>{{ __('terms.interest_rates_determined') }}</li>
                    <li>{{ __('terms.platform_connecting') }}</li>
                </ul>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('terms.privacy_policy') }}</h2>
                <p class="text-gray-700 mb-6">
                    {{ __('terms.privacy_policy_content') }}
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('terms.prohibited_uses') }}</h2>
                <p class="text-gray-700 mb-4">
                    {{ __('terms.prohibited_uses_content') }}
                </p>
                <ul class="list-disc pl-6 mb-6 text-gray-700">
                    <li>{{ __('terms.violate_laws') }}</li>
                    <li>{{ __('terms.transmit_advertising') }}</li>
                    <li>{{ __('terms.impersonate') }}</li>
                    <li>{{ __('terms.infringe_rights') }}</li>
                </ul>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('terms.disclaimer') }}</h2>
                <p class="text-gray-700 mb-6">
                    {{ __('terms.disclaimer_content') }}
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('terms.limitations') }}</h2>
                <p class="text-gray-700 mb-6">
                    {{ __('terms.limitations_content') }}
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('terms.revisions') }}</h2>
                <p class="text-gray-700 mb-6">
                    {{ __('terms.revisions_content') }}
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('terms.contact_information') }}</h2>
                <p class="text-gray-700 mb-6">
                    {{ __('terms.contact_information_content') }}
                </p>

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-500">
                        {{ __('terms.acknowledgment') }}
                    </p>
                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-6">
                <a href="{{ url()->previous() ?: route('user.register') }}" 
                   class="inline-flex items-center text-red-600 hover:text-red-700 font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    {{ __('terms.back') }}
                </a>
            </div>
        </div>
    </div>
</body>
</html>

