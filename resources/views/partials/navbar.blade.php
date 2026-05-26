<nav class="fixed top-0 left-0 right-0 z-40 bg-white/95 dark:bg-[#0b0f19]/95 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 shadow-sm transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            <a href="{{ auth()->check() ? (auth()->user()->isAdmin() ? route('admin.dashboard') : route('user.dashboard')) : route('login') }}"
               class="flex items-center gap-2.5 group">
                <div class="w-9 h-9 bg-gradient-to-br from-sky-400 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-200 dark:shadow-none group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xl font-bold bg-gradient-to-r from-sky-600 to-blue-700 dark:from-sky-400 dark:to-blue-400 bg-clip-text text-transparent">VacciCare</span>
            </a>

            <div class="hidden md:flex items-center gap-1">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                           class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.vaccines.index') }}"
                           class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.vaccines.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                            Vaccines
                        </a>
                        <a href="{{ route('admin.centers.index') }}"
                           class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.centers.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                            Centers
                        </a>
                        <a href="{{ route('admin.appointments.index') }}"
                           class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.appointments.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                            Appointments
                        </a>
                    @else
                        <a href="{{ route('user.dashboard') }}"
                           class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('user.dashboard') ? 'bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('user.vaccines.index') }}"
                           class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('user.vaccines.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                            Vaccines
                        </a>
                        <a href="{{ route('user.appointments.index') }}"
                           class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('user.appointments.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                            My Appointments
                        </a>
                        <a href="{{ route('user.appointments.create') }}"
                           class="px-4 py-2 text-sm font-semibold bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-600 hover:to-blue-700 text-white rounded-lg transition-all shadow-sm active:scale-98">
                            + Book Appointment
                        </a>
                    @endif
                @endauth
            </div>

            <div class="flex items-center gap-3">
                <button onclick="toggleTheme()"
                        class="p-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-lg text-slate-500 dark:text-slate-400 transition-all active:scale-95 focus:outline-none"
                        aria-label="Toggle theme">
                    <svg class="w-5 h-5 hidden dark:block text-amber-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707m2.828 9.9a5 5 0 117.072 0l-7.072 0z"/>
                    </svg>
                    <svg class="w-5 h-5 block dark:hidden text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>

                {{-- Notifications Bell Icon Widget --}}
                @auth
                    <div class="relative" x-data="{ open: false }" style="display: inline-block;">
                        @php
                            $unreadNotifications = auth()->user()->notifications()->unread()->take(5)->get();
                            $unreadCount = auth()->user()->notifications()->unread()->count();
                        @endphp
                        <button @click="open = !open"
                                class="p-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-lg text-slate-500 dark:text-slate-400 transition-all active:scale-95 focus:outline-none relative cursor-pointer"
                                aria-label="Notifications">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            @if($unreadCount > 0)
                                <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-rose-500 rounded-full animate-pulse-slow"></span>
                            @endif
                        </button>

                        {{-- Notifications Dropdown Panel --}}
                        <div x-show="open" @click.away="open = false"
                             x-transition:enter="transition ease-out duration-150 transform"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             class="absolute right-0 mt-2 w-80 bg-white dark:bg-[#151c2c] border border-slate-100 dark:border-slate-800 shadow-xl rounded-2xl py-2 z-50 overflow-hidden"
                             style="display: none;">
                            
                            <div class="px-4 py-2 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                                <h4 class="text-xs uppercase font-extrabold text-slate-400 tracking-wider">Alerts ({{ $unreadCount }})</h4>
                                <a href="{{ route('user.notifications.index') }}" class="text-[10px] font-bold text-blue-650 dark:text-blue-400 hover:underline">Inbox →</a>
                            </div>

                            <div class="max-h-60 overflow-y-auto divide-y divide-slate-50 dark:divide-slate-800/40">
                                @forelse($unreadNotifications as $notif)
                                    <div class="p-3.5 hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors">
                                        <div class="flex items-start gap-2.5">
                                            <span class="w-2 h-2 mt-1.5 rounded-full shrink-0 {{ $notif->type === 'success' ? 'bg-emerald-500' : ($notif->type === 'warning' ? 'bg-rose-500' : 'bg-blue-500') }}"></span>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs font-bold text-slate-850 dark:text-white leading-tight truncate">{{ $notif->title }}</p>
                                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 leading-normal line-clamp-2">{{ $notif->message }}</p>
                                                <span class="text-[9px] text-slate-450 dark:text-slate-500 mt-1 block">{{ $notif->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-6 text-center text-xs text-slate-400">
                                        No unread notifications
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endauth

                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                                class="flex items-center gap-2 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-lg transition-colors text-sm font-medium text-slate-700 dark:text-slate-300">
                            <div class="w-7 h-7 bg-gradient-to-br from-sky-400 to-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="hidden sm:block truncate max-w-[100px]">{{ auth()->user()->name }}</span>
                            @if(auth()->user()->isAdmin())
                                <span class="hidden sm:block text-xs bg-amber-100 dark:bg-amber-950/30 text-amber-700 dark:text-amber-400 px-1.5 py-0.5 rounded-full font-semibold">Admin</span>
                            @endif
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <div x-show="open" @click.away="open = false"
                             x-transition
                             class="absolute right-0 mt-2 w-48 bg-white dark:bg-[#151c2c] rounded-xl shadow-lg border border-slate-100 dark:border-slate-800 py-1 z-50"
                             style="display: none;">
                            <div class="px-3 py-2 border-b border-slate-100 dark:border-slate-800">
                                <p class="text-xs text-slate-500 dark:text-slate-400">Signed in as</p>
                                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            
                            <a href="{{ auth()->user()->isAdmin() ? route('admin.profile') : route('user.profile') }}"
                               class="px-3 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                My Profile
                            </a>

                            <div class="border-t border-slate-100 dark:border-slate-800"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-3 py-2 text-sm text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/20 transition-colors flex items-center gap-2">
                                    <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">Login</a>
                    <a href="{{ route('register') }}" class="text-sm font-medium bg-gradient-to-r from-sky-500 to-blue-600 text-white px-4 py-2 rounded-lg hover:from-sky-600 hover:to-blue-700 transition-all shadow-sm">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<script>
    function toggleTheme() {
        document.documentElement.classList.add('no-transitions');
        
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            document.documentElement.style.colorScheme = 'light';
            localStorage.theme = 'light';
        } else {
            document.documentElement.classList.add('dark');
            document.documentElement.style.colorScheme = 'dark';
            localStorage.theme = 'dark';
        }
        
        // Force a reflow to ensure the class is active
        window.getComputedStyle(document.documentElement).opacity;
        
        setTimeout(() => {
            document.documentElement.classList.remove('no-transitions');
        }, 50);

        window.dispatchEvent(new Event('theme-changed'));
    }
</script>
