<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Online Vaccine Management and Appointment System - Book your vaccination appointments easily">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'OVMS') | VacciCare</title>
    <!-- AlpineJS -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <!-- Canvas Confetti -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js" defer></script>
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            document.documentElement.style.colorScheme = 'dark';
        } else {
            document.documentElement.classList.remove('dark');
            document.documentElement.style.colorScheme = 'light';
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-slate-50 dark:bg-[#0b0f19] text-slate-800 dark:text-slate-100 font-sans antialiased transition-colors duration-200">

    {{-- Decorative floating background gradient blobs --}}
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute top-[10%] left-[5%] w-[40vw] h-[40vw] max-w-[400px] max-h-[400px] bg-sky-400/10 dark:bg-sky-500/5 rounded-full blur-[80px] animate-float"></div>
        <div class="absolute bottom-[10%] right-[5%] w-[45vw] h-[45vw] max-w-[450px] max-h-[450px] bg-indigo-400/10 dark:bg-indigo-500/5 rounded-full blur-[100px] animate-float" style="animation-delay: -3s;"></div>
    </div>

    {{-- Navbar --}}
    @if(!request()->routeIs('login') && !request()->routeIs('register'))
        @include('partials.navbar')
    @endif

    {{-- Flash Messages --}}
    {{-- Global Toast Notifications Stack --}}
    <div x-data="toastContainer()"
         class="fixed top-24 right-4 z-50 flex flex-col gap-3 max-w-sm w-full pointer-events-none"
         @toast.window="add($event.detail)"
         style="display: none;"
         x-show="true">
        
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.visible"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="translate-x-full scale-95 opacity-0"
                 x-transition:enter-end="translate-x-0 scale-100 opacity-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="translate-x-0 scale-100 opacity-100"
                 x-transition:leave-end="translate-x-full scale-95 opacity-0"
                 class="pointer-events-auto relative overflow-hidden flex items-start gap-3.5 p-4 rounded-2xl border backdrop-blur-md shadow-xl transition-all duration-300 bg-white/95 dark:bg-slate-900/95 border-slate-200/60 dark:border-slate-800/80 shadow-slate-200/30 dark:shadow-black/50"
                 :class="toast.type === 'success' ? 'border-emerald-500/20 dark:border-emerald-500/10' : 
                         toast.type === 'error' ? 'border-rose-500/20 dark:border-rose-500/10' : 
                         toast.type === 'warning' ? 'border-amber-500/20 dark:border-amber-500/10' : 
                         'border-blue-500/20 dark:border-blue-500/10'">
                
                <!-- Status Colored Glow Indicator (Left edge line) -->
                <div class="absolute left-0 top-0 bottom-0 w-1 rounded-l-2xl"
                     :class="toast.type === 'success' ? 'bg-gradient-to-b from-emerald-400 to-teal-500' : 
                             toast.type === 'error' ? 'bg-gradient-to-b from-rose-400 to-red-500' : 
                             toast.type === 'warning' ? 'bg-gradient-to-b from-amber-400 to-orange-500' : 
                             'bg-gradient-to-b from-blue-400 to-indigo-500'"></div>

                <!-- Icon Container -->
                <div class="flex-shrink-0 p-2 rounded-xl text-white shadow-md animate-pulse-subtle"
                     :class="toast.type === 'success' ? 'bg-gradient-to-tr from-emerald-400 to-teal-500 shadow-emerald-250/20' : 
                             toast.type === 'error' ? 'bg-gradient-to-tr from-rose-400 to-red-500 shadow-rose-250/20' : 
                             toast.type === 'warning' ? 'bg-gradient-to-tr from-amber-400 to-orange-500 shadow-amber-250/20' : 
                             'bg-gradient-to-tr from-blue-400 to-indigo-500 shadow-blue-250/20'">
                    
                    <template x-if="toast.type === 'success'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </template>
                    <template x-if="toast.type === 'error'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </template>
                    <template x-if="toast.type === 'warning'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </template>
                    <template x-if="toast.type === 'info'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </template>
                </div>

                <!-- Text Content -->
                <div class="flex-1 min-w-0 pr-2">
                    <p class="text-[10px] font-bold tracking-wider uppercase"
                       :class="toast.type === 'success' ? 'text-emerald-600 dark:text-emerald-400' : 
                               toast.type === 'error' ? 'text-rose-600 dark:text-rose-400' : 
                               toast.type === 'warning' ? 'text-amber-600 dark:text-amber-400' : 
                               'text-blue-600 dark:text-blue-400'"
                       x-text="toast.title || (toast.type === 'success' ? 'Success' : toast.type === 'error' ? 'Error' : toast.type === 'warning' ? 'Warning' : 'System Notice')">
                    </p>
                    <p class="text-xs mt-1 text-slate-600 dark:text-slate-300 font-semibold leading-relaxed" x-text="toast.message"></p>
                </div>

                <!-- Dismiss Button -->
                <button @click="remove(toast.id)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors p-1 rounded-lg shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                <!-- Animated Time-Remaining Progress Bar -->
                <div class="absolute bottom-0 left-0 h-0.5 w-full transition-all ease-linear"
                     :class="toast.type === 'success' ? 'bg-gradient-to-r from-emerald-400 to-teal-500' : 
                             toast.type === 'error' ? 'bg-gradient-to-r from-rose-400 to-red-500' : 
                             toast.type === 'warning' ? 'bg-gradient-to-r from-amber-400 to-orange-500' : 
                             'bg-gradient-to-r from-blue-400 to-indigo-500'"
                     :style="`width: ${toast.progress}%; transition-duration: ${toast.duration}ms;`"
                     x-init="setTimeout(() => toast.progress = 0, 50)"></div>
            </div>
        </template>
    </div>

    {{-- Session Flash Dispatchers --}}
    @if(session('success'))
        <div x-data x-init="$nextTick(() => $dispatch('toast', { type: 'success', title: 'Success', message: '{{ addslashes(session('success')) }}' }))"></div>
    @endif
    @if(session('error'))
        <div x-data x-init="$nextTick(() => $dispatch('toast', { type: 'error', title: 'Error Failed', message: '{{ addslashes(session('error')) }}' }))"></div>
    @endif
    @if(session('status'))
        <div x-data x-init="$nextTick(() => $dispatch('toast', { type: 'info', title: 'System Notice', message: '{{ addslashes(session('status')) }}' }))"></div>
    @endif

    {{-- Main Content --}}
    @if(!request()->routeIs('login') && !request()->routeIs('register'))
        <main class="min-h-screen pt-16">
            @yield('content')
        </main>
    @else
        <main class="min-h-screen">
            @yield('content')
        </main>
    @endif

    {{-- Footer --}}
    @if(!request()->routeIs('login') && !request()->routeIs('register'))
        <footer class="bg-white dark:bg-[#070b14] border-t border-slate-200 dark:border-slate-800/60 mt-6 transition-colors duration-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 sm:py-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 items-center">
                    {{-- Left side: Brand, Mission details & Status --}}
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold bg-gradient-to-r from-blue-500 to-indigo-500 dark:from-blue-400 dark:to-indigo-400 bg-clip-text text-transparent">VacciCare</span>
                            <span class="px-1.5 py-0.2 text-[8px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 rounded-full flex items-center gap-1">
                                <span class="w-1 h-1 rounded-full bg-emerald-500 dark:bg-emerald-400 animate-pulse"></span>
                                Live
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 max-w-md leading-normal">
                            VacciCare national immunization platform. We facilitate vaccine appointments and secure e-KYC record tracking.
                        </p>
                    </div>

                    {{-- Right side: Helpline, Aadhaar info, Socials --}}
                    <div class="flex flex-col sm:items-end space-y-1.5">
                        <div class="flex items-center gap-3 text-[11px] text-slate-500 dark:text-slate-400">
                            <div>
                                <span class="text-slate-400 dark:text-slate-500">Helpline:</span>
                                <span class="font-semibold text-slate-700 dark:text-slate-300">1075</span>
                            </div>
                            <span class="text-slate-300 dark:text-slate-700">|</span>
                            <div>
                                <span class="text-slate-400 dark:text-slate-500">Support:</span>
                                <span class="font-semibold text-slate-700 dark:text-slate-300">support@vaccicare.gov.in</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2.5">
                            <span class="text-[10px] font-semibold tracking-wider text-slate-455 dark:text-slate-500 uppercase">Connect:</span>
                            <div class="flex items-center gap-2.5">
                                {{-- Instagram --}}
                                <a href="#" aria-label="Instagram"
                                   class="text-slate-400 dark:text-slate-500 hover:text-pink-500 dark:hover:text-pink-400 transition-all duration-200 hover:scale-110 transform">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                                    </svg>
                                </a>

                                {{-- Twitter / X --}}
                                <a href="#" aria-label="Twitter / X"
                                   class="text-slate-400 dark:text-slate-500 hover:text-sky-500 dark:hover:text-sky-400 transition-all duration-200 hover:scale-110 transform">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                    </svg>
                                </a>

                                {{-- LinkedIn --}}
                                <a href="#" aria-label="LinkedIn"
                                   class="text-slate-400 dark:text-slate-500 hover:text-blue-500 dark:hover:text-blue-400 transition-all duration-200 hover:scale-110 transform">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Divider --}}
                <div class="border-t border-slate-200 dark:border-slate-800/40 my-2.5"></div>

                {{-- Bottom: Copyright & Security Badge --}}
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                    <p class="text-[10px] text-slate-500">
                        &copy; {{ date('Y') }} VacciCare. National Digital Health Alignment. All rights reserved.
                    </p>
                    <div class="flex items-center gap-1 text-[9px] text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-950/40 px-2 py-0.5 rounded border border-slate-200 dark:border-slate-800/50">
                        <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        Aadhaar e-KYC Secure Platform
                    </div>
                </div>
            </div>
        </footer>
    @endif


    <style>
        .animate-pulse-subtle {
            animation: pulse-subtle 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse-subtle {
            0%, 100% { opacity: 1; }
            50% { opacity: .7; }
        }

        /* Page transition on load */
        @keyframes page-enter {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        main {
            animation: page-enter 0.35s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        /* Input focus ring upgrade */
        input:focus, select:focus, textarea:focus {
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        /* Smooth scrollbar */
        :root { scroll-behavior: smooth; }

        /* Button active scale utility */
        .active\:scale-98:active { transform: scale(0.98); }
        .active\:scale-97:active { transform: scale(0.97); }
    </style>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('toastContainer', () => ({
                toasts: [],
                add(detail) {
                    let id = Date.now() + Math.random();
                    let duration = detail.duration || 4500;
                    
                    this.toasts.push({
                        id: id,
                        type: detail.type || 'info',
                        title: detail.title || '',
                        message: detail.message || '',
                        visible: false,
                        progress: 100,
                        duration: duration
                    });

                    // Trigger micro-delight confetti on success toasts
                    if (detail.type === 'success') {
                        setTimeout(() => {
                            if (typeof confetti === 'function') {
                                confetti({
                                    particleCount: 80,
                                    spread: 50,
                                    origin: { y: 0.8 },
                                    colors: ['#38bdf8', '#3b82f6', '#10b981', '#fbbf24']
                                });
                            }
                        }, 100);
                    }
                    
                    // Animate entry
                    setTimeout(() => {
                        let index = this.toasts.findIndex(t => t.id === id);
                        if (index !== -1) {
                            this.toasts[index].visible = true;
                        }
                    }, 50);

                    // Auto remove
                    setTimeout(() => {
                        this.remove(id);
                    }, duration);
                },
                remove(id) {
                    let index = this.toasts.findIndex(t => t.id === id);
                    if (index !== -1) {
                        this.toasts[index].visible = false;
                        // Wait for fade out animation before deleting
                        setTimeout(() => {
                            this.toasts = this.toasts.filter(t => t.id !== id);
                        }, 250);
                    }
                }
            }));
        });
    </script>

    {{-- Floating FAQ / Support Widget Drawer --}}
    <div x-data="{ faqOpen: false, activeFaq: null }" class="fixed bottom-6 right-6 z-40 no-print" style="display: none;" x-show="true">
        <!-- Floating Bubble Trigger -->
        <button @click="faqOpen = !faqOpen" 
                class="w-14 h-14 bg-gradient-to-tr from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-full flex items-center justify-center shadow-lg hover:shadow-indigo-500/30 transition-all hover:scale-105 active:scale-95 cursor-pointer relative z-50">
            <!-- Pulse ring when drawer is closed -->
            <span x-show="!faqOpen" class="absolute inset-0 rounded-full bg-indigo-500/30 animate-ping-slow"></span>
            
            <!-- Toggle icons -->
            <svg x-show="!faqOpen" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <svg x-show="faqOpen" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <!-- Support Drawer Panel -->
        <div x-show="faqOpen"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-250 transform"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-8 scale-95"
             class="absolute bottom-18 right-0 w-80 sm:w-96 bg-white/95 dark:bg-slate-900/95 border border-slate-200/60 dark:border-slate-800/80 rounded-3xl p-5 shadow-2xl backdrop-blur-md transition-all duration-300 max-h-[75vh] flex flex-col justify-between overflow-y-auto"
             style="display: none;">
            
            <div>
                <!-- Header -->
                <div class="flex items-center gap-2.5 mb-4 pb-3.5 border-b border-slate-100 dark:border-slate-800/50">
                    <div class="p-1.5 bg-blue-100 dark:bg-blue-950/40 rounded-xl text-blue-600 dark:text-blue-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 dark:text-white text-sm">VacciCare FAQ Desk</h4>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">Quick guidance and alignment queries</p>
                    </div>
                </div>

                <!-- Accordion List -->
                <div class="space-y-2">
                    @php
                        $faqs = [
                            [
                                'q' => 'How do I download my vaccine certificate?',
                                'a' => 'Once your appointment status is updated to completed by the vaccination center administrator, a green Certificate link will appear next to the record on your bookings page or milestone timeline. Click it to view and print your digitally signed PDF.'
                            ],
                            [
                                'q' => 'How does Aadhaar e-KYC validation work?',
                                'a' => 'Go to your Profile and request Aadhaar OTP. The system connects to simulated UIDAI biometric registries. Input the OTP to authenticate. Completed validation unlocks booking gates for initial allocations.'
                            ],
                            [
                                'q' => 'When will I be eligible for Dose 2?',
                                'a' => 'Different vaccine allocations require specific days gap (e.g. 84 days for Covishield). Your dashboard computes eligibility timelines based on your Dose 1 date. The Dose 2 button unlocks automatically once eligible.'
                            ],
                            [
                                'q' => 'Is my vaccination record digitally verified?',
                                'a' => 'Yes! Every completed dose generates a unique reference code and a cryptographically signed URL. Scanning the certificate QR code redirects instantly to our public secure verification gate confirming citizen status.'
                            ],
                            [
                                'q' => 'How can I change my appointment time?',
                                'a' => 'If an appointment is pending or confirmed, you can cancel it from your bookings manager. This restores allocation stocks. You can then schedule a new slot for alternative dates or health hubs.'
                            ]
                        ];
                    @endphp
                    @foreach($faqs as $index => $faq)
                        <div class="border border-slate-100 dark:border-slate-800/60 rounded-xl overflow-hidden">
                            <button @click="activeFaq = (activeFaq === {{ $index }} ? null : {{ $index }})"
                                    class="w-full text-left p-3 flex items-center justify-between text-xs font-bold text-slate-700 dark:text-slate-200 bg-slate-50/50 dark:bg-slate-900/20 hover:bg-slate-100/50 dark:hover:bg-slate-800/20 transition-all cursor-pointer">
                                <span class="pr-2 leading-snug">{{ $faq['q'] }}</span>
                                <svg class="w-3.5 h-3.5 text-slate-400 transform transition-transform duration-200 shrink-0"
                                     :class="activeFaq === {{ $index }} ? 'rotate-180' : ''"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="activeFaq === {{ $index }}"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 max-h-0"
                                 x-transition:enter-end="opacity-100 max-h-40"
                                 class="p-3 text-xs text-slate-500 dark:text-slate-400 leading-relaxed border-t border-slate-100 dark:border-slate-800/40 bg-white dark:bg-[#151c2c]"
                                 style="display: none;">
                                {{ $faq['a'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Hotlines footer inside drawer -->
            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800/60 flex items-center justify-between text-[9px] text-slate-400">
                <span>National Helpline: 1075</span>
                <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>Admin Status: Online</span>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
