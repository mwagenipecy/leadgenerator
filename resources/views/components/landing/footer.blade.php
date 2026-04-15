<footer id="contact" class="bg-white text-gray-900 py-12 border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-4 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <div class="md:col-span-1">
                <a href="/" class="inline-block mb-4">
                    <img src="{{ asset('landing/redlogo.png') }}" alt="Fanikisha Market place Logo" class="h-10 w-auto">
                </a>
                <p class="text-gray-600 text-sm leading-relaxed mb-4">
                    {{ __('landing.connecting_borrowers') }}
                </p>
            </div>

            <div>
                <h4 class="font-semibold font-poppins text-lg mb-4 text-gray-900">{{ __('landing.quick_links') }}</h4>
                <ul class="space-y-2">
                    <li><a href="/" class="text-gray-600 hover:text-brand-green transition-colors text-sm">{{ __('landing.home') }}</a></li>
                    <li><a href="/#eligibility" class="text-gray-600 hover:text-brand-green transition-colors text-sm">{{ __('landing.eligibility') }}</a></li>
                    <li><a href="/#process" class="text-gray-600 hover:text-brand-green transition-colors text-sm">{{ __('landing.how_it_works') }}</a></li>
                    <li><a href="{{ route('customer-help.index') }}" class="text-gray-600 hover:text-brand-green transition-colors text-sm">{{ __('landing.customer_help') }}</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold font-poppins text-lg mb-4 text-gray-900">{{ __('landing.support') }}</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('customer-help.index') }}" class="text-gray-600 hover:text-brand-green transition-colors text-sm">{{ __('landing.help_center') }}</a></li>
                    <li><a href="{{ route('customer-help.index') }}" class="text-gray-600 hover:text-brand-green transition-colors text-sm">{{ __('landing.customer_help') }}</a></li>
                    <li><a href="{{ route('blog.index') }}" class="text-gray-600 hover:text-brand-green transition-colors text-sm">{{ __('landing.blog') }}</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold font-poppins text-lg mb-4 text-gray-900">{{ __('landing.contact') }}</h4>
                <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                        <span class="text-gray-600 text-sm">+255 123 456 789</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="text-gray-600 text-sm">info@leadgenerator.com</span>
                    </div>
                    <div class="flex items-start space-x-3">
                        <span class="text-gray-600 text-sm">Dar es Salaam, Tanzania</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-200 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="text-gray-500 text-sm mb-4 md:mb-0">
                    © 2025 Fanikisha Market place. All rights reserved.
                </div>
                <div class="flex flex-wrap gap-6">
                    <a href="#" class="text-gray-500 hover:text-brand-green text-sm transition-colors">Privacy Policy</a>
                    <a href="#" class="text-gray-500 hover:text-brand-green text-sm transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </div>
</footer>
