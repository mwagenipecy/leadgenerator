
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Creditinfo Lead Generator</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-red': '#C40F12',
                        'brand-dark-red': '#A00E11',
                        'sidebar-black': '#0A0A0A',
                        'sidebar-gray': '#1F1F1F',
                        'accent-gray': '#F8F9FA',
                    },
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                        'poppins': ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>

@vite(['resources/css/app.css', 'resources/js/app.js'])

<!-- Styles -->
@livewireStyles


</head>
<body class="bg-accent-gray font-inter">
    <div class="flex h-screen overflow-hidden">
        <!-- Enhanced Responsive Sidebar -->
      <livewire:layout.side-bar />

        <!-- Main Content Area -->
        <div class="flex-1 overflow-y-auto lg:ml-0">
            <!-- Enhanced Responsive Header -->
           <livewire:layout.nav-bar />
            <!-- Dashboard Content -->
            <main class="flex-1 overflow-y-auto bg-accent-gray p-4 sm:p-6 lg:p-8">
                <!-- Sample Content Area -->
               

                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>


    @livewireScripts


    <!-- JavaScript -->
    <script>
        // Enhanced Mobile menu functionality
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        mobileMenuButton.addEventListener('click', function() {
            sidebar.classList.toggle('-translate-x-full');
            sidebarOverlay.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden');
        });

        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        });

        // Auto-hide mobile menu on window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
            }
        });

        // Mobile search functionality
        const mobileSearchButton = document.querySelector('.sm\\:hidden svg');
        const mobileSearch = document.getElementById('mobile-search');

        if (mobileSearchButton && mobileSearch) {
            mobileSearchButton.parentElement.addEventListener('click', function() {
                mobileSearch.classList.toggle('hidden');
            });
        }

        // Enhanced hover effects for metric cards
        document.querySelectorAll('.hover\\:shadow-md').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Simulate real-time notifications
        setInterval(function() {
            const notification = document.querySelector('.animate-pulse');
            if (notification) {
                notification.classList.add('scale-110');
                setTimeout(() => notification.classList.remove('scale-110'), 300);
            }
        }, 8000);

        // Add smooth scrolling
        const mainContent = document.querySelector('main');
        if (mainContent) {
            mainContent.style.scrollBehavior = 'smooth';
        }

        // Initialize tooltips and interactive elements
        document.addEventListener('DOMContentLoaded', function() {
            // Add loading states to buttons
            document.querySelectorAll('button').forEach(button => {
                button.addEventListener('click', function(e) {
                    if (this.textContent.includes('Add Lead') || this.textContent.includes('View All') || this.textContent.includes('Generate Report')) {
                        e.preventDefault();
                        const originalText = this.textContent;
                        this.textContent = 'Loading...';
                        this.disabled = true;
                        
                        setTimeout(() => {
                            this.textContent = originalText;
                            this.disabled = false;
                        }, 1500);
                    }
                });
            });

            // Enhanced search functionality
            const searchInputs = document.querySelectorAll('input[type="text"]');
            searchInputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('ring-2', 'ring-brand-red/50');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('ring-2', 'ring-brand-red/50');
                });

                // Add search suggestions (placeholder functionality)
                input.addEventListener('input', function() {
                    if (this.value.length > 2) {
                        // Here you would implement search suggestions
                        console.log('Searching for:', this.value);
                    }
                });
            });

            // Add keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl/Cmd + K to focus search
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    const searchInput = document.querySelector('input[type="text"]');
                    if (searchInput) {
                        searchInput.focus();
                    }
                }

                // Escape to close mobile menu
                if (e.key === 'Escape') {
                    sidebar.classList.add('-translate-x-full');
                    sidebarOverlay.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            });
        });

        // Performance optimization: Lazy load heavy components
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '50px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in');
                }
            });
        }, observerOptions);

        // Observe all major sections
        document.querySelectorAll('.bg-white').forEach(section => {
            observer.observe(section);
        });

        // Add touch gestures for mobile sidebar
        let touchStartX = 0;
        let touchEndX = 0;

        document.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        });

        document.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });

        function handleSwipe() {
            const swipeThreshold = 50;
            const swipeDistance = touchEndX - touchStartX;

            if (window.innerWidth < 1024) {
                // Swipe right to open sidebar
                if (swipeDistance > swipeThreshold && touchStartX < 50) {
                    sidebar.classList.remove('-translate-x-full');
                    sidebarOverlay.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                }
                // Swipe left to close sidebar
                else if (swipeDistance < -swipeThreshold && !sidebar.classList.contains('-translate-x-full')) {
                    sidebar.classList.add('-translate-x-full');
                    sidebarOverlay.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            }
        }
    </script>

    <style>
        /* Enhanced Custom scrollbar styling */
        .overflow-y-auto::-webkit-scrollbar {
            width: 4px;
        }
        
        .overflow-y-auto::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #C40F12;
            border-radius: 2px;
        }
        
        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #A00E11;
        }

        /* Custom animation classes */
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.6s ease-out;
        }

        /* Smooth transitions for all interactive elements */
        * {
            transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 200ms;
        }

        /* Enhanced focus states for accessibility */
        button:focus-visible,
        input:focus-visible {
            outline: 2px solid #C40F12;
            outline-offset: 2px;
        }

        /* Gradient background for active states */
        .bg-gradient-to-r {
            background-size: 200% 200%;
            animation: gradient-shift 3s ease infinite;
        }

        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Mobile-specific improvements */
        @media (max-width: 640px) {
            .truncate {
                max-width: 120px;
            }
        }

        /* Smooth sidebar animation */
        #sidebar {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Enhanced button hover states */
        button:hover {
            transform: translateY(-1px);
        }

        button:active {
            transform: translateY(0);
        }

        /* Custom loading animation */
        @keyframes pulse-scale {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .animate-pulse-scale {
            animation: pulse-scale 2s ease-in-out infinite;
        }

        /* Improved responsive grid */
        @media (min-width: 640px) and (max-width: 1023px) {
            .grid-cols-1.sm\\:grid-cols-2 > * {
                min-height: 140px;
            }
        }
    </style>
</body>
</html>


