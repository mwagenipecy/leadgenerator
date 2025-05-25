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
</head>
<body class="bg-accent-gray font-inter">
    <div class="flex h-screen overflow-hidden">
        <!-- Black Sidebar -->
        <div id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-sidebar-black shadow-2xl transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0">
            <!-- Logo Section -->
            <div class="flex items-center justify-center h-20 px-6 bg-gradient-to-r from-brand-red via-brand-dark-red to-red-900 relative overflow-hidden">
                <!-- Animated glow effect -->
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent animate-pulse"></div>
                <div class="flex items-center space-x-4 relative z-10">
                    <div class="w-12 h-12 bg-white/15 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/20 shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold font-poppins text-white">
                            Lead<span class="text-red-200">Generator</span>
                        </h1>
                        <p class="text-red-200 text-xs font-medium mt-0.5">Advanced Analytics</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="mt-8 px-4 pb-4">
                <div class="space-y-3">
                    <!-- Dashboard - Active -->
                    <a href="#" class="group flex items-center px-5 py-4 text-sm font-semibold rounded-2xl bg-gradient-to-r from-brand-red/90 to-brand-dark-red text-white shadow-lg shadow-brand-red/20 transform transition-all duration-200 hover:scale-105">
                        <svg class="mr-4 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"/>
                        </svg>
                        Dashboard
                        <div class="ml-auto w-2 h-2 bg-white rounded-full animate-pulse"></div>
                    </a>

                    <!-- Leads -->
                    <a href="#" class="group flex items-center px-5 py-4 text-sm font-medium text-gray-300 rounded-2xl hover:bg-sidebar-gray hover:text-white transition-all duration-200 hover:shadow-lg">
                        <svg class="mr-4 h-5 w-5 group-hover:text-brand-red transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Lead Management
                        <span class="ml-auto bg-brand-red text-white text-xs px-2.5 py-1.5 rounded-full font-bold shadow-md">24</span>
                    </a>

                    <!-- Analytics -->
                    <a href="#" class="group flex items-center px-5 py-4 text-sm font-medium text-gray-300 rounded-2xl hover:bg-sidebar-gray hover:text-white transition-all duration-200 hover:shadow-lg">
                        <svg class="mr-4 h-5 w-5 group-hover:text-brand-red transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Analytics & Reports
                    </a>

                    <!-- NIDA Verification -->
                    <a href="#" class="group flex items-center px-5 py-4 text-sm font-medium text-gray-300 rounded-2xl hover:bg-sidebar-gray hover:text-white transition-all duration-200 hover:shadow-lg">
                        <svg class="mr-4 h-5 w-5 group-hover:text-brand-red transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        NIDA Verification
                        <div class="ml-auto">
                            <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                        </div>
                    </a>

                    <!-- Campaign Management -->
                    <a href="#" class="group flex items-center px-5 py-4 text-sm font-medium text-gray-300 rounded-2xl hover:bg-sidebar-gray hover:text-white transition-all duration-200 hover:shadow-lg">
                        <svg class="mr-4 h-5 w-5 group-hover:text-brand-red transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        Campaigns
                    </a>

                    <!-- Integration -->
                    <a href="#" class="group flex items-center px-5 py-4 text-sm font-medium text-gray-300 rounded-2xl hover:bg-sidebar-gray hover:text-white transition-all duration-200 hover:shadow-lg">
                        <svg class="mr-4 h-5 w-5 group-hover:text-brand-red transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Integrations
                    </a>

                    <!-- Settings -->
                    <a href="#" class="group flex items-center px-5 py-4 text-sm font-medium text-gray-300 rounded-2xl hover:bg-sidebar-gray hover:text-white transition-all duration-200 hover:shadow-lg">
                        <svg class="mr-4 h-5 w-5 group-hover:text-brand-red transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Settings
                    </a>
                </div>

                <!-- Divider -->
                <div class="my-8 border-t border-gray-700"></div>

                <!-- Support Section -->
                <div class="bg-gradient-to-br from-brand-red/10 via-brand-dark-red/5 to-transparent rounded-2xl p-5 border border-brand-red/20">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-brand-red/20 rounded-xl flex items-center justify-center border border-brand-red/30">
                            <svg class="w-6 h-6 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 3a9 9 0 110 18 9 9 0 010-18z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-white mb-1">Need Support?</p>
                            <p class="text-xs text-gray-400 mb-3">Our team is here to help you 24/7</p>
                            <button class="bg-gradient-to-r from-brand-red to-brand-dark-red text-white text-xs font-semibold py-2 px-4 rounded-lg hover:shadow-lg hover:shadow-brand-red/25 transition-all duration-200 w-full">
                                Get Help Now
                            </button>
                        </div>
                    </div>
                </div>

                <!-- User Profile Section -->
                <div class="mt-6 px-2">
                    <div class="bg-sidebar-gray rounded-2xl p-4 hover:bg-gray-800 transition-colors duration-200 cursor-pointer">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-brand-red to-brand-dark-red rounded-xl flex items-center justify-center shadow-lg">
                                <span class="text-white text-sm font-bold">JD</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-white truncate">John Doe</p>
                                <p class="text-xs text-gray-400">Administrator</p>
                            </div>
                            <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 overflow-hidden lg:ml-0">
            <!-- Top Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-8 py-4">
                    <!-- Mobile menu button -->
                    <button id="mobile-menu-button" class="lg:hidden p-2 rounded-xl text-gray-600 hover:text-brand-red hover:bg-gray-100 transition-all duration-200">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <!-- Search Bar -->
                    <div class="flex-1 max-w-2xl mx-6">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" class="block w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red placeholder-gray-500 text-gray-900 text-sm transition-all duration-200" placeholder="Search leads, campaigns, or reports...">
                        </div>
                    </div>

                    <!-- Header Actions -->
                    <div class="flex items-center space-x-4">
                        <!-- Quick Actions -->
                        <button class="bg-brand-red text-white px-4 py-2 rounded-xl font-semibold hover:bg-brand-dark-red transition-all duration-200 shadow-lg shadow-brand-red/25 hover:shadow-brand-red/40">
                            + Add Lead
                        </button>

                        <!-- Notifications -->
                        <button class="relative p-3 text-gray-600 hover:text-brand-red hover:bg-gray-100 rounded-xl transition-all duration-200">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span class="absolute -top-1 -right-1 h-5 w-5 bg-brand-red rounded-full flex items-center justify-center">
                                <span class="text-white text-xs font-bold">3</span>
                            </span>
                        </button>

                        <!-- Profile Menu -->
                        <div class="relative">
                            <button class="flex items-center space-x-3 p-2 rounded-xl hover:bg-gray-100 transition-all duration-200 group">
                                <div class="w-10 h-10 bg-gradient-to-br from-brand-red to-brand-dark-red rounded-xl flex items-center justify-center shadow-md">
                                    <span class="text-white text-sm font-bold">JD</span>
                                </div>
                                <div class="hidden md:block text-left">
                                    <p class="text-sm font-semibold text-gray-900 group-hover:text-brand-red transition-colors">John Doe</p>
                                    <p class="text-xs text-gray-500">Admin</p>
                                </div>
                                <svg class="h-4 w-4 text-gray-500 group-hover:text-brand-red transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <main class="flex-1 overflow-y-auto bg-accent-gray">
                <div class="p-8">
                    <!-- Page Header -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="text-4xl font-bold text-gray-900 mb-2">Dashboard Overview</h1>
                                <p class="text-gray-600 text-lg">Monitor your lead generation performance and key metrics</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center space-x-2 bg-green-50 px-4 py-2 rounded-full">
                                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                                    <span class="text-sm font-medium text-green-700">All Systems Operational</span>
                                </div>
                                <button class="bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-xl font-medium hover:bg-gray-50 transition-colors">
                                    Last 30 days
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Key Performance Metrics -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <!-- Total Leads Card -->
                        <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300 group hover:border-brand-red/20">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-14 h-14 bg-brand-red/10 rounded-2xl flex items-center justify-center group-hover:bg-brand-red/20 transition-colors">
                                    <svg class="w-7 h-7 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-500">Total Leads</p>
                                    <p class="text-3xl font-bold text-gray-900 group-hover:text-brand-red transition-colors">1,249</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-1 text-green-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                    <span class="text-sm font-semibold">+12.5%</span>
                                </div>
                                <span class="text-sm text-gray-500">vs last month</span>
                            </div>
                        </div>

                        <!-- NIDA Verified Card -->
                        <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300 group hover:border-green-500/20">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center group-hover:bg-green-200 transition-colors">
                                    <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-500">NIDA Verified</p>
                                    <p class="text-3xl font-bold text-gray-900 group-hover:text-green-600 transition-colors">987</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-1 text-green-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="text-sm font-semibold">79.1%</span>
                                </div>
                                <span class="text-sm text-gray-500">success rate</span>
                            </div>
                        </div>

                        <!-- Conversion Rate Card -->
                        <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300 group hover:border-blue-500/20">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-500">Conversion Rate</p>
                                    <p class="text-3xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">23.7%</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-1 text-blue-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                    <span class="text-sm font-semibold">+3.2%</span>
                                </div>
                                <span class="text-sm text-gray-500">this week</span>
                            </div>
                        </div>

                        <!-- Revenue Card -->
                        <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-300 group hover:border-purple-500/20">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                                    <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                                    <p class="text-3xl font-bold text-gray-900 group-hover:text-purple-600 transition-colors">TSh 45.2M</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-1 text-green-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                    <span class="text-sm font-semibold">+15.3%</span>
                                </div>
                                <span class="text-sm text-gray-500">vs last month</span>
                            </div>
                        </div>
                    </div>

                    <!-- Charts and Activity Section -->
                    <div class="grid grid-cols-1 lg:grid-cols-7 gap-6 mb-8">
                        <!-- Lead Generation Chart -->
                        <div class="lg:col-span-5 bg-white rounded-3xl shadow-sm p-8 border border-gray-100">
                            <div class="flex items-center justify-between mb-8">
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Lead Generation Analytics</h3>
                                    <p class="text-gray-600">Track your lead acquisition performance over time</p>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <button class="bg-brand-red text-white px-4 py-2 rounded-xl font-semibold text-sm shadow-lg shadow-brand-red/25">7 Days</button>
                                    <button class="bg-gray-100 text-gray-600 px-4 py-2 rounded-xl font-medium text-sm hover:bg-gray-200 transition-colors">30 Days</button>
                                    <button class="bg-gray-100 text-gray-600 px-4 py-2 rounded-xl font-medium text-sm hover:bg-gray-200 transition-colors">90 Days</button>
                                </div>
                            </div>
                            <div class="h-80 bg-gradient-to-br from-gray-50 to-white rounded-2xl flex items-center justify-center border border-gray-100 relative overflow-hidden">
                                <!-- Background Pattern -->
                                <div class="absolute inset-0 opacity-5">
                                    <div class="absolute inset-0" style="background-image: radial-gradient(circle at 20px 20px, #C40F12 2px, transparent 0), radial-gradient(circle at 60px 60px, #C40F12 1px, transparent 0); background-size: 40px 40px;"></div>
                                </div>
                                <div class="text-center relative z-10">
                                    <div class="w-20 h-20 bg-brand-red/10 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-brand-red/20">
                                        <svg class="w-10 h-10 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-bold text-gray-900 mb-2">Interactive Analytics Chart</h4>
                                    <p class="text-gray-500">Advanced visualization would render here with real-time data</p>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity Feed -->
                        <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm p-6 border border-gray-100">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-bold text-gray-900">Live Activity</h3>
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                                    <span class="text-xs font-semibold text-green-600">LIVE</span>
                                </div>
                            </div>
                            <div class="space-y-4 max-h-80 overflow-y-auto">
                                <!-- Activity Item 1 -->
                                <div class="flex items-start space-x-3 p-3 rounded-2xl hover:bg-gray-50 transition-colors group">
                                    <div class="w-10 h-10 bg-brand-red/10 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-brand-red/20 transition-colors">
                                        <div class="w-3 h-3 bg-brand-red rounded-full animate-pulse"></div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900">New Lead Verified</p>
                                        <p class="text-xs text-gray-500 mt-1">John Smith completed NIDA verification</p>
                                        <p class="text-xs text-gray-400 mt-2">2 minutes ago</p>
                                    </div>
                                </div>

                                <!-- Activity Item 2 -->
                                <div class="flex items-start space-x-3 p-3 rounded-2xl hover:bg-gray-50 transition-colors group">
                                    <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-green-200 transition-colors">
                                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900">Lead Converted</p>
                                        <p class="text-xs text-gray-500 mt-1">Maria Johnson became customer</p>
                                        <p class="text-xs text-gray-400 mt-2">15 minutes ago</p>
                                    </div>
                                </div>

                                <!-- Activity Item 3 -->
                                <div class="flex items-start space-x-3 p-3 rounded-2xl hover:bg-gray-50 transition-colors group">
                                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-blue-200 transition-colors">
                                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900">Report Generated</p>
                                        <p class="text-xs text-gray-500 mt-1">Weekly analytics report</p>
                                        <p class="text-xs text-gray-400 mt-2">1 hour ago</p>
                                    </div>
                                </div>

                                <!-- Activity Item 4 -->
                                <div class="flex items-start space-x-3 p-3 rounded-2xl hover:bg-gray-50 transition-colors group">
                                    <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-yellow-200 transition-colors">
                                        <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900">System Update</p>
                                        <p class="text-xs text-gray-500 mt-1">NIDA integration enhanced</p>
                                        <p class="text-xs text-gray-400 mt-2">3 hours ago</p>
                                    </div>
                                </div>

                                <!-- Activity Item 5 -->
                                <div class="flex items-start space-x-3 p-3 rounded-2xl hover:bg-gray-50 transition-colors group">
                                    <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-purple-200 transition-colors">
                                        <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900">Bulk Import</p>
                                        <p class="text-xs text-gray-500 mt-1">142 leads imported successfully</p>
                                        <p class="text-xs text-gray-400 mt-2">6 hours ago</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Leads Table -->
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-white to-gray-50">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900 mb-1">Recent Leads</h3>
                                    <p class="text-gray-600">Latest lead acquisitions and verification status</p>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <button class="bg-gray-100 text-gray-600 px-4 py-2 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                                        Filter
                                    </button>
                                    <button class="bg-brand-red text-white px-6 py-2 rounded-xl font-semibold hover:bg-brand-dark-red transition-all duration-200 shadow-lg shadow-brand-red/25">
                                        View All Leads
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-8 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lead Information</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Contact Details</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">NIDA Status</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Added Date</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lead Score</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <!-- Lead Row 1 -->
                                    <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                                        <td class="px-8 py-6 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="relative">
                                                    <div class="w-12 h-12 bg-gradient-to-br from-brand-red to-brand-dark-red rounded-2xl flex items-center justify-center shadow-md">
                                                        <span class="text-white text-sm font-bold">JS</span>
                                                    </div>
                                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 rounded-full border-2 border-white"></div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-bold text-gray-900 group-hover:text-brand-red transition-colors">John Smith</div>
                                                    <div class="text-xs text-gray-500">Lead ID: #L001234</div>
                                                    <div class="text-xs text-blue-600 font-medium mt-1">Premium Prospect</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-6 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">john.smith@email.com</div>
                                            <div class="text-sm text-gray-500">+255 123 456 789</div>
                                        </td>
                                        <td class="px-6 py-6 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Verified
                                            </span>
                                        </td>
                                        <td class="px-6 py-6 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">May 26, 2025</div>
                                            <div class="text-xs text-gray-500">10:30 AM</div>
                                        </td>
                                        <td class="px-6 py-6 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-1 bg-gray-200 rounded-full h-2 mr-3 max-w-16">
                                                    <div class="bg-gradient-to-r from-green-400 to-green-500 h-2 rounded-full" style="width: 85%"></div>
                                                </div>
                                                <span class="text-sm font-bold text-green-600">85</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-6 whitespace-nowrap text-right">
                                            <div class="flex items-center space-x-2">
                                                <button class="text-brand-red hover:text-brand-dark-red p-2 rounded-xl hover:bg-brand-red/10 transition-all duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </button>
                                                <button class="text-gray-400 hover:text-gray-600 p-2 rounded-xl hover:bg-gray-100 transition-all duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Lead Row 2 -->
                                    <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                                        <td class="px-8 py-6 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="relative">
                                                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-md">
                                                        <span class="text-white text-sm font-bold">MJ</span>
                                                    </div>
                                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-yellow-400 rounded-full border-2 border-white"></div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-bold text-gray-900 group-hover:text-purple-600 transition-colors">Maria Johnson</div>
                                                    <div class="text-xs text-gray-500">Lead ID: #L001235</div>
                                                    <div class="text-xs text-gray-600 font-medium mt-1">Standard Prospect</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-6 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">maria.johnson@email.com</div>
                                            <div class="text-sm text-gray-500">+255 987 654 321</div>
                                        </td>
                                        <td class="px-6 py-6 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                <svg class="w-3 h-3 mr-1.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Pending
                                            </span>
                                        </td>
                                        <td class="px-6 py-6 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">May 25, 2025</div>
                                            <div class="text-xs text-gray-500">3:45 PM</div>
                                        </td>
                                        <td class="px-6 py-6 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-1 bg-gray-200 rounded-full h-2 mr-3 max-w-16">
                                                    <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 h-2 rounded-full" style="width: 62%"></div>
                                                </div>
                                                <span class="text-sm font-bold text-yellow-600">62</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-6 whitespace-nowrap text-right">
                                            <div class="flex items-center space-x-2">
                                                <button class="text-brand-red hover:text-brand-dark-red p-2 rounded-xl hover:bg-brand-red/10 transition-all duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </button>
                                                <button class="text-gray-400 hover:text-gray-600 p-2 rounded-xl hover:bg-gray-100 transition-all duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Lead Row 3 -->
                                    <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                                        <td class="px-8 py-6 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="relative">
                                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-md">
                                                        <span class="text-white text-sm font-bold">AB</span>
                                                    </div>
                                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 rounded-full border-2 border-white"></div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-bold text-gray-900 group-hover:text-blue-600 transition-colors">Ahmed Bakari</div>
                                                    <div class="text-xs text-gray-500">Lead ID: #L001236</div>
                                                    <div class="text-xs text-blue-600 font-medium mt-1">Premium Prospect</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-6 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">ahmed.bakari@email.com</div>
                                            <div class="text-sm text-gray-500">+255 555 123 456</div>
                                        </td>
                                        <td class="px-6 py-6 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Verified
                                            </span>
                                        </td>
                                        <td class="px-6 py-6 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">May 24, 2025</div>
                                            <div class="text-xs text-gray-500">8:20 AM</div>
                                        </td>
                                        <td class="px-6 py-6 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-1 bg-gray-200 rounded-full h-2 mr-3 max-w-16">
                                                    <div class="bg-gradient-to-r from-blue-400 to-blue-500 h-2 rounded-full" style="width: 78%"></div>
                                                </div>
                                                <span class="text-sm font-bold text-blue-600">78</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-6 whitespace-nowrap text-right">
                                            <div class="flex items-center space-x-2">
                                                <button class="text-brand-red hover:text-brand-dark-red p-2 rounded-xl hover:bg-brand-red/10 transition-all duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </button>
                                                <button class="text-gray-400 hover:text-gray-600 p-2 rounded-xl hover:bg-gray-100 transition-all duration-200">
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

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>

    <!-- JavaScript -->
    <script>
        // Mobile menu functionality
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        mobileMenuButton.addEventListener('click', function() {
            sidebar.classList.toggle('-translate-x-full');
            sidebarOverlay.classList.toggle('hidden');
        });

        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        });

        // Auto-hide mobile menu on window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
            }
        });

        // Enhanced hover effects for metric cards
        document.querySelectorAll('.group').forEach(card => {
            card.addEventListener('mouseenter', function() {
                if (this.classList.contains('hover:shadow-md')) {
                    this.style.transform = 'translateY(-4px)';
                }
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

        // Add smooth scrolling to activity feed
        const activityFeed = document.querySelector('.overflow-y-auto');
        if (activityFeed) {
            activityFeed.style.scrollBehavior = 'smooth';
        }

        // Initialize tooltips and interactive elements
        document.addEventListener('DOMContentLoaded', function() {
            // Add loading states to buttons
            document.querySelectorAll('button').forEach(button => {
                button.addEventListener('click', function(e) {
                    if (this.textContent.includes('Add Lead') || this.textContent.includes('View All')) {
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

            // Add search functionality
            const searchInput = document.querySelector('input[type="text"]');
            if (searchInput) {
                searchInput.addEventListener('focus', function() {
                    this.parentElement.classList.add('ring-2', 'ring-brand-red/50');
                });
                
                searchInput.addEventListener('blur', function() {
                    this.parentElement.classList.remove('ring-2', 'ring-brand-red/50');
                });
            }
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
    </script>

    <style>
        /* Custom scrollbar styling */
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
    </style>
</body>
</html>