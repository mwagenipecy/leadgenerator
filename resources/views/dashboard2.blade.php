<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Lead Generator</title>
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
                        'dark-bg': '#0F0F0F',
                        'dark-card': '#1A1A1A',
                        'dark-hover': '#2A2A2A',
                        'dark-border': '#333333',
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
<body class="bg-dark-bg font-inter text-white overflow-hidden">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-dark-card shadow-2xl transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 border-r border-dark-border">
            <!-- Logo -->
            <div class="flex items-center justify-center h-16 px-4 bg-gradient-to-r from-brand-red via-brand-dark-red to-red-900 relative overflow-hidden">
                <!-- Animated background -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent transform -skew-x-12 animate-pulse"></div>
                </div>
                <div class="flex items-center space-x-3 relative z-10">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm border border-white/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h1 class="text-xl font-bold font-poppins text-white">
                        Lead<span class="text-red-200">Generator</span>
                    </h1>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="mt-8 px-4">
                <div class="space-y-2">
                    <!-- Dashboard -->
                    <a href="#" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl bg-gradient-to-r from-brand-red/20 to-brand-dark-red/10 text-brand-red border border-brand-red/30 shadow-lg shadow-brand-red/10">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"/>
                        </svg>
                        Dashboard
                        <div class="ml-auto w-2 h-2 bg-brand-red rounded-full animate-pulse"></div>
                    </a>

                    <!-- Leads -->
                    <a href="#" class="group flex items-center px-4 py-3 text-sm font-medium text-gray-300 rounded-xl hover:bg-dark-hover hover:text-white transition-all duration-200 hover:shadow-lg">
                        <svg class="mr-3 h-5 w-5 group-hover:text-brand-red transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Leads
                        <span class="ml-auto bg-gradient-to-r from-brand-red to-brand-dark-red text-white text-xs px-2.5 py-1 rounded-full font-semibold shadow-md">24</span>
                    </a>

                    <!-- Analytics -->
                    <a href="#" class="group flex items-center px-4 py-3 text-sm font-medium text-gray-300 rounded-xl hover:bg-dark-hover hover:text-white transition-all duration-200 hover:shadow-lg">
                        <svg class="mr-3 h-5 w-5 group-hover:text-brand-red transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Analytics
                    </a>

                    <!-- NIDA Verification -->
                    <a href="#" class="group flex items-center px-4 py-3 text-sm font-medium text-gray-300 rounded-xl hover:bg-dark-hover hover:text-white transition-all duration-200 hover:shadow-lg">
                        <svg class="mr-3 h-5 w-5 group-hover:text-brand-red transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        NIDA Verification
                    </a>

                    <!-- Reports -->
                    <a href="#" class="group flex items-center px-4 py-3 text-sm font-medium text-gray-300 rounded-xl hover:bg-dark-hover hover:text-white transition-all duration-200 hover:shadow-lg">
                        <svg class="mr-3 h-5 w-5 group-hover:text-brand-red transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Reports
                    </a>

                    <!-- Settings -->
                    <a href="#" class="group flex items-center px-4 py-3 text-sm font-medium text-gray-300 rounded-xl hover:bg-dark-hover hover:text-white transition-all duration-200 hover:shadow-lg">
                        <svg class="mr-3 h-5 w-5 group-hover:text-brand-red transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Settings
                    </a>
                </div>

                <!-- Support Section -->
                <div class="mt-8 pt-8 border-t border-dark-border">
                    <div class="bg-gradient-to-br from-brand-red/10 via-brand-dark-red/5 to-transparent rounded-xl p-4 border border-brand-red/20 backdrop-blur-sm">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-brand-red/20 to-brand-dark-red/10 rounded-xl flex items-center justify-center border border-brand-red/30">
                                <svg class="w-5 h-5 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-white">Need Help?</p>
                                <p class="text-xs text-gray-400">24/7 Support Available</p>
                            </div>
                        </div>
                        <button class="w-full mt-3 bg-gradient-to-r from-brand-red to-brand-dark-red text-white text-sm font-medium py-2 rounded-lg hover:shadow-lg hover:shadow-brand-red/25 transition-all duration-200">
                            Contact Support
                        </button>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-hidden lg:ml-0">
            <!-- Header -->
            <header class="bg-dark-card shadow-2xl border-b border-dark-border backdrop-blur-xl">
                <div class="flex items-center justify-between px-6 py-4">
                    <!-- Mobile menu button -->
                    <button id="mobile-menu-button" class="lg:hidden p-2 rounded-xl text-gray-400 hover:text-brand-red hover:bg-dark-hover transition-all duration-200">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <!-- Search Bar -->
                    <div class="flex-1 max-w-2xl mx-4">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" class="block w-full pl-12 pr-4 py-3 bg-dark-hover border border-dark-border rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red placeholder-gray-500 text-white text-sm transition-all duration-200" placeholder="Search leads, reports, or settings...">
                        </div>
                    </div>

                    <!-- Header Actions -->
                    <div class="flex items-center space-x-4">
                        <!-- Theme Toggle -->
                        <button class="p-2 text-gray-400 hover:text-brand-red hover:bg-dark-hover rounded-xl transition-all duration-200">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                            </svg>
                        </button>

                        <!-- Notifications -->
                        <button class="relative p-2 text-gray-400 hover:text-brand-red hover:bg-dark-hover rounded-xl transition-all duration-200">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span class="absolute -top-1 -right-1 h-5 w-5 bg-gradient-to-r from-brand-red to-brand-dark-red rounded-full flex items-center justify-center">
                                <span class="text-white text-xs font-bold">3</span>
                            </span>
                        </button>

                        <!-- User Menu -->
                        <div class="relative">
                            <button class="flex items-center space-x-3 p-2 rounded-xl hover:bg-dark-hover transition-all duration-200 group">
                                <div class="w-10 h-10 bg-gradient-to-br from-brand-red via-brand-dark-red to-red-900 rounded-xl flex items-center justify-center shadow-lg shadow-brand-red/25">
                                    <span class="text-white text-sm font-bold">JD</span>
                                </div>
                                <div class="hidden md:block text-left">
                                    <p class="text-sm font-semibold text-white group-hover:text-brand-red transition-colors">John Doe</p>
                                    <p class="text-xs text-gray-400">Administrator</p>
                                </div>
                                <svg class="h-4 w-4 text-gray-500 group-hover:text-brand-red transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Dashboard Content -->
            <main class="flex-1 overflow-y-auto bg-dark-bg">
                <div class="p-6">
                    <!-- Page Header -->
                    <div class="mb-8">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-brand-red to-brand-dark-red rounded-xl flex items-center justify-center shadow-lg shadow-brand-red/25">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-white">Dashboard Overview</h1>
                                <p class="text-gray-400 mt-1">Monitor your lead generation performance in real-time</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 text-sm text-gray-500">
                            <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                            <span>System Status: All services operational</span>
                        </div>
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <!-- Total Leads -->
                        <div class="bg-dark-card rounded-2xl shadow-2xl p-6 border border-dark-border hover:shadow-brand-red/10 hover:shadow-2xl transition-all duration-300 group hover:border-brand-red/30">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-400 group-hover:text-gray-300">Total Leads</p>
                                    <p class="text-4xl font-bold text-white mt-2 group-hover:text-brand-red transition-colors">1,249</p>
                                    <div class="flex items-center mt-3">
                                        <div class="flex items-center space-x-1 text-green-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                            </svg>
                                            <span class="text-sm font-semibold">+12%</span>
                                        </div>
                                        <span class="text-sm text-gray-500 ml-2">vs last month</span>
                                    </div>
                                </div>
                                <div class="w-16 h-16 bg-gradient-to-br from-brand-red/20 to-brand-dark-red/10 rounded-2xl flex items-center justify-center border border-brand-red/20 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-8 h-8 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Verified -->
                        <div class="bg-dark-card rounded-2xl shadow-2xl p-6 border border-dark-border hover:shadow-green-500/10 hover:shadow-2xl transition-all duration-300 group hover:border-green-500/30">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-400 group-hover:text-gray-300">NIDA Verified</p>
                                    <p class="text-4xl font-bold text-white mt-2 group-hover:text-green-400 transition-colors">987</p>
                                    <div class="flex items-center mt-3">
                                        <div class="flex items-center space-x-1 text-green-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span class="text-sm font-semibold">79%</span>
                                        </div>
                                        <span class="text-sm text-gray-500 ml-2">verification rate</span>
                                    </div>
                                </div>
                                <div class="w-16 h-16 bg-gradient-to-br from-green-500/20 to-green-600/10 rounded-2xl flex items-center justify-center border border-green-500/20 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Conversion Rate -->
                        <div class="bg-dark-card rounded-2xl shadow-2xl p-6 border border-dark-border hover:shadow-blue-500/10 hover:shadow-2xl transition-all duration-300 group hover:border-blue-500/30">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-400 group-hover:text-gray-300">Conversion Rate</p>
                                    <p class="text-4xl font-bold text-white mt-2 group-hover:text-blue-400 transition-colors">23.7%</p>
                                    <div class="flex items-center mt-3">
                                        <div class="flex items-center space-x-1 text-blue-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                            </svg>
                                            <span class="text-sm font-semibold">+3.2%</span>
                                        </div>
                                        <span class="text-sm text-gray-500 ml-2">this week</span>
                                    </div>
                                </div>
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-500/20 to-blue-600/10 rounded-2xl flex items-center justify-center border border-blue-500/20 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Revenue -->
                        <div class="bg-dark-card rounded-2xl shadow-2xl p-6 border border-dark-border hover:shadow-purple-500/10 hover:shadow-2xl transition-all duration-300 group hover:border-purple-500/30">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-400 group-hover:text-gray-300">Revenue</p>
                                    <p class="text-4xl font-bold text-white mt-2 group-hover:text-purple-400 transition-colors">TSh 45.2M</p>
                                    <div class="flex items-center mt-3">
                                        <div class="flex items-center space-x-1 text-green-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                            </svg>
                                            <span class="text-sm font-semibold">+15%</span>
                                        </div>
                                        <span class="text-sm text-gray-500 ml-2">from last month</span>
                                    </div>
                                </div>
                                <div class="w-16 h-16 bg-gradient-to-br from-purple-500/20 to-purple-600/10 rounded-2xl flex items-center justify-center border border-purple-500/20 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts and Activity Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                        <!-- Lead Generation Chart -->
                        <div class="lg:col-span-2 bg-dark-card rounded-2xl shadow-2xl p-6 border border-dark-border">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h3 class="text-xl font-bold text-white">Lead Generation Trends</h3>
                                    <p class="text-gray-400 text-sm mt-1">Performance analytics over time</p>
                                </div>
                                <div class="flex space-x-2">
                                    <button class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-brand-red to-brand-dark-red rounded-xl shadow-lg shadow-brand-red/25 hover:shadow-brand-red/40 transition-all duration-200">7D</button>
                                    <button class="px-4 py-2 text-sm font-medium text-gray-400 hover:text-white hover:bg-dark-hover rounded-xl transition-all duration-200">30D</button>
                                    <button class="px-4 py-2 text-sm font-medium text-gray-400 hover:text-white hover:bg-dark-hover rounded-xl transition-all duration-200">90D</button>
                                </div>
                            </div>
                            <div class="h-80 bg-gradient-to-br from-dark-hover/50 to-transparent rounded-xl flex items-center justify-center border border-dark-border/50 relative overflow-hidden">
                                <!-- Background grid pattern -->
                                <div class="absolute inset-0 opacity-5">
                                    <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25px 25px, rgba(196, 15, 18, 0.3) 2px, transparent 0), radial-gradient(circle at 75px 75px, rgba(196, 15, 18, 0.2) 1px, transparent 0); background-size: 50px 50px;"></div>
                                </div>
                                <div class="text-center relative z-10">
                                    <div class="w-20 h-20 bg-gradient-to-br from-brand-red/20 to-brand-dark-red/10 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-brand-red/20">
                                        <svg class="w-10 h-10 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                    </div>
                                    <p class="text-gray-400 font-medium">Advanced Analytics Chart</p>
                                    <p class="text-gray-500 text-sm mt-2">Interactive visualization would be rendered here</p>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity Feed -->
                        <div class="bg-dark-card rounded-2xl shadow-2xl p-6 border border-dark-border">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-bold text-white">Live Activity</h3>
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                                    <span class="text-xs text-green-400 font-medium">LIVE</span>
                                </div>
                            </div>
                            <div class="space-y-6 max-h-80 overflow-y-auto scrollbar-thin scrollbar-thumb-dark-hover scrollbar-track-transparent">
                                <!-- Activity Item 1 -->
                                <div class="flex items-start space-x-4 group">
                                    <div class="relative">
                                        <div class="w-10 h-10 bg-gradient-to-br from-brand-red/20 to-brand-dark-red/10 rounded-xl flex items-center justify-center border border-brand-red/30 group-hover:scale-110 transition-transform duration-200">
                                            <div class="w-3 h-3 bg-brand-red rounded-full animate-pulse"></div>
                                        </div>
                                        <div class="absolute -bottom-2 -right-2 w-4 h-4 bg-green-400 rounded-full border-2 border-dark-card"></div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-white group-hover:text-brand-red transition-colors">New Lead Verified</p>
                                        <p class="text-xs text-gray-400 mt-1">John Smith - NIDA verification completed</p>
                                        <div class="flex items-center space-x-2 mt-2">
                                            <span class="text-xs text-gray-500">2 min ago</span>
                                            <div class="w-1 h-1 bg-gray-600 rounded-full"></div>
                                            <span class="text-xs text-green-400 font-medium">High Priority</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Activity Item 2 -->
                                <div class="flex items-start space-x-4 group">
                                    <div class="relative">
                                        <div class="w-10 h-10 bg-gradient-to-br from-green-500/20 to-green-600/10 rounded-xl flex items-center justify-center border border-green-500/30 group-hover:scale-110 transition-transform duration-200">
                                            <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-white group-hover:text-green-400 transition-colors">Lead Converted</p>
                                        <p class="text-xs text-gray-400 mt-1">Maria Johnson became a premium customer</p>
                                        <div class="flex items-center space-x-2 mt-2">
                                            <span class="text-xs text-gray-500">15 min ago</span>
                                            <div class="w-1 h-1 bg-gray-600 rounded-full"></div>
                                            <span class="text-xs text-blue-400 font-medium">Revenue: TSh 2.5M</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Activity Item 3 -->
                                <div class="flex items-start space-x-4 group">
                                    <div class="relative">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500/20 to-blue-600/10 rounded-xl flex items-center justify-center border border-blue-500/30 group-hover:scale-110 transition-transform duration-200">
                                            <div class="w-3 h-3 bg-blue-400 rounded-full"></div>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-white group-hover:text-blue-400 transition-colors">Report Generated</p>
                                        <p class="text-xs text-gray-400 mt-1">Weekly performance analytics report</p>
                                        <div class="flex items-center space-x-2 mt-2">
                                            <span class="text-xs text-gray-500">1 hour ago</span>
                                            <div class="w-1 h-1 bg-gray-600 rounded-full"></div>
                                            <button class="text-xs text-brand-red hover:text-brand-dark-red font-medium">Download</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Activity Item 4 -->
                                <div class="flex items-start space-x-4 group">
                                    <div class="relative">
                                        <div class="w-10 h-10 bg-gradient-to-br from-yellow-500/20 to-yellow-600/10 rounded-xl flex items-center justify-center border border-yellow-500/30 group-hover:scale-110 transition-transform duration-200">
                                            <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-white group-hover:text-yellow-400 transition-colors">System Update</p>
                                        <p class="text-xs text-gray-400 mt-1">NIDA verification system enhanced</p>
                                        <div class="flex items-center space-x-2 mt-2">
                                            <span class="text-xs text-gray-500">3 hours ago</span>
                                            <div class="w-1 h-1 bg-gray-600 rounded-full"></div>
                                            <span class="text-xs text-purple-400 font-medium">v2.1.4</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Activity Item 5 -->
                                <div class="flex items-start space-x-4 group">
                                    <div class="relative">
                                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500/20 to-purple-600/10 rounded-xl flex items-center justify-center border border-purple-500/30 group-hover:scale-110 transition-transform duration-200">
                                            <div class="w-3 h-3 bg-purple-400 rounded-full"></div>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-white group-hover:text-purple-400 transition-colors">Bulk Import Completed</p>
                                        <p class="text-xs text-gray-400 mt-1">142 new leads imported successfully</p>
                                        <div class="flex items-center space-x-2 mt-2">
                                            <span class="text-xs text-gray-500">6 hours ago</span>
                                            <div class="w-1 h-1 bg-gray-600 rounded-full"></div>
                                            <span class="text-xs text-green-400 font-medium">Success Rate: 98%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Leads Table -->
                    <div class="bg-dark-card rounded-2xl shadow-2xl border border-dark-border overflow-hidden">
                        <div class="px-6 py-4 border-b border-dark-border bg-gradient-to-r from-dark-card to-dark-hover">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-white">Recent Leads</h3>
                                    <p class="text-gray-400 text-sm mt-1">Latest lead acquisitions and status updates</p>
                                </div>
                                <button class="bg-gradient-to-r from-brand-red to-brand-dark-red text-white px-4 py-2 rounded-xl font-semibold hover:shadow-lg hover:shadow-brand-red/25 transition-all duration-200">
                                    View All Leads
                                </button>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gradient-to-r from-dark-hover to-dark-card">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">Contact</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">Communication</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">NIDA Status</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">Date Added</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">Score</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-dark-border">
                                    <tr class="hover:bg-dark-hover/50 transition-colors duration-200 group">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="relative">
                                                    <div class="w-12 h-12 bg-gradient-to-br from-brand-red via-brand-dark-red to-red-900 rounded-xl flex items-center justify-center shadow-lg">
                                                        <span class="text-white text-sm font-bold">JS</span>
                                                    </div>
                                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 rounded-full border-2 border-dark-card"></div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-semibold text-white group-hover:text-brand-red transition-colors">John Smith</div>
                                                    <div class="text-xs text-gray-400">Lead ID: #L001234</div>
                                                    <div class="text-xs text-gray-500 mt-1">Premium Prospect</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-300">john.smith@email.com</div>
                                            <div class="text-sm text-gray-400">+255 123 456 789</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-500/20 text-green-300 border border-green-500/30">
                                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Verified
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-300">May 26, 2025</div>
                                            <div class="text-xs text-gray-500">10:30 AM</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-1 bg-dark-hover rounded-full h-2 mr-2">
                                                    <div class="bg-gradient-to-r from-green-400 to-green-500 h-2 rounded-full" style="width: 85%"></div>
                                                </div>
                                                <span class="text-sm font-semibold text-green-400">85</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="flex items-center space-x-2">
                                                <button class="text-brand-red hover:text-brand-dark-red p-2 rounded-lg hover:bg-dark-hover transition-all duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </button>
                                                <button class="text-gray-400 hover:text-white p-2 rounded-lg hover:bg-dark-hover transition-all duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr class="hover:bg-dark-hover/50 transition-colors duration-200 group">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="relative">
                                                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 via-purple-600 to-purple-700 rounded-xl flex items-center justify-center shadow-lg">
                                                        <span class="text-white text-sm font-bold">MJ</span>
                                                    </div>
                                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-yellow-400 rounded-full border-2 border-dark-card"></div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-semibold text-white group-hover:text-purple-400 transition-colors">Maria Johnson</div>
                                                    <div class="text-xs text-gray-400">Lead ID: #L001235</div>
                                                    <div class="text-xs text-gray-500 mt-1">Standard Prospect</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-300">maria.johnson@email.com</div>
                                            <div class="text-sm text-gray-400">+255 987 654 321</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-500/20 text-yellow-300 border border-yellow-500/30">
                                                <svg class="w-3 h-3 mr-1.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Pending
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-300">May 25, 2025</div>
                                            <div class="text-xs text-gray-500">3:45 PM</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-1 bg-dark-hover rounded-full h-2 mr-2">
                                                    <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 h-2 rounded-full" style="width: 62%"></div>
                                                </div>
                                                <span class="text-sm font-semibold text-yellow-400">62</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="flex items-center space-x-2">
                                                <button class="text-brand-red hover:text-brand-dark-red p-2 rounded-lg hover:bg-dark-hover transition-all duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </button>
                                                <button class="text-gray-400 hover:text-white p-2 rounded-lg hover:bg-dark-hover transition-all duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr class="hover:bg-dark-hover/50 transition-colors duration-200 group">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="relative">
                                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 rounded-xl flex items-center justify-center shadow-lg">
                                                        <span class="text-white text-sm font-bold">AB</span>
                                                    </div>
                                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 rounded-full border-2 border-dark-card"></div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-semibold text-white group-hover:text-blue-400 transition-colors">Ahmed Bakari</div>
                                                    <div class="text-xs text-gray-400">Lead ID: #L001236</div>
                                                    <div class="text-xs text-gray-500 mt-1">Premium Prospect</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-300">ahmed.bakari@email.com</div>
                                            <div class="text-sm text-gray-400">+255 555 123 456</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-500/20 text-green-300 border border-green-500/30">
                                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Verified
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-300">May 24, 2025</div>
                                            <div class="text-xs text-gray-500">8:20 AM</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-1 bg-dark-hover rounded-full h-2 mr-2">
                                                    <div class="bg-gradient-to-r from-blue-400 to-blue-500 h-2 rounded-full" style="width: 78%"></div>
                                                </div>
                                                <span class="text-sm font-semibold text-blue-400">78</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="flex items-center space-x-2">
                                                <button class="text-brand-red hover:text-brand-dark-red p-2 rounded-lg hover:bg-dark-hover transition-all duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </button>
                                                <button class="text-gray-400 hover:text-white p-2 rounded-lg hover:bg-dark-hover transition-all duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const sidebar = document.getElementById('sidebar');

        mobileMenuButton.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });
    </script>
</body>
</html>