<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lead Generator - Loans for Everyone</title>
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
<body class="bg-white text-gray-900 font-inter">
    <!-- Navigation -->
    <nav class="bg-white py-4">
        <div class="max-w-6xl mx-auto px-4 flex justify-between items-center">
            <div class="text-xl font-semibold text-black font-poppins">
                Lead<span class="text-brand-red">Generator</span>
            </div>
            <div class="flex items-center space-x-6">
                <a href="#about" class="text-gray-600 hover:text-gray-900">About</a>
                <a href="#eligibility" class="text-gray-600 hover:text-gray-900">Eligibility</a>
                <button class="bg-brand-red text-white px-4 py-2 rounded text-sm hover:bg-red-700 transition-colors">
                    Get Started
                </button>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-black to-gray-800 text-white">
        <!-- Diagonal Background Elements -->
        <div class="absolute inset-0">
            <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-brand-red/10 to-transparent transform skew-x-12 origin-top-right"></div>
            <div class="absolute bottom-0 left-0 w-2/3 h-3/4 bg-gradient-to-t from-gray-800/30 to-transparent transform -skew-x-12 origin-bottom-left"></div>
        </div>
        
        <!-- Content -->
        <div class="relative z-10 max-w-6xl mx-auto px-4 py-24 md:py-32">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <div class="mb-6">
                        <span class="inline-block bg-brand-red/20 text-brand-red px-4 py-2 rounded-full text-sm font-medium">
                            🚀 Now Available
                        </span>
                    </div>
                    <h1 class="text-4xl md:text-6xl font-bold font-poppins leading-tight mb-8">
                        Loans for <span class="text-brand-red">Everyone</span><br>
                        <span class="text-2xl md:text-3xl text-gray-300 font-light">Employed or Not</span>
                    </h1>
                    <p class="text-lg md:text-xl text-gray-300 mb-10 leading-relaxed">
                        Whether you're employed, self-employed, or between jobs, our verified lenders 
                        are ready to help you achieve your financial goals.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button class="bg-brand-red text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-red-700 transition-all duration-300 transform hover:scale-105">
                            Apply Now
                        </button>
                        <button class="border-2 border-white/30 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white/10 transition-all duration-300">
                            Learn More
                        </button>
                    </div>
                </div>
                
                <!-- Right Side Visual Element -->
                <div class="relative">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20">
                        <div class="text-center mb-8">
                            <div class="w-20 h-20 bg-brand-red rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-semibold font-poppins mb-2">Quick Application</h3>
                            <p class="text-gray-300">Get started in minutes</p>
                        </div>
                        <div class="space-y-4 text-sm">
                            <div class="flex items-center justify-between bg-white/5 rounded-lg p-3">
                                <span class="text-gray-300">✓ NIDA Verification</span>
                                <span class="text-green-400">Secure</span>
                            </div>
                            <div class="flex items-center justify-between bg-white/5 rounded-lg p-3">
                                <span class="text-gray-300">✓ Smart Matching</span>
                                <span class="text-green-400">AI-Powered</span>
                            </div>
                            <div class="flex items-center justify-between bg-white/5 rounded-lg p-3">
                                <span class="text-gray-300">✓ Fast Approval</span>
                                <span class="text-green-400">Same Day</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bottom Slanted Element -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1200 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-16 md:h-24">
                <path d="M0 120L1200 120L1200 80L0 20Z" fill="white"/>
            </svg>
        </div>
    </section>

    <!-- Who Can Apply Section -->
    <section id="eligibility" class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-semibold text-black font-poppins mb-4">Who Can Apply?</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    We believe everyone deserves access to financial opportunities
                </p>
            </div>
            <div class="grid md:grid-cols-3 gap-12">
                <div class="text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-black mb-3 font-poppins">Employed Individuals</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Salary workers, professionals, and those with steady employment seeking additional funding
                    </p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-black mb-3 font-poppins">Self-Employed</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Entrepreneurs, freelancers, and business owners looking to grow or manage cash flow
                    </p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-1a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-black mb-3 font-poppins">Non-Employed</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Students, retirees, or those between jobs with alternative income or collateral
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-light text-black mb-4">Simple Process</h2>
                <p class="text-gray-600">Get matched with the right lender in minutes</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-lg border border-gray-100">
                    <div class="w-8 h-8 bg-brand-red text-white rounded-full flex items-center justify-center text-sm font-medium mb-6">1</div>
                    <h3 class="text-lg font-medium text-black mb-3">Register & Verify</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Create your account and verify your identity with NIDA for secure processing
                    </p>
                </div>
                <div class="bg-white p-8 rounded-lg border border-gray-100">
                    <div class="w-8 h-8 bg-brand-red text-white rounded-full flex items-center justify-center text-sm font-medium mb-6">2</div>
                    <h3 class="text-lg font-medium text-black mb-3">Submit Application</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Tell us about your needs and financial situation through our simple form
                    </p>
                </div>
                <div class="bg-white p-8 rounded-lg border border-gray-100">
                    <div class="w-8 h-8 bg-brand-red text-white rounded-full flex items-center justify-center text-sm font-medium mb-6">3</div>
                    <h3 class="text-lg font-medium text-black mb-3">Get Connected</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        We match you with suitable lenders and handle the connection process
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section id="about" class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div>
                    <h2 class="text-3xl font-light text-black mb-6">
                        Your Financial Partner
                    </h2>
                    <p class="text-gray-600 mb-8 leading-relaxed">
                        We understand that traditional banking can leave many people behind. 
                        That's why we work with diverse lenders who evaluate applications 
                        based on your unique circumstances, not just employment status.
                    </p>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-brand-red mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700 text-sm">No employment requirement</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-brand-red mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700 text-sm">NIDA verified security</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-brand-red mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700 text-sm">Multiple lender options</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-brand-red mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700 text-sm">Fast approval process</span>
                        </li>
                    </ul>
                </div>
                <div class="bg-gray-50 p-12 rounded-lg">
                    <div class="text-center">
                        <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
                            <svg class="w-10 h-10 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-medium text-black mb-3">Trusted Platform</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Join thousands who have successfully connected with lenders 
                            through our secure, verified platform
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-black">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-light text-white mb-6">
                Ready to Get Started?
            </h2>
            <p class="text-gray-300 mb-10 text-lg leading-relaxed">
                Don't let employment status limit your financial opportunities. 
                Connect with lenders who understand your unique situation.
            </p>
            <button class="bg-brand-red text-white px-10 py-4 rounded-md text-lg hover:bg-red-700 transition-colors">
                Apply Now
            </button>
            <p class="text-gray-500 text-sm mt-6">
                Secure • Fast • No hidden fees
            </p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white py-12 border-t border-gray-100">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="text-xl font-semibold text-black mb-4 md:mb-0 font-poppins">
                    Lead<span class="text-brand-red">Generator</span>
                </div>
                <div class="flex space-x-8 text-sm text-gray-600">
                    <a href="#" class="hover:text-gray-900">Privacy</a>
                    <a href="#" class="hover:text-gray-900">Terms</a>
                    <a href="#" class="hover:text-gray-900">Support</a>
                </div>
            </div>
            <div class="border-t border-gray-100 mt-8 pt-8 text-center">
                <p class="text-gray-500 text-sm">
                    © 2025 LeadGenerator. Connecting borrowers with lenders.
                </p>
            </div>
        </div>
    </section>

    <script>
        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>