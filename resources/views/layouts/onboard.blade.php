<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - Lead Generator</title>
    <link rel="icon" type="image/png" href="{{ asset('landing/applicationIcon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    
    <!-- Alpine.js for type switching -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    
    <style>
        :root {
            --brand-red: #C40F11;
            --brand-dark-red: #A00E11;
        }
    </style>

</head>
<body class="bg-gray-50 font-inter">




    

    @yield('main-section')



<!-- JavaScript -->
<script>
    function togglePassword(fieldId) {
        const passwordInput = document.getElementById(fieldId);
        const eyeIcon = document.getElementById(fieldId + '-eye');
        
        if (!passwordInput || !eyeIcon) return;
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
            `;
        } else {
            passwordInput.type = 'password';
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            `;
        }
    }

    // Phone number formatting - only if element exists
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
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
    }

    // NIDA number formatting - only if element exists
    const nidaInput = document.getElementById('nida_number');
    if (nidaInput) {
        nidaInput.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/[^A-Za-z0-9]/g, '').toUpperCase();
        });
    }

    // Real-time form validation - only if elements exist
    const passwordInput = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');
    const registrationForm = document.querySelector('form:not([wire\\:submit])');
    
    if (registrationForm && passwordInput && passwordConfirmation) {
        registrationForm.addEventListener('submit', function(e) {
            if (passwordInput.value !== passwordConfirmation.value) {
                e.preventDefault();
                passwordConfirmation.focus();
                passwordConfirmation.classList.add('border-sidebar-green', 'ring-1', 'ring-sidebar-green');
                return false;
            }
        });

        // Password match validation
        passwordConfirmation.addEventListener('input', function() {
            const confirmation = this.value;
            
            if (confirmation && passwordInput.value !== confirmation) {
                this.classList.add('border-sidebar-green', 'ring-1', 'ring-sidebar-green');
            } else {
                this.classList.remove('border-sidebar-green', 'ring-1', 'ring-sidebar-green');
            }
        });
    }

    // Input validation feedback - only for non-Livewire inputs
    document.querySelectorAll('input[required]:not([wire\\:model])').forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value.trim()) {
                this.classList.remove('border-sidebar-green', 'ring-1', 'ring-sidebar-green');
                this.classList.add('border-green-400');
            } else {
                this.classList.remove('border-green-400');
            }
        });

        input.addEventListener('focus', function() {
            this.classList.remove('border-green-400');
        });
    });

    // Language dropdown toggle
    function toggleLanguageDropdown() {
        const dropdown = document.getElementById('language-dropdown');
        const arrow = document.getElementById('language-arrow');
        
        if (dropdown.classList.contains('hidden')) {
            dropdown.classList.remove('hidden');
            arrow.style.transform = 'rotate(180deg)';
        } else {
            dropdown.classList.add('hidden');
            arrow.style.transform = 'rotate(0deg)';
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const switcher = document.getElementById('language-switcher');
        const dropdown = document.getElementById('language-dropdown');
        
        if (switcher && dropdown && !switcher.contains(event.target)) {
            dropdown.classList.add('hidden');
            const arrow = document.getElementById('language-arrow');
            if (arrow) {
                arrow.style.transform = 'rotate(0deg)';
            }
        }
    });
    </script>
@livewireScripts
</body>
</html>

