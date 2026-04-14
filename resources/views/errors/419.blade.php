<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Expired - {{ config('app.name') }}</title>
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
            <div class="mb-8 flex justify-center">
                <img src="{{ asset('landing/redlogo.png') }}" alt="{{ config('app.name') }} Logo" class="h-16 w-auto">
            </div>

            <div class="mb-6">
                <h1 class="text-9xl font-bold text-brand-red font-poppins">419</h1>
            </div>

            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-4 font-poppins">Session Expired</h2>
                <p class="text-lg text-gray-600">Your session has expired. Please refresh the page and try again.</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <button
                    onclick="window.location.reload()"
                    class="w-full sm:w-auto bg-brand-red text-white px-8 py-3 rounded-lg font-semibold hover:bg-brand-dark-red focus:ring-4 focus:ring-brand-red/30 transition-all duration-300 transform hover:scale-[1.02]"
                >
                    Refresh Page
                </button>
                <a
                    href="{{ auth()->check() ? route('dashboard') : url('/') }}"
                    class="w-full sm:w-auto bg-white border-2 border-gray-300 text-gray-700 px-8 py-3 rounded-lg font-semibold hover:bg-gray-50 focus:ring-4 focus:ring-gray-300/30 transition-all duration-300 transform hover:scale-[1.02]"
                >
                    Go Home
                </a>
            </div>

            <div class="mt-8 pt-8 border-t border-gray-200">
                <p class="text-sm text-gray-500">Error code: 419 Page Expired</p>
            </div>
        </div>
    </div>
</body>
</html>
