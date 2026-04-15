<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('landing.customer_help') }} - Fanikisha</title>
    <link rel="icon" type="image/png" href="{{ asset('landing/applicationIcon.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
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
        .floating-navbar-shell #language-switcher-button {
            color: #C40F11;
        }

        .floating-navbar-shell.navbar-scrolled .nav-scroll-link,
        .floating-navbar-shell.navbar-scrolled #language-switcher-button {
            color: #C40F11;
        }
    </style>
</head>
<body class="bg-white text-gray-900 font-inter overflow-x-hidden">
    <x-landing.navbar :is-home="false" />

    <main class="pt-24 pb-12">
        <section class="relative overflow-hidden min-h-[520px] bg-gradient-to-br from-[#fff8f8] via-white to-white px-5 py-12">
            <div class="pointer-events-none absolute -top-16 -right-16 h-[220px] w-[220px] rounded-full bg-[radial-gradient(circle,_#fde8e8_0%,_transparent_70%)]"></div>
            <div class="pointer-events-none absolute -bottom-10 -left-10 h-[160px] w-[160px] rounded-full bg-[radial-gradient(circle,_#fde8e8_0%,_transparent_70%)]"></div>

            <div class="relative z-10 mx-auto flex max-w-3xl flex-col items-center">
                <div class="mb-5 flex h-[52px] w-[52px] items-center justify-center rounded-[14px] bg-[#C62828] text-white shadow-[0_4px_16px_#c6282833]">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2l2.4 7.4H22l-6.2 4.5 2.4 7.4L12 17l-6.2 4.3 2.4-7.4L2 9.4h7.6z" />
                    </svg>
                </div>

                <h1 class="mb-2 text-center text-[26px] font-semibold text-[#111]">{{ __('landing.help_heading') }}</h1>
                <p class="mb-7 text-center text-sm text-[#777]">
                    {{ __('landing.help_subtitle') }}
                    <a href="{{ route('blog.index') }}" class="font-medium text-[#C62828] border-b border-[#fccaca]">{{ __('landing.view_quick_start') }}</a>
                </p>

                @if (session()->has('help_success'))
                    <div class="mb-4 w-full max-w-[600px] rounded-lg border border-green-300 bg-green-100 px-4 py-3 text-sm text-green-800">
                        {{ session('help_success') }}
                    </div>
                @endif

                <div class="mb-4 w-full max-w-[760px] rounded-2xl border border-[#E8E8E8] bg-white p-4 shadow-[0_2px_20px_rgba(0,0,0,0.06)]">
                    <p class="mb-2.5 text-[11px] font-medium uppercase tracking-[0.06em] text-[#aaa]">{{ __('landing.chat_preview') }}</p>
                    <div id="chat-messages" class="max-h-[360px] overflow-y-auto space-y-2 rounded-lg border border-[#f0f0f0] bg-[#fcfcfc] p-3 text-left">
                        <div class="text-xs text-[#777]">{{ __('landing.ai_welcome') }}</div>
                    </div>
                </div>

                <form id="help-chat-form" action="{{ route('customer-help.request') }}" method="POST" class="mb-5 w-full max-w-[760px] rounded-2xl border border-[#E8E8E8] bg-white p-5 shadow-[0_2px_20px_rgba(0,0,0,0.06)]">
                    @csrf
                    <div class="mb-3 flex items-start justify-between gap-3">
                        <div>
                            <p class="text-[11px] font-medium uppercase tracking-[0.06em] text-[#aaa]">{{ __('landing.ask_me_anything') }}</p>
                            <p class="mt-1 text-xs text-gray-500">{{ __('landing.type_your_message_below') }}</p>
                        </div>
                    </div>
                    <div class="rounded-xl border border-[#efefef] bg-[#fcfcfc] p-3.5">
                        <textarea id="help-message-input" name="message" class="min-h-[120px] w-full resize-y border-none bg-transparent p-0 text-sm text-[#111] outline-none placeholder:text-gray-400 focus:ring-0" placeholder="{{ __('landing.send_message_placeholder') }}" rows="4">{{ old('message') }}</textarea>
                        <div class="mt-3 flex items-center justify-between border-t border-[#ececec] pt-3">
                            <div class="relative">
                                <button id="ticket-options-btn" type="button" class="inline-flex items-center gap-2 rounded-lg border border-[#E5E5E5] bg-white px-3 py-2 text-xs font-medium text-[#666] hover:bg-[#FFF1F1] hover:text-[#C62828]">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="5" r="1.5"></circle>
                                        <circle cx="12" cy="12" r="1.5"></circle>
                                        <circle cx="12" cy="19" r="1.5"></circle>
                                    </svg>
                                    <span>{{ __('landing.options') }}</span>
                                </button>
                                <div id="ticket-options-menu" class="hidden absolute left-0 top-11 z-20 w-56 rounded-lg border border-gray-200 bg-white p-3 shadow-lg">
                                    <label class="flex items-center gap-2 text-xs text-gray-700">
                                        <input id="create-ticket-toggle" type="checkbox" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        {{ __('landing.create_ticket_option') }}
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-[#C62828] px-4 py-2 text-xs font-semibold text-white shadow-[0_2px_8px_#c6282844] hover:bg-red-700">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="22" y1="2" x2="11" y2="13" />
                                    <polygon points="22 2 15 22 11 13 2 9 22 2" />
                                </svg>
                                <span>{{ __('landing.send') }}</span>
                            </button>
                        </div>
                    </div>
                    <div class="my-4 h-px bg-[#F0F0F0]"></div>
                    <div class="flex items-center justify-between gap-3 text-xs text-[#999]">
                        @auth
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2.5 py-1 text-[11px] text-green-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span>
                                {{ __('landing.signed_in_as') }} {{ auth()->user()->email }}
                            </span>
                            <span class="text-[11px] text-gray-400">{{ __('landing.enable_ticket_from_menu') }}</span>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2.5 py-1 text-[11px] font-medium text-[#C62828] hover:bg-red-100">
                                {{ __('landing.login_to_create_ticket') }}
                            </a>
                            <span class="text-[11px] text-gray-400">{{ __('landing.enable_ticket_from_menu') }}</span>
                        @endauth
                    </div>
                </form>

                <div class="relative z-10 mb-7 flex max-w-[640px] flex-wrap justify-center gap-2">
                    @foreach([
                        __('landing.suggestion_1'),
                        __('landing.suggestion_2'),
                        __('landing.suggestion_3'),
                        __('landing.suggestion_4')
                    ] as $suggestion)
                        <button type="button" class="help-chip whitespace-nowrap rounded-full border border-[#E5E5E5] bg-white px-4 py-2 text-[12.5px] font-medium text-[#333] transition-all hover:border-[#C62828] hover:bg-[#FFF8F8] hover:text-[#C62828]" data-message="{{ $suggestion }}">
                            {{ $suggestion }}
                        </button>
                    @endforeach
                </div>

                <p class="max-w-[600px] text-center text-[11.5px] leading-7 text-[#aaa]">
                    {{ __('landing.help_notice_ai') }}
                </p>
            </div>
        </section>

        @auth
            <section class="mx-auto mt-10 max-w-5xl px-5">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900">{{ __('landing.my_tickets') }}</h2>
                </div>
                <div class="space-y-4">
                    @forelse($tickets as $ticket)
                        <details class="rounded-xl border border-gray-200 bg-white group" {{ $loop->first ? 'open' : '' }}>
                            <summary class="list-none cursor-pointer p-5">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-sm text-gray-500">#{{ $ticket->id }} · {{ $ticket->created_at->format('d M Y, H:i') }}</div>
                                        <div class="mt-1 text-sm text-gray-700">{{ \Illuminate\Support\Str::limit($ticket->message, 90) }}</div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $ticket->status === 'attended' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                            {{ ucfirst($ticket->status) }}
                                        </span>
                                        <span class="text-gray-400 transition-transform group-open:rotate-180">
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M6 9l6 6 6-6"/>
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                            </summary>
                            <div class="border-t border-gray-100 p-5">
                                <div class="max-h-72 overflow-y-auto space-y-3 rounded-lg bg-gray-50 p-3">
                                    @foreach($ticket->messages as $msg)
                                        <div class="{{ $msg->sender_type === 'admin' ? 'text-left' : 'text-right' }}">
                                            <div class="inline-block max-w-[85%] rounded-lg px-3 py-2 text-sm {{ $msg->sender_type === 'admin' ? 'bg-white text-gray-700 border border-gray-200' : 'bg-[#C62828] text-white' }}">
                                                {{ $msg->message }}
                                            </div>
                                            <div class="mt-1 text-[11px] text-gray-400">
                                                {{ $msg->created_at->format('d M Y, H:i') }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <form action="{{ route('customer-help.message', $ticket) }}" method="POST" class="mt-3 flex items-end gap-2">
                                    @csrf
                                    <textarea name="message" rows="2" class="flex-1 rounded-lg border border-gray-300 text-sm focus:border-red-500 focus:ring-red-500" placeholder="{{ __('landing.send_message_placeholder') }}"></textarea>
                                    <button type="submit" class="rounded-lg bg-[#C62828] px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                                        {{ __('landing.send') }}
                                    </button>
                                </form>
                            </div>
                        </details>
                    @empty
                        <div class="rounded-xl border border-gray-200 bg-white p-8 text-center text-gray-500">
                            {{ __('landing.no_tickets_yet') }}
                        </div>
                    @endforelse
                </div>
            </section>
        @endauth
    </main>

    <x-landing.footer />

    <script>
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobileMenu');
            const menuIcon = document.getElementById('menuIcon');
            if (!mobileMenu || !menuIcon) return;
            mobileMenu.classList.toggle('hidden');
            menuIcon.style.transform = mobileMenu.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(90deg)';
        }

        const navbar = document.getElementById('mainNavbar');
        const navbarShell = navbar ? navbar.querySelector('.floating-navbar-shell') : null;
        window.addEventListener('scroll', function () {
            if (!navbarShell) return;
            if (window.pageYOffset > 10) {
                navbarShell.classList.add('navbar-scrolled');
            } else {
                navbarShell.classList.remove('navbar-scrolled');
            }
        });

        document.querySelectorAll('.help-chip').forEach((chip) => {
            chip.addEventListener('click', () => {
                const input = document.getElementById('help-message-input');
                if (input) input.value = chip.getAttribute('data-message') ?? '';
                input?.focus();
            });
        });

        const helpForm = document.getElementById('help-chat-form');
        const helpInput = document.getElementById('help-message-input');
        const chatMessages = document.getElementById('chat-messages');
        const ticketToggle = document.getElementById('create-ticket-toggle');
        const ticketBtn = document.getElementById('ticket-options-btn');
        const ticketMenu = document.getElementById('ticket-options-menu');
        const csrfToken = helpForm?.querySelector('input[name="_token"]')?.value ?? '';
        const isLoggedIn = @json(auth()->check());

        const fallbackAnswer = @json(__('landing.ai_fallback_response'));
        const loginRequiredAnswer = @json(__('landing.login_to_create_ticket'));
        const ticketCreatedAnswer = @json(__('landing.ticket_created_answer'));
        const ticketFailedAnswer = @json(__('landing.ticket_failed_answer'));

        const faqAnswers = [
            { q: @json(__('landing.suggestion_1')), a: @json(__('landing.faq_what_is_answer')) },
            { q: @json(__('landing.suggestion_2')), a: @json(__('landing.faq_who_can_apply_intro')) },
            { q: @json(__('landing.suggestion_3')), a: @json(__('landing.faq_how_process_intro')) },
            { q: @json(__('landing.suggestion_4')), a: @json(__('landing.faq_help_intro')) },
        ];

        function appendMessage(type, text) {
            if (!chatMessages) return;
            const wrap = document.createElement('div');
            wrap.className = type === 'user' ? 'text-right' : 'text-left';
            const bubble = document.createElement('div');
            bubble.className = type === 'user'
                ? 'inline-block rounded-lg bg-[#C62828] px-3 py-2 text-sm text-white max-w-[90%]'
                : 'inline-block rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-700 max-w-[90%]';
            bubble.textContent = text;
            wrap.appendChild(bubble);
            chatMessages.appendChild(wrap);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function getAssistantReply(message) {
            const needle = message.toLowerCase();
            const found = faqAnswers.find(({ q }) => needle.includes(String(q).toLowerCase()));
            return found ? found.a : fallbackAnswer;
        }

        async function maybeCreateTicket(message) {
            if (!ticketToggle?.checked) return;
            if (!isLoggedIn) {
                appendMessage('assistant', loginRequiredAnswer);
                return;
            }

            try {
                const response = await fetch(@json(route('customer-help.request')), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ message }),
                });

                if (response.ok) {
                    appendMessage('assistant', ticketCreatedAnswer);
                } else {
                    appendMessage('assistant', ticketFailedAnswer);
                }
            } catch (e) {
                appendMessage('assistant', ticketFailedAnswer);
            }
        }

        helpForm?.addEventListener('submit', async (event) => {
            event.preventDefault();
            const message = (helpInput?.value ?? '').trim();
            if (!message) return;

            appendMessage('user', message);
            appendMessage('assistant', getAssistantReply(message));
            await maybeCreateTicket(message);

            helpInput.value = '';
        });

        ticketBtn?.addEventListener('click', () => {
            ticketMenu?.classList.toggle('hidden');
        });

        document.addEventListener('click', (event) => {
            if (!ticketMenu || !ticketBtn) return;
            if (!ticketMenu.contains(event.target) && !ticketBtn.contains(event.target)) {
                ticketMenu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
