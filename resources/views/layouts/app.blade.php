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
        <footer class="bg-slate-900 text-slate-400 py-8 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="flex items-center justify-center gap-2 mb-2">
                    <div class="w-6 h-6 bg-gradient-to-br from-sky-400 to-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-white font-semibold">VacciCare</span>
                </div>
                <p class="text-sm">© {{ date('Y') }} Online Vaccine Management System. All rights reserved.</p>
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

    @stack('scripts')
</body>
</html>
