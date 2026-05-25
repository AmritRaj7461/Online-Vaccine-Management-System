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
    @if(session('success'))
        <div id="flash-success" class="fixed top-20 right-4 z-50 flex items-center gap-3 bg-emerald-500 text-white px-5 py-3 rounded-xl shadow-lg shadow-emerald-200 max-w-sm animate-slide-in">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
            <button onclick="document.getElementById('flash-success').remove()" class="ml-auto opacity-75 hover:opacity-100">✕</button>
        </div>
    @endif

    @if(session('error'))
        <div id="flash-error" class="fixed top-20 right-4 z-50 flex items-center gap-3 bg-rose-500 text-white px-5 py-3 rounded-xl shadow-lg shadow-rose-200 max-w-sm animate-slide-in">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
            <button onclick="document.getElementById('flash-error').remove()" class="ml-auto opacity-75 hover:opacity-100">✕</button>
        </div>
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
        @keyframes slide-in {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        .animate-slide-in { animation: slide-in 0.3s ease-out; }
    </style>

    <script>
        
        setTimeout(() => {
            document.getElementById('flash-success')?.remove();
            document.getElementById('flash-error')?.remove();
        }, 5000);
    </script>

    @stack('scripts')
</body>
</html>
