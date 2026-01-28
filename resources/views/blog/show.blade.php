<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@if(isset($post)){{ $post->title }} - @endif Blog - Fanikisha Market place</title>
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
    </style>
</head>
<body class="bg-white text-gray-900 font-inter overflow-x-hidden">
    <!-- Navigation -->
    <nav id="mainNavbar" class="sticky top-0 z-50 bg-white transition-all duration-300" style="box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="/" class="flex items-center group">
                    <img src="{{ asset('landing/redlogo.png') }}" alt="Fanikisha Market place Logo" class="h-12 lg:h-14 w-auto transition-transform duration-300 group-hover:scale-105">
                </a>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-1 lg:space-x-2">
                    <a href="/#eligibility" 
                       class="nav-link px-4 py-2 rounded-lg text-gray-700 hover:text-white hover:bg-red-600 transition-all duration-300 font-medium text-sm lg:text-base relative group"
                       style="color: #C40F11;">
                        <span class="relative z-10">Eligibility</span>
                        <span class="absolute inset-0 bg-red-600 rounded-lg scale-0 group-hover:scale-100 transition-transform duration-300 origin-center"></span>
                    </a>
                    <a href="/#process" 
                       class="nav-link px-4 py-2 rounded-lg text-gray-700 hover:text-white hover:bg-red-600 transition-all duration-300 font-medium text-sm lg:text-base relative group"
                       style="color: #C40F11;">
                        <span class="relative z-10">Process</span>
                        <span class="absolute inset-0 bg-red-600 rounded-lg scale-0 group-hover:scale-100 transition-transform duration-300 origin-center"></span>
                    </a>
                    <a href="{{ route('blog.index') }}" 
                       class="nav-link px-4 py-2 rounded-lg text-gray-700 hover:text-white hover:bg-red-600 transition-all duration-300 font-medium text-sm lg:text-base relative group"
                       style="color: #C40F11;">
                        <span class="relative z-10">Blog</span>
                        <span class="absolute inset-0 bg-red-600 rounded-lg scale-0 group-hover:scale-100 transition-transform duration-300 origin-center"></span>
                    </a>
                    <a href="{{ route('login') }}" 
                       class="ml-2 px-6 py-2.5 rounded-lg text-white font-semibold text-sm lg:text-base hover:shadow-lg hover:scale-105 transition-all duration-300 relative overflow-hidden group"
                       style="background-color: #C40F11;">
                        <span class="relative z-10 flex items-center">
                            Get Started
                            <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </span>
                        <span class="absolute inset-0 bg-red-800 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                    </a>
                </div>
                
                <!-- Mobile Menu Button -->
                <button class="md:hidden p-2.5 rounded-lg text-gray-700 hover:bg-gray-100 hover:text-red-600 transition-all duration-300" onclick="toggleMobileMenu()" aria-label="Toggle menu">
                    <svg id="menuIcon" class="w-6 h-6 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Mobile Navigation -->
            <div id="mobileMenu" class="hidden md:hidden border-t border-gray-200 bg-white">
                <div class="py-4 space-y-1">
                    <a href="/#eligibility" 
                       onclick="toggleMobileMenu()"
                       class="block px-4 py-3 rounded-lg text-gray-700 hover:text-white hover:bg-red-600 transition-all duration-300 font-medium"
                       style="color: #C40F11;">
                        Eligibility
                    </a>
                    <a href="/#process" 
                       onclick="toggleMobileMenu()"
                       class="block px-4 py-3 rounded-lg text-gray-700 hover:text-white hover:bg-red-600 transition-all duration-300 font-medium"
                       style="color: #C40F11;">
                        Process
                    </a>
                    <a href="{{ route('blog.index') }}" 
                       onclick="toggleMobileMenu()"
                       class="block px-4 py-3 rounded-lg text-gray-700 hover:text-white hover:bg-red-600 transition-all duration-300 font-medium"
                       style="color: #C40F11;">
                        Blog
                    </a>
                    <a href="{{ route('login') }}" 
                       onclick="toggleMobileMenu()"
                       class="block px-4 py-3 rounded-lg text-white font-semibold mt-2 transition-all duration-300"
                       style="background-color: #C40F11;">
                        Get Started
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Blog Detail Content -->
    <livewire:blog.blog-detail :slug="$slug" />

    <!-- Footer -->
    <footer class="bg-white text-gray-900 py-12 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 lg:px-8">
            <!-- Main Footer Content -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                
                <!-- Brand Section -->
                <div class="md:col-span-1">
                    <a href="/" class="inline-block mb-4">
                        <img src="{{ asset('landing/redlogo.png') }}" alt="Fanikisha Market place Logo" class="h-10 w-auto">
                    </a>
                    <p class="text-gray-600 text-sm leading-relaxed mb-4">
                        Connecting borrowers with verified lenders. Loans for everyone, regardless of employment status.
                    </p>
                    <div class="flex space-x-4">
                        <!-- Social Icons -->
                        <a href="#" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-brand-green hover:text-white transition-all text-gray-700">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-brand-green hover:text-white transition-all text-gray-700">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-brand-green hover:text-white transition-all text-gray-700">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="font-semibold font-poppins text-lg mb-4 text-gray-900">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="/" class="text-gray-600 hover:text-brand-green transition-colors text-sm">Home</a></li>
                        <li><a href="/#eligibility" class="text-gray-600 hover:text-brand-green transition-colors text-sm">Eligibility</a></li>
                        <li><a href="/#process" class="text-gray-600 hover:text-brand-green transition-colors text-sm">How It Works</a></li>
                        <li><a href="{{ route('blog.index') }}" class="text-gray-600 hover:text-brand-green transition-colors text-sm">Blog</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h4 class="font-semibold font-poppins text-lg mb-4 text-gray-900">Support</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-600 hover:text-brand-green transition-colors text-sm">Help Center</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-brand-green transition-colors text-sm">Contact Us</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-brand-green transition-colors text-sm">Live Chat</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-brand-green transition-colors text-sm">FAQs</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-brand-green transition-colors text-sm">Status</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="font-semibold font-poppins text-lg mb-4 text-gray-900">Contact</h4>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span class="text-gray-600 text-sm">+255 123 456 789</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-gray-600 text-sm">info@fanikisha.com</span>
                        </div>
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-brand-green mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="text-gray-600 text-sm">Dar es Salaam, Tanzania</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Bottom Footer -->
            <div class="border-t border-gray-200 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="text-gray-500 text-sm mb-4 md:mb-0">
                        © 2025 Fanikisha Market place. All rights reserved.
                    </div>
                    <div class="flex flex-wrap gap-6">
                        <a href="#" class="text-gray-500 hover:text-brand-green text-sm transition-colors">Privacy Policy</a>
                        <a href="#" class="text-gray-500 hover:text-brand-green text-sm transition-colors">Terms of Service</a>
                        <a href="#" class="text-gray-500 hover:text-brand-green text-sm transition-colors">Cookie Policy</a>
                        <a href="#" class="text-gray-500 hover:text-brand-green text-sm transition-colors">Compliance</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript for Mobile Menu and Navbar Scroll Effect -->
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

        // Navbar scroll effect - add shadow on scroll
        let lastScroll = 0;
        const navbar = document.getElementById('mainNavbar');
        
        window.addEventListener('scroll', function() {
            const currentScroll = window.pageYOffset;
            
            if (currentScroll > 10) {
                navbar.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)';
            } else {
                navbar.style.boxShadow = '0 1px 3px 0 rgba(0, 0, 0, 0.1)';
            }
            
            lastScroll = currentScroll;
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

