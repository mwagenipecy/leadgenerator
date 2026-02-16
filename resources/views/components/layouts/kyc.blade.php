<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company KYC Verification - Fanikisha</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @livewireStyles


    @vite(['resources/css/app.css', 'resources/js/app.js'])

 
</head>
<body class="bg-gray-50 font-inter">
    {{ $slot }}

    @livewireScripts
    
    <script>
        // Handle redirect event from Livewire (for NIDA verification redirect)
        document.addEventListener('livewire:init', () => {
            Livewire.on('redirect', (data) => {
                if (data && data.url) {
                    window.location.href = data.url;
                } else if (Array.isArray(data) && data[0] && data[0].url) {
                    window.location.href = data[0].url;
                }
            });
        });
    </script>
</body>
</html>

