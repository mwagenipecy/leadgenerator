<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? (trim($__env->yieldContent('title')) ?: config('app.name')) }}</title>
    <link rel="icon" type="image/png" href="{{ asset('landing/applicationIcon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js for language switcher -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .language-dropdown { z-index: 9999 !important; }
    </style>
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
    @livewireStyles
    @stack('styles')
</head>
<body class="font-inter">
@php
    $resolveAuthImageUrl = function (?string $value): string {
        if (empty($value)) {
            return asset('landing/register-login.jpg');
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        if (str_starts_with($value, '/storage/')) {
            return asset(ltrim($value, '/'));
        }

        if (str_starts_with($value, 'storage/')) {
            return asset($value);
        }

        return asset('storage/' . ltrim($value, '/'));
    };

    $authImageKey = $imageKey ?? (trim($__env->yieldContent('image-key')) ?: 'login');

    $authSideImageValue = \App\Models\SystemSetting::getValue('auth_side_image_' . $authImageKey . '_' . app()->getLocale())
        ?: \App\Models\SystemSetting::getValue('auth_side_image_' . $authImageKey . '_en')
        ?: \App\Models\SystemSetting::getValue('auth_side_image_' . app()->getLocale())
        ?: \App\Models\SystemSetting::getValue('auth_side_image_en')
        ?: null;

    $authSideImage = $resolveAuthImageUrl($authSideImageValue);
@endphp
<div class="h-screen flex overflow-hidden">
    <!-- Left Side - Image Panel (hidden on mobile) -->
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden h-screen" style="background-image: url('{{ $authSideImage }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="absolute bottom-0 right-0 z-20 p-6 pr-8 max-w-sm">
            @yield('lhs-content')
        </div>
    </div>

    <!-- Right Side - Page Content -->
    <div class="w-full lg:w-1/2 h-screen overflow-y-auto relative">
        <!-- Language Switcher (Top Right) -->
        <div class="absolute top-4 right-4 z-50">
            <x-language-switcher />
        </div>
        <div class="flex items-center justify-center min-h-full p-6 sm:p-8 lg:p-12">
            <div class="w-full max-w-lg py-8">
                <!-- Logo -->
                <div class="flex justify-center mb-6">
                    <img src="{{ asset('landing/redlogo.png') }}" alt="Fanikisha Market place Logo" class="h-16 w-auto">
                </div>

                @yield('content')
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const passwordInput = document.getElementById(fieldId);
    const eyeIcon = document.getElementById('eye-icon-' + fieldId);

    if (!passwordInput || !eyeIcon) return;

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
        `;
    } else {
        passwordInput.type = 'password';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
        `;
    }
}

// Auto-format any #phone input to +255XXXXXXXXX as the visitor types.
// Delegated on document (rather than bound to the element) so it keeps
// working after Livewire swaps the input back in on step changes.
document.addEventListener('input', function(e) {
    if (!e.target || e.target.id !== 'phone') return;

    let value = e.target.value.replace(/\D/g, '');
    if (value.startsWith('255')) {
        value = '+' + value;
    } else if (value.startsWith('0')) {
        value = '+255' + value.substring(1);
    } else if (value.length > 0 && !value.startsWith('+')) {
        value = '+255' + value;
    }
    e.target.value = value;
});
</script>
@stack('scripts')
@livewireScripts
</body>
</html>
