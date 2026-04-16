<footer id="contact" class="bg-white text-gray-900 py-16 border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-4 lg:px-8">
        {{-- Top Section: Brand, Company, Resources, Video --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 mb-12">
            {{-- Brand Column --}}
            <div class="lg:col-span-3">
                <a href="/" class="inline-block mb-5">
                    <img src="{{ asset('landing/redlogo.png') }}" alt="Fanikisha Marketplace Logo" class="h-12 w-auto">
                </a>
                <p class="text-gray-600 text-sm leading-relaxed max-w-xs">
                    {{ __('landing.connecting_borrowers') }}
                </p>
            </div>

            {{-- Company Column --}}
            <div class="lg:col-span-2">
                <h4 class="font-bold font-poppins text-base mb-5 text-gray-900">{{ __('landing.footer_company') }}</h4>
                <ul class="space-y-3">
                    <li><a href="#" class="text-gray-700 hover:text-brand-green transition-colors text-sm">{{ __('landing.footer_about_us') }}</a></li>
                    <li><a href="#" class="text-gray-700 hover:text-brand-green transition-colors text-sm">{{ __('landing.footer_careers') }}</a></li>
                    <li><a href="#" class="text-gray-700 hover:text-brand-green transition-colors text-sm">{{ __('landing.footer_press') }}</a></li>
                    <li><a href="#contact" class="text-gray-700 hover:text-brand-green transition-colors text-sm">{{ __('landing.contact') }}</a></li>
                </ul>
            </div>

            {{-- Resources Column --}}
            <div class="lg:col-span-3">
                <h4 class="font-bold font-poppins text-base mb-5 text-gray-900">{{ __('landing.footer_resources') }}</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('blog.index') }}" class="text-gray-700 hover:text-brand-green transition-colors text-sm">{{ __('landing.blog') }}</a></li>
                    <li><a href="#" class="text-gray-700 hover:text-brand-green transition-colors text-sm">{{ __('landing.footer_reports') }}</a></li>
                    <li><a href="#" class="text-gray-700 hover:text-brand-green transition-colors text-sm">{{ __('landing.footer_case_studies') }}</a></li>
                    <li><a href="#" class="text-gray-700 hover:text-brand-green transition-colors text-sm">{{ __('landing.footer_guides') }}</a></li>
                    <li><a href="#" class="text-gray-700 hover:text-brand-green transition-colors text-sm leading-snug block">{{ __('landing.footer_bot_laws') }}</a></li>
                </ul>
            </div>

            {{-- Video Column --}}
            <div class="lg:col-span-4">
                <div class="relative w-full aspect-video bg-black rounded-lg overflow-hidden group cursor-pointer" id="footer-video-wrapper">
                    {{-- Thumbnail with play button (shown initially) --}}
                    <div id="footer-video-thumbnail" class="absolute inset-0 bg-black flex items-center justify-center">
                        <img
                            src="https://img.youtube.com/vi/ZaUgKYPJX-c/maxresdefault.jpg"
                            alt="Video thumbnail"
                            class="w-full h-full object-cover opacity-70"
                            onerror="this.style.display='none'"
                        >
                        <button
                            type="button"
                            onclick="loadFooterVideo()"
                            class="absolute inset-0 flex items-center justify-center z-10"
                            aria-label="{{ __('landing.footer_play_video') }}"
                        >
                            <span class="flex items-center justify-center w-16 h-16 rounded-full bg-white/95 group-hover:bg-white shadow-lg transition-all group-hover:scale-110">
                                <svg class="w-7 h-7 text-gray-900 ml-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </span>
                        </button>
                    </div>
                    {{-- Iframe injected on click --}}
                    <div id="footer-video-iframe" class="absolute inset-0 hidden"></div>
                </div>
            </div>
        </div>

        {{-- Bottom Section: Support, For Borrowers, Legal, Contact --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 mb-10">
            {{-- Support Column --}}
            <div class="lg:col-span-3">
                <h4 class="font-bold font-poppins text-base mb-5 text-gray-900">{{ __('landing.support') }}</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('customer-help.index') }}" class="text-gray-700 hover:text-brand-green transition-colors text-sm">{{ __('landing.help_center') }}</a></li>
                    <li><a href="{{ route('customer-help.index') }}" class="text-gray-700 hover:text-brand-green transition-colors text-sm">{{ __('landing.customer_help') }}</a></li>
                    <li><a href="{{ route('blog.index') }}" class="text-gray-700 hover:text-brand-green transition-colors text-sm">{{ __('landing.blog') }}</a></li>
                </ul>
            </div>

            {{-- For Borrowers Column --}}
            <div class="lg:col-span-2">
                <h4 class="font-bold font-poppins text-base mb-5 text-gray-900">{{ __('landing.footer_for_borrowers') }}</h4>
                <ul class="space-y-3">
                    <li><a href="/#eligibility" class="text-gray-700 hover:text-brand-green transition-colors text-sm">{{ __('landing.eligibility') }}</a></li>
                    <li><a href="#" class="text-gray-700 hover:text-brand-green transition-colors text-sm">{{ __('landing.footer_registration') }}</a></li>
                </ul>
            </div>

            {{-- Legal & Policies Column --}}
            <div class="lg:col-span-3">
                <h4 class="font-bold font-poppins text-base mb-5 text-gray-900">{{ __('landing.footer_legal_policies') }}</h4>
                <ul class="space-y-3">
                    <li><a href="#" class="text-gray-700 hover:text-brand-green transition-colors text-sm">{{ __('landing.footer_cookie_policy') }}</a></li>
                    <li><a href="#" class="text-gray-700 hover:text-brand-green transition-colors text-sm">{{ __('landing.footer_customer_protection') }}</a></li>
                    <li><a href="#" class="text-gray-700 hover:text-brand-green transition-colors text-sm">{{ __('landing.footer_customer_support') }}</a></li>
                    <li><a href="#" class="text-gray-700 hover:text-brand-green transition-colors text-sm">{{ __('landing.footer_charges_tariffs') }}</a></li>
                    <li><a href="#" class="text-gray-700 hover:text-brand-green transition-colors text-sm">{{ __('landing.footer_equal_opportunity') }}</a></li>
                    <li><a href="#" class="text-gray-700 hover:text-brand-green transition-colors text-sm">{{ __('landing.footer_privacy_policy') }}</a></li>
                </ul>
            </div>

            {{-- Contact Column --}}
            <div class="lg:col-span-4">
                <h4 class="font-bold font-poppins text-base mb-5 text-gray-900">{{ __('landing.contact') }}</h4>
                <div class="space-y-3">
                    <div>
                        <a href="tel:+255123456789" class="text-gray-700 hover:text-brand-green transition-colors text-sm">+255 123 456 789</a>
                    </div>
                    <div>
                        <a href="mailto:info@leadgenerator.com" class="text-gray-700 hover:text-brand-green transition-colors text-sm">info@leadgenerator.com</a>
                    </div>
                    <div>
                        <span class="text-gray-700 text-sm">Dar es Salaam, Tanzania</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="border-t border-gray-200 pt-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-3">
                <div class="text-gray-500 text-sm">
                    &copy; {{ date('Y') }} {{ __('landing.footer_copyright') }}
                </div>
                <div>
                    <a href="#" class="text-gray-500 hover:text-brand-green text-sm transition-colors">{{ __('landing.footer_terms_of_service') }}</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Floating Customer Help Button --}}
    <a href="{{ route('customer-help.index') }}"
       class="fixed bottom-6 right-6 z-40 inline-flex items-center gap-2 bg-brand-red hover:bg-red-700 text-white px-5 py-3 rounded-full shadow-lg transition-all hover:scale-105"
       style="background-color: #B63B2E;">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
        <span class="text-sm font-medium">{{ __('landing.customer_help') }}</span>
    </a>



<script>
    function loadFooterVideo() {
        const videoId = 'ZaUgKYPJX-c';
        const thumbnail = document.getElementById('footer-video-thumbnail');
        const iframeContainer = document.getElementById('footer-video-iframe');

        iframeContainer.innerHTML = `
            <iframe
                class="w-full h-full"
                src="https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0"
                title="{{ __('landing.footer_youtube_player') }}"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen>
            </iframe>
        `;
        thumbnail.classList.add('hidden');
        iframeContainer.classList.remove('hidden');
    }
</script>


</footer>
