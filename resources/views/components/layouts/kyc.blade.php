<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company KYC Verification - Lead Generator</title>

    <meta http-equiv="Cross-Origin-Resource-Policy" content="same-origin">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-Frame-Options" content="SAMEORIGIN">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>
<body class="bg-gray-50 font-inter">
    {{ $slot }}

    @livewireScripts
    
    <script>
        // Handle redirect event from Livewire
        document.addEventListener('livewire:init', () => {
            Livewire.on('redirect', (event) => {
                window.location.href = event.url;
            });
        });
        
        // Handle file upload errors
        document.addEventListener('livewire:error', (event) => {
            console.error('Livewire error:', event.detail);
        });
    </script>
</body>
</html>

