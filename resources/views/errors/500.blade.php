<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('errors.server_error') }} - {{ config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('landing/applicationIcon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-red': '#C40F11',
                        'brand-dark-red': '#A00E11',
                        'brand-green': '#C40F11',
                        'brand-green-light': '#999999',
                        'brand-gray': '#999999',
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
<body class="font-inter bg-gray-50">
    <div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl w-full text-center">
            <!-- Logo -->
            <div class="mb-8 flex justify-center">
                <img src="{{ asset('landing/redlogo.png') }}" alt="{{ config('app.name') }} Logo" class="h-16 w-auto">
            </div>
            
            <!-- Error Icon -->
            <div class="mb-6 flex justify-center">
                <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
            
            <!-- Error Code -->
            <div class="mb-6">
                <h1 class="text-9xl font-bold text-brand-red font-poppins">{{ __('errors.500_title') }}</h1>
            </div>
            
            <!-- Error Message -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-4 font-poppins">{{ __('errors.something_went_wrong') }}</h2>
                <p class="text-lg text-gray-600 mb-2">{{ __('errors.contact_admin_message') }}</p>
                <p class="text-sm text-gray-500">{{ __('errors.500_description') }}</p>
            </div>
            
            <!-- Contact Admin Card -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-8">
                <div class="flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-brand-red mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900">{{ __('errors.contact_admin_support') }}</h3>
                </div>
                <div class="space-y-3">
                    <a 
                        href="mailto:admin@{{ parse_url(config('app.url'), PHP_URL_HOST) }}?subject=Server Error - Support Request" 
                        class="inline-flex items-center justify-center w-full bg-brand-red text-white px-6 py-3 rounded-lg font-semibold hover:bg-brand-dark-red focus:ring-4 focus:ring-brand-red/30 transition-all duration-300 transform hover:scale-[1.02]"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ __('errors.contact_support') }}
                    </a>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <button 
                    onclick="window.location.reload()" 
                    class="w-full sm:w-auto bg-gray-200 text-gray-700 px-8 py-3 rounded-lg font-semibold hover:bg-gray-300 focus:ring-4 focus:ring-gray-300/30 transition-all duration-300 transform hover:scale-[1.02]"
                >
                    {{ __('errors.try_again') }}
                </button>
                <a 
                    href="{{ auth()->check() ? route('dashboard') : url('/') }}" 
                    class="w-full sm:w-auto bg-white border-2 border-gray-300 text-gray-700 px-8 py-3 rounded-lg font-semibold hover:bg-gray-50 focus:ring-4 focus:ring-gray-300/30 transition-all duration-300 transform hover:scale-[1.02]"
                >
                    {{ __('errors.go_home') }}
                </a>
            </div>
            
            <!-- Error Code Info -->
            <div class="mt-8 pt-8 border-t border-gray-200">
                <p class="text-sm text-gray-500">{{ __('errors.error_code_500') }}</p>
            </div>
        </div>
    </div>
</body>
</html>
