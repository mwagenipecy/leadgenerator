<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog & News - Fanikisha Market place</title>
    <link rel="icon" type="image/png" href="{{ asset('landing/applicationIcon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
   
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Custom Brand Color */
        .bg-brand-green {
            background-color: #C40F11;
        }
        
        .text-brand-green {
            color: #C40F11;
        }
        
        .border-brand-green {
            border-color: #C40F11;
        }
        
        .hover\:bg-brand-green:hover {
            background-color: #C40F11;
        }
        
        .hover\:text-brand-green:hover {
            color: #C40F11;
        }
        
        .hover\:bg-brand-green-light:hover {
            background-color: #999999;
        }

        .floating-navbar-shell {
            border-radius: 1.15rem;
            background: linear-gradient(120deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.08));
            border: 1px solid rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.09);
            transition: all 0.3s ease;
        }

        .floating-navbar-shell.navbar-scrolled {
            background: linear-gradient(120deg, rgba(255, 255, 255, 0.24), rgba(255, 255, 255, 0.12));
            border-color: rgba(255, 255, 255, 0.48);
            box-shadow: 0 14px 30px rgba(0, 0, 0, 0.12);
        }

        .floating-navbar-shell .nav-scroll-link,
        .floating-navbar-shell #language-switcher-button,
        .floating-navbar-shell.navbar-scrolled .nav-scroll-link,
        .floating-navbar-shell.navbar-scrolled #language-switcher-button {
            color: #C40F11;
        }
    </style>
</head>
<body class="bg-white text-gray-900 font-inter overflow-x-hidden">
    <x-landing.navbar :is-home="false" />

    <!-- Blog Content -->
    <main class="pt-24">
        <livewire:blog.blog-listing />
    </main>

    <x-landing.footer />

    <!-- JavaScript for shared navbar behavior -->
    <script>
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobileMenu');
            const menuIcon = document.getElementById('menuIcon');
            mobileMenu.classList.toggle('hidden');
            
            // Animate menu icon
            if (!mobileMenu.classList.contains('hidden')) {
                menuIcon.style.transform = 'rotate(90deg)';
            } else {
                menuIcon.style.transform = 'rotate(0deg)';
            }
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const mobileMenu = document.getElementById('mobileMenu');
            const menuButton = event.target.closest('button');
            
            if (!mobileMenu.contains(event.target) && !menuButton) {
                mobileMenu.classList.add('hidden');
                const menuIcon = document.getElementById('menuIcon');
                if (menuIcon) {
                    menuIcon.style.transform = 'rotate(0deg)';
                }
            }
        });

        // Navbar scroll effect
        const navbar = document.getElementById('mainNavbar');
        const navbarShell = navbar ? navbar.querySelector('.floating-navbar-shell') : null;
        
        window.addEventListener('scroll', function() {
            const currentScroll = window.pageYOffset;

            if (!navbarShell) {
                return;
            }

            if (currentScroll > 10) {
                navbarShell.classList.add('navbar-scrolled');
            } else {
                navbarShell.classList.remove('navbar-scrolled');
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href !== '#' && href.length > 1) {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        const offsetTop = target.offsetTop - 80; // Account for navbar height
                        window.scrollTo({
                            top: offsetTop,
                            behavior: 'smooth'
                        });
                        // Close mobile menu if open
                        const mobileMenu = document.getElementById('mobileMenu');
                        if (!mobileMenu.classList.contains('hidden')) {
                            toggleMobileMenu();
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>

