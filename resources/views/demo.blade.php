<!-- resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Lead Generator - Professional Lead Generation Solutions</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased font-sans text-gray-900 bg-white">



<livewire:verification-method-selector />


<livewire:phone-photo-verification />


<livewire:qr-code-verification />

<livewire:questionnaire-verification />

    <!-- Navbar -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <!-- Logo -->
                    <a href="#" class="flex-shrink-0 flex items-center">
                        <svg class="h-10 w-10 text-red-600" viewBox="0 0 40 40" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 40C31.0457 40 40 31.0457 40 20C40 8.9543 31.0457 0 20 0C8.9543 0 0 8.9543 0 20C0 31.0457 8.9543 40 20 40Z"/>
                            <path d="M12 12L28 28M12 28L28 12" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="ml-3 text-xl font-bold text-gray-900">Lead Generator</span>
                    </a>
                </div>

                <!-- Navigation Links - Desktop -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-base font-medium text-gray-700 hover:text-red-600 transition duration-150 ease-in-out">Features</a>
                    <a href="#how-it-works" class="text-base font-medium text-gray-700 hover:text-red-600 transition duration-150 ease-in-out">How It Works</a>
                    <a href="#testimonials" class="text-base font-medium text-gray-700 hover:text-red-600 transition duration-150 ease-in-out">Testimonials</a>
                    <a href="#contact" class="text-base font-medium text-gray-700 hover:text-red-600 transition duration-150 ease-in-out">Contact</a>
                    <a href="/login" class="ml-8 inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                        Login
                    </a>
                    <a href="/register" class="inline-flex items-center justify-center px-4 py-2 border border-red-600 rounded-md shadow-sm text-base font-medium text-red-600 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                        Register
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="flex items-center md:hidden">
                    <button type="button" class="mobile-menu-button p-2 rounded-md inline-flex items-center justify-center text-gray-700 hover:text-red-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-red-500">
                        <span class="sr-only">Open main menu</span>
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu, show/hide based on menu state -->
        <div class="mobile-menu hidden md:hidden bg-white border-b border-gray-200 pb-4">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="#features" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-red-600 hover:bg-gray-50 transition duration-150 ease-in-out">Features</a>
                <a href="#how-it-works" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-red-600 hover:bg-gray-50 transition duration-150 ease-in-out">How It Works</a>
                <a href="#testimonials" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-red-600 hover:bg-gray-50 transition duration-150 ease-in-out">Testimonials</a>
                <a href="#contact" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-red-600 hover:bg-gray-50 transition duration-150 ease-in-out">Contact</a>
            </div>
            <div class="pt-4 pb-3 border-t border-gray-200">
                <div class="px-2 space-y-3">
                    <a href="/login" class="block w-full text-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                        Login
                    </a>
                    <a href="/register" class="block w-full text-center px-4 py-2 border border-red-600 rounded-md shadow-sm text-base font-medium text-red-600 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                        Register
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-r from-[#2D2D2D] to-gray-900 py-20 md:py-32">
        <div class="absolute inset-0 bg-red-600 opacity-5 pattern-dots"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-12 md:mb-0">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-6">
                    Generate High-Quality <span class="text-red-500">Leads</span> & Manage Receipts
                </h1>
                <p class="text-xl text-gray-300 mb-8 max-w-lg">
                    A powerful platform for businesses to generate qualified leads and manage receipt requests efficiently. All in one place.
                </p>
                <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                    <a href="#contact" class="inline-flex items-center justify-center px-8 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                        Get Started
                    </a>
                    <a href="#how-it-works" class="inline-flex items-center justify-center px-8 py-3 border border-gray-300 rounded-md shadow-sm text-base font-medium text-white bg-transparent hover:bg-white hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition duration-150 ease-in-out">
                        Learn More
                    </a>
                </div>
            </div>
            <div class="md:w-1/2 md:ml-auto">
                <div class="relative mx-auto w-full max-w-md">
                    <div class="absolute top-0 -left-4 w-64 h-64 bg-red-500 rounded-full mix-blend-multiply filter blur-3xl opacity-25 animate-blob"></div>
                    <div class="absolute top-0 -right-4 w-64 h-64 bg-red-500 rounded-full mix-blend-multiply filter blur-3xl opacity-25 animate-blob animation-delay-2000"></div>
                    <div class="absolute -bottom-8 left-16 w-64 h-64 bg-red-500 rounded-full mix-blend-multiply filter blur-3xl opacity-25 animate-blob animation-delay-4000"></div>
                    <div class="relative">
                        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                            <div class="px-6 py-8">
                                <h3 class="text-2xl font-bold text-gray-800 mb-4">Start Generating Leads Today</h3>
                                <form class="space-y-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                        <input type="text" id="name" name="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                                    </div>
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                        <input type="email" id="email" name="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                                    </div>
                                    <div>
                                        <label for="company" class="block text-sm font-medium text-gray-700">Company</label>
                                        <input type="text" id="company" name="company" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                                    </div>
                                    <div>
                                        <button type="submit" class="w-full py-3 px-4 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                                            Schedule a Demo
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trusted By Section -->
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-center text-lg font-medium text-gray-500 mb-8">Trusted by businesses worldwide</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8">
                <div class="flex items-center justify-center opacity-60 grayscale hover:grayscale-0 hover:opacity-100 transition duration-300">
                    <svg class="h-10" viewBox="0 0 100 30" fill="none">
                        <rect width="100" height="30" rx="4" fill="#2D2D2D"/>
                        <text x="20" y="20" fill="white" font-family="Arial" font-size="12">COMPANY 1</text>
                    </svg>
                </div>
                <div class="flex items-center justify-center opacity-60 grayscale hover:grayscale-0 hover:opacity-100 transition duration-300">
                    <svg class="h-10" viewBox="0 0 100 30" fill="none">
                        <rect width="100" height="30" rx="4" fill="#2D2D2D"/>
                        <text x="20" y="20" fill="white" font-family="Arial" font-size="12">COMPANY 2</text>
                    </svg>
                </div>
                <div class="flex items-center justify-center opacity-60 grayscale hover:grayscale-0 hover:opacity-100 transition duration-300">
                    <svg class="h-10" viewBox="0 0 100 30" fill="none">
                        <rect width="100" height="30" rx="4" fill="#2D2D2D"/>
                        <text x="20" y="20" fill="white" font-family="Arial" font-size="12">COMPANY 3</text>
                    </svg>
                </div>
                <div class="flex items-center justify-center opacity-60 grayscale hover:grayscale-0 hover:opacity-100 transition duration-300">
                    <svg class="h-10" viewBox="0 0 100 30" fill="none">
                        <rect width="100" height="30" rx="4" fill="#2D2D2D"/>
                        <text x="20" y="20" fill="white" font-family="Arial" font-size="12">COMPANY 4</text>
                    </svg>
                </div>
                <div class="flex items-center justify-center opacity-60 grayscale hover:grayscale-0 hover:opacity-100 transition duration-300">
                    <svg class="h-10" viewBox="0 0 100 30" fill="none">
                        <rect width="100" height="30" rx="4" fill="#2D2D2D"/>
                        <text x="20" y="20" fill="white" font-family="Arial" font-size="12">COMPANY 5</text>
                    </svg>
                </div>
                <div class="flex items-center justify-center opacity-60 grayscale hover:grayscale-0 hover:opacity-100 transition duration-300">
                    <svg class="h-10" viewBox="0 0 100 30" fill="none">
                        <rect width="100" height="30" rx="4" fill="#2D2D2D"/>
                        <text x="20" y="20" fill="white" font-family="Arial" font-size="12">COMPANY 6</text>
                    </svg>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-base font-semibold text-red-600 uppercase tracking-wide">Features</h2>
                <p class="mt-1 text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight">Everything you need to succeed</p>
                <p class="max-w-xl mt-5 mx-auto text-xl text-gray-500">Our comprehensive platform offers all the tools you need to generate and manage leads effectively.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white rounded-lg p-6 shadow-md hover:shadow-xl transition duration-300 border border-gray-100">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Dynamic Lead Forms</h3>
                    <p class="text-gray-600">Create customizable forms to capture leads from multiple channels. Tailor each form to your specific needs.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white rounded-lg p-6 shadow-md hover:shadow-xl transition duration-300 border border-gray-100">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Receipt Management</h3>
                    <p class="text-gray-600">Effortlessly handle receipt requests, track history, and deliver documents to your customers quickly.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white rounded-lg p-6 shadow-md hover:shadow-xl transition duration-300 border border-gray-100">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Analytics Dashboard</h3>
                    <p class="text-gray-600">Gain valuable insights with comprehensive analytics. Track lead sources, conversion rates, and performance metrics.</p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-white rounded-lg p-6 shadow-md hover:shadow-xl transition duration-300 border border-gray-100">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Automated Notifications</h3>
                    <p class="text-gray-600">Stay informed with real-time alerts for new leads, form submissions, and customer requests.</p>
                </div>

                <!-- Feature 5 -->
                <div class="bg-white rounded-lg p-6 shadow-md hover:shadow-xl transition duration-300 border border-gray-100">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Secure Data Handling</h3>
                    <p class="text-gray-600">Your data security is our priority. Advanced encryption and compliance measures keep information safe.</p>
                </div>

                <!-- Feature 6 -->
                <div class="bg-white rounded-lg p-6 shadow-md hover:shadow-xl transition duration-300 border border-gray-100">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Integration Capabilities</h3>
                    <p class="text-gray-600">Seamlessly integrate with your existing tools like CRM, email marketing, and accounting software.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-base font-semibold text-red-600 uppercase tracking-wide">How It Works</h2>
                <p class="mt-1 text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight">Simple, efficient, and powerful</p>
                <p class="max-w-xl mt-5 mx-auto text-xl text-gray-500">Our platform makes lead generation and receipt management a breeze in just a few simple steps.</p>
            </div>

            <div class="relative">
                <!-- Process Timeline -->
                <div class="hidden md:block absolute left-1/2 transform -translate-x-1/2 h-full w-1 bg-red-100"></div>

                <!-- Step 1 -->
                <div class="relative mb-12 md:mb-0">
                    <div class="flex flex-col md:flex-row items-center">
                        <div class="flex-1 md:pr-12 md:text-right mb-6 md:mb-0">
                            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition duration-300 inline-block">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">1. Create Custom Forms</h3>
                                <p class="text-gray-600">Design personalized lead capture forms tailored to your specific business needs. Choose from various field types and customization options.</p>
                            </div>
                        </div>
                        <div class="hidden md:flex items-center justify-center w-12 h-12 bg-red-600 rounded-full text-white font-bold z-10">1</div>
                        <div class="flex-1 md:pl-12"></div>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="relative mb-12 md:mb-0 md:mt-24">
                    <div class="flex flex-col md:flex-row items-center">
                        <div class="flex-1 md:pr-12 md:text-right mb-6 md:mb-0 md:hidden">
                            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition duration-300 inline-block">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">2. Collect and Organize Leads</h3>
                                <p class="text-gray-600">Capture leads from multiple channels and organize them automatically. All data is securely stored and easily accessible.</p>
                            </div>
                        </div>
                        <div class="hidden md:flex items-center justify-center w-12 h-12 bg-red-600 rounded-full text-white font-bold z-10">2</div>
                        <div class="flex-1 md:pl-12 hidden md:block">
                            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition duration-300 inline-block">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">2. Collect and Organize Leads</h3>
                                <p class="text-gray-600">Capture leads from multiple channels and organize them automatically. All data is securely stored and easily accessible.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="relative mb-12 md:mb-0 md:mt-24">
                    <div class="flex flex-col md:flex-row items-center">
                        <div class="flex-1 md:pr-12 md:text-right mb-6 md:mb-0">
                            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition duration-300 inline-block">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">3. Manage Receipt Requests</h3>
                                <p class="text-gray-600">Process receipt requests efficiently with our streamlined workflow. Generate, deliver, and track receipts all in one place.</p>
                            </div>
                        </div>
                        <div class="hidden md:flex items-center justify-center w-12 h-12 bg-red-600 rounded-full text-white font-bold z-10">3</div>
                        <div class="flex-1 md:pl-12"></div>
                    </div>
                </div>

             <!-- Step 4 -->
             <div class="relative md:mt-24">
                    <div class="flex flex-col md:flex-row items-center">
                        <div class="flex-1 md:pr-12 md:text-right mb-6 md:mb-0 md:hidden">
                            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition duration-300 inline-block">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">4. Analyze and Optimize</h3>
                                <p class="text-gray-600">Monitor performance with comprehensive analytics. Gain insights to optimize your lead generation strategy for better results.</p>
                            </div>
                        </div>
                        <div class="hidden md:flex items-center justify-center w-12 h-12 bg-red-600 rounded-full text-white font-bold z-10">4</div>
                        <div class="flex-1 md:pl-12 hidden md:block">
                            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition duration-300 inline-block">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">4. Analyze and Optimize</h3>
                                <p class="text-gray-600">Monitor performance with comprehensive analytics. Gain insights to optimize your lead generation strategy for better results.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-base font-semibold text-red-600 uppercase tracking-wide">Testimonials</h2>
                <p class="mt-1 text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight">What our customers say</p>
                <p class="max-w-xl mt-5 mx-auto text-xl text-gray-500">Don't just take our word for it—hear from our satisfied customers.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-white rounded-xl p-8 shadow-md hover:shadow-xl transition duration-300 border border-gray-100">
                    <div class="flex items-center mb-6">
                        <div class="text-yellow-400 flex">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        </div>
                    </div>
                    <blockquote class="text-gray-700 mb-6">
                        "Lead Generator has completely transformed how we handle leads and receipts. The efficiency and organization it brings to our business is invaluable. Our lead conversion rate has increased by 45% since implementation."
                    </blockquote>
                    <div class="flex items-center">
                        <div class="h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center">
                            <span class="text-xl font-bold text-gray-600">SJ</span>
                        </div>
                        <div class="ml-4">
                            <p class="font-medium text-gray-900">Sarah Johnson</p>
                            <p class="text-gray-500 text-sm">Marketing Director, TechSolutions</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-white rounded-xl p-8 shadow-md hover:shadow-xl transition duration-300 border border-gray-100">
                    <div class="flex items-center mb-6">
                        <div class="text-yellow-400 flex">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        </div>
                    </div>
                    <blockquote class="text-gray-700 mb-6">
                        "The receipt management feature has saved my accounting team countless hours. What used to take days now takes minutes. The platform is intuitive and the customer support is exceptional."
                    </blockquote>
                    <div class="flex items-center">
                        <div class="h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center">
                            <span class="text-xl font-bold text-gray-600">MR</span>
                        </div>
                        <div class="ml-4">
                            <p class="font-medium text-gray-900">Michael Rodriguez</p>
                            <p class="text-gray-500 text-sm">CFO, Global Retail Inc.</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="bg-white rounded-xl p-8 shadow-md hover:shadow-xl transition duration-300 border border-gray-100">
                    <div class="flex items-center mb-6">
                        <div class="text-yellow-400 flex">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        </div>
                    </div>
                    <blockquote class="text-gray-700 mb-6">
                        "As a small business owner, I needed a solution that was powerful yet easy to use. Lead Generator delivered exactly that. The analytics have given me insights I never had before, helping me make better business decisions."
                    </blockquote>
                    <div class="flex items-center">
                        <div class="h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center">
                            <span class="text-xl font-bold text-gray-600">AP</span>
                        </div>
                        <div class="ml-4">
                            <p class="font-medium text-gray-900">Amelia Patel</p>
                            <p class="text-gray-500 text-sm">Owner, Boutique Agency</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- Pricing Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-base font-semibold text-red-600 uppercase tracking-wide">Pricing</h2>
                <p class="mt-1 text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight">Plans for businesses of all sizes</p>
                <p class="max-w-xl mt-5 mx-auto text-xl text-gray-500">Choose a plan that works best for your business needs.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Starter Plan -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition duration-300 overflow-hidden">
                    <div class="px-6 py-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Starter</h3>
                        <p class="text-gray-600 mb-6">Perfect for small businesses just getting started</p>
                        <div class="flex items-baseline mb-6">
                            <span class="text-4xl font-extrabold text-gray-900">$29</span>
                            <span class="text-gray-500 ml-1">/month</span>
                        </div>
                        <ul class="space-y-4 mb-8">
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="ml-2 text-gray-700">Up to 500 leads</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="ml-2 text-gray-700">2 custom forms</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="ml-2 text-gray-700">Basic receipt management</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="ml-2 text-gray-700">Email support</span>
                            </li>
                        </ul>
                    </div>
                    <div class="bg-gray-50 px-6 py-4">
                        <a href="#contact" class="block w-full text-center px-4 py-2 border border-red-600 rounded-md shadow-sm text-base font-medium text-red-600 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                            Get Started
                        </a>
                    </div>
                </div>

                <!-- Professional Plan -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition duration-300 overflow-hidden">
                    <div class="px-6 py-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Professional</h3>
                        <p class="text-gray-600 mb-6">Ideal for growing businesses</p>
                        <div class="flex items-baseline mb-6">
                            <span class="text-4xl font-extrabold text-gray-900">$99</span>
                            <span class="text-gray-500 ml-1">/month</span>
                        </div>
                        <ul class="space-y-4 mb-8">
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="ml-2 text-gray-700">Up to 5,000 leads</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="ml-2 text-gray-700">10 custom forms</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="ml-2 text-gray-700">Advanced receipt management</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="ml-2 text-gray-700">Priority email support</span>
                            </li>
                        </ul>
                    </div>
                    <div class="bg-gray-50 px-6 py-4">
                        <a href="#contact" class="block w-full text-center px-4 py-2 border border-red-600 rounded-md shadow-sm text-base font-medium text-red-600 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                            Get Started
                        </a>
                    </div>
                </div>

                <!-- Enterprise Plan -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition duration-300 overflow-hidden">
                    <div class="px-6 py-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Enterprise</h3>
                        <p class="text-gray-600 mb-6">For large businesses and enterprises</p>
                        <div class="flex items-baseline mb-6">
                            <span class="text-4xl font-extrabold text-gray-900">Custom</span>
                            <span class="text-gray-500 ml-1">pricing</span>
                        </div>
                        <ul class="space-y-4 mb-8">
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="ml-2 text-gray-700">Unlimited leads</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="ml-2 text-gray-700">Unlimited custom forms</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="ml-2 text-gray-700">Dedicated account manager</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="ml-2 text-gray-700">24/7 priority support</span>
                            </li>
                        </ul>
                    </div>
                    <div class="bg-gray-50 px-6 py-4">
                        <a href="#contact" class="block w-full text-center px-4 py-2 border border-red-600 rounded-md shadow-sm text-base font-medium text-red-600 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                            Contact Us
                        </a>
                    </div>
                </div>
                </div>
            </div>
        </section>
    </body>
    </html>