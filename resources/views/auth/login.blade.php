@extends('layouts.app')

@section('title', 'Welcome to VacciCare')

@section('content')
<div class="min-h-screen relative flex items-center justify-center overflow-hidden py-12 px-4 auth-container"
     style="background-color: #070913;"
     x-data="{
        mode: '{{ old('name') || $errors->has('name') || $errors->has('password_confirmation') || ($isRegister ?? false) ? 'register' : 'login' }}',
        showPassLogin: false,
        showPassReg: false,
        showPassRegConf: false,
        highlightFill: false,
        isMobile: window.innerWidth < 768,
        toggle() {
            this.mode = this.mode === 'login' ? 'register' : 'login';
        },
        autofill(email, password) {
            const emailInput = document.getElementById('login_email');
            const passInput = document.getElementById('login_password');
            if (emailInput && passInput) {
                emailInput.value = email;
                passInput.value = password;
                // Dispatch input event so standard bindings detect changes
                emailInput.dispatchEvent(new Event('input', { bubbles: true }));
                passInput.dispatchEvent(new Event('input', { bubbles: true }));
                
                // Trigger quick visual highlight animation
                this.highlightFill = true;
                setTimeout(() => { this.highlightFill = false; }, 1200);
            }
        }
     }"
     x-init="window.addEventListener('resize', () => { isMobile = window.innerWidth < 768 })">

    {{-- Glowing animated fluid blobs in the background --}}
    <div class="absolute -top-32 -left-32 w-[600px] h-[600px] bg-gradient-to-tr from-indigo-500/20 to-purple-600/5 rounded-full blur-[130px] animate-blob pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-[600px] h-[600px] bg-gradient-to-br from-emerald-500/15 to-teal-500/5 rounded-full blur-[130px] animate-blob pointer-events-none" style="animation-delay: 5s;"></div>
    <div class="absolute top-1/2 left-1/3 w-96 h-96 bg-gradient-to-bl from-pink-500/10 to-indigo-500/5 rounded-full blur-[110px] animate-blob pointer-events-none" style="animation-delay: 10s;"></div>

    {{-- Floating glass particles in background --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none opacity-30">
        @for($i = 0; $i < 15; $i++)
            <div class="absolute rounded-full border border-white/10 bg-white/5 animate-float"
                 style="
                    width: {{ rand(12, 28) }}px;
                    height: {{ rand(12, 28) }}px;
                    left: {{ rand(2, 98) }}%;
                    top: {{ rand(2, 98) }}%;
                    animation-duration: {{ rand(14, 28) }}s;
                    animation-delay: {{ rand(-15, 0) }}s;
                 ">
                 <div class="w-1.5 h-1.5 rounded-full bg-indigo-400/30 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 animate-ping"></div>
            </div>
        @endfor
    </div>

    {{-- Premium Floating Digital Grid Overlay --}}
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff02_1px,transparent_1px),linear-gradient(to_bottom,#ffffff02_1px,transparent_1px)] bg-[size:50px_50px] pointer-events-none"></div>

    {{-- Main Glass Card Container --}}
    <div class="relative w-full max-w-4xl min-h-[640px] md:h-[640px] md:min-h-0 backdrop-blur-2xl rounded-3xl border border-white/10 shadow-[0_30px_90px_-20px_rgba(0,0,0,0.7)] overflow-hidden flex flex-col md:flex-row"
         style="background-color: rgba(9, 13, 26, 0.75);">

        {{-- Mobile Responsive Toggle Header --}}
        <div class="flex md:hidden backdrop-blur-md p-2.5 border-b border-white/10 shrink-0 gap-2"
             style="background-color: rgba(9, 13, 26, 0.9);">
            <button @click="mode = 'login'"
                    :class="mode === 'login' ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg shadow-indigo-500/20' : 'text-slate-400 hover:text-slate-200'"
                    class="flex-1 py-2 text-center text-xs font-semibold rounded-xl transition-all duration-300">
                Sign In
            </button>
            <button @click="mode = 'register'"
                    :class="mode === 'register' ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg shadow-indigo-500/20' : 'text-slate-400 hover:text-slate-200'"
                    class="flex-1 py-2 text-center text-xs font-semibold rounded-xl transition-all duration-300">
                Create Account
            </button>
        </div>

        {{-- ------------------------------------------------------------- --}}
        {{-- LEFT SIDE FORM PANEL (Sign In)                                --}}
        {{-- ------------------------------------------------------------- --}}
        <div class="flex-1 h-full relative p-8 sm:p-12 transition-all duration-700 ease-in-out md:block overflow-y-auto form-scrollbar"
             x-show="mode === 'login' || !isMobile"
             :class="mode === 'login' ? 'md:translate-x-0 md:opacity-100 md:pointer-events-auto' : 'md:translate-x-[40px] md:opacity-0 md:pointer-events-none'">

            <div class="h-full flex flex-col justify-center"
                 x-show="mode === 'login'"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 transform -translate-x-6"
                 x-transition:enter-end="opacity-100 transform translate-x-0">

                {{-- Branding --}}
                <div class="mb-6">
                    <a href="{{ route('login') }}" class="flex items-center gap-3 group mb-4">
                        <div class="w-9 h-9 bg-gradient-to-br from-indigo-400 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/20 group-hover:scale-105 transition-transform duration-300">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold bg-gradient-to-r from-indigo-300 to-purple-200 bg-clip-text text-transparent tracking-wide">VacciCare</span>
                    </a>
                    <h2 class="text-3xl font-extrabold text-white tracking-tight">Welcome Back!</h2>
                    <p class="text-slate-400 text-sm mt-1">Sign in to manage appointments & check your health records.</p>
                </div>

                {{-- Validation Errors --}}
                @if($errors->any() && (!old('name') && !$errors->has('name') && !$errors->has('password_confirmation')))
                    <div class="mb-5 bg-rose-500/10 border border-rose-500/20 rounded-2xl p-4">
                        <ul class="text-xs text-rose-400 space-y-1">
                            @foreach($errors->all() as $error)
                                <li class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 bg-rose-400 rounded-full shrink-0 animate-pulse"></span>
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Login Form --}}
                <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="login_email" class="block text-xs font-semibold text-slate-300 mb-2">Email Address</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </span>
                            <input
                                type="email"
                                name="email"
                                id="login_email"
                                value="{{ old('email') }}"
                                placeholder="e.g., name@domain.com"
                                required
                                :class="highlightFill ? 'ring-4 ring-indigo-500/30 border-indigo-400 bg-indigo-950/20' : 'border-white/10 bg-white/[0.03]'"
                                class="w-full pl-12 pr-4 py-3.5 border rounded-2xl text-white placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500/60 transition-all duration-300 text-sm"
                            />
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label for="login_password" class="block text-xs font-semibold text-slate-300">Password</label>
                        </div>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </span>
                            <input
                                :type="showPassLogin ? 'text' : 'password'"
                                name="password"
                                id="login_password"
                                placeholder="Enter your password"
                                required
                                :class="highlightFill ? 'ring-4 ring-indigo-500/30 border-indigo-400 bg-indigo-950/20' : 'border-white/10 bg-white/[0.03]'"
                                class="w-full pl-12 pr-12 py-3.5 border rounded-2xl text-white placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500/60 transition-all duration-300 text-sm"
                            />
                            <button type="button" @click="showPassLogin = !showPassLogin" class="absolute right-4 top-3.5 text-slate-400 hover:text-white transition-colors focus:outline-none z-10">
                                <svg x-show="!showPassLogin" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showPassLogin" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-xs pt-1">
                        <label class="flex items-center gap-2.5 text-slate-400 cursor-pointer hover:text-slate-200 transition-colors">
                            <input type="checkbox" name="remember" class="rounded bg-slate-950/60 border-white/10 text-indigo-500 focus:ring-0 focus:ring-offset-0 w-4 h-4">
                            Remember me
                        </label>
                    </div>

                    <button type="submit"
                            class="w-full py-3.5 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-bold rounded-2xl transition-all shadow-lg shadow-indigo-500/20 flex items-center justify-center gap-2 active:scale-[0.98] text-sm tracking-wide">
                        Sign In
                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </form>

                {{-- Elegant Clickable Quick-Login Widget --}}
                <div class="mt-8 border-t border-white/[0.08] pt-5">
                    <p class="text-xs text-slate-400 font-medium mb-3 flex items-center gap-2">
                        <span class="inline-flex w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                        ✨ Easy-Access Demo Accounts
                    </p>
                    <div class="flex flex-wrap gap-2.5">
                        <button type="button" @click="autofill('admin@vaccicare.com', 'password')"
                                class="px-4 py-2.5 text-xs font-semibold rounded-xl bg-white/[0.03] hover:bg-white/[0.08] border border-white/10 text-slate-300 hover:text-white transition-all duration-300 flex items-center gap-1.5 shadow-sm hover:scale-[1.03] active:scale-[0.97]">
                            🔑 Center Manager
                        </button>
                        <button type="button" @click="autofill('user@vaccicare.com', 'password')"
                                class="px-4 py-2.5 text-xs font-semibold rounded-xl bg-white/[0.03] hover:bg-white/[0.08] border border-white/10 text-slate-300 hover:text-white transition-all duration-300 flex items-center gap-1.5 shadow-sm hover:scale-[1.03] active:scale-[0.97]">
                            👤 Patient
                        </button>
                    </div>
                </div>

            </div>
        </div>

        {{-- ------------------------------------------------------------- --}}
        {{-- RIGHT SIDE FORM PANEL (Create Account)                        --}}
        {{-- ------------------------------------------------------------- --}}
        <div class="flex-1 h-full relative p-8 sm:p-12 transition-all duration-700 ease-in-out md:block overflow-y-auto form-scrollbar"
             x-show="mode === 'register' || !isMobile"
             :class="mode === 'register' ? 'md:translate-x-0 md:opacity-100 md:pointer-events-auto' : 'md:-translate-x-[40px] md:opacity-0 md:pointer-events-none'">

            <div class="h-full flex flex-col justify-center animate-fade-in"
                 x-show="mode === 'register'"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 transform translate-x-6"
                 x-transition:enter-end="opacity-100 transform translate-x-0">

                {{-- Branding --}}
                <div class="mb-5">
                    <a href="{{ route('login') }}" class="flex items-center gap-3 group mb-3">
                        <div class="w-9 h-9 bg-gradient-to-br from-indigo-400 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/20 group-hover:scale-105 transition-transform duration-300">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold bg-gradient-to-r from-indigo-300 to-purple-200 bg-clip-text text-transparent tracking-wide">Register</span>
                    </a>
                    <h2 class="text-3xl font-extrabold text-white tracking-tight">Create Account</h2>
                    <p class="text-slate-400 text-sm mt-1">Join VacciCare to book vaccine slots easily.</p>
                </div>

                {{-- Validation Errors --}}
                @if($errors->any() && (old('name') || $errors->has('name') || $errors->has('password_confirmation')))
                    <div class="mb-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl p-4">
                        <ul class="text-xs text-rose-400 space-y-1">
                            @foreach($errors->all() as $error)
                                <li class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 bg-rose-400 rounded-full shrink-0 animate-pulse"></span>
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Register Form --}}
                <form method="POST" action="{{ route('register.post') }}" class="space-y-3.5">
                    @csrf
                    <div class="grid grid-cols-2 gap-3.5">
                        <div>
                            <label for="reg_name" class="block text-xs font-semibold text-slate-300 mb-1.5">Full Name *</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-2.5 text-slate-400">
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </span>
                                <input type="text" name="name" id="reg_name" value="{{ old('name') }}" required placeholder="e.g., Jane Doe"
                                    class="w-full pl-10 pr-4 py-2.5 bg-white/[0.03] border border-white/10 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500/60 transition-all text-sm"/>
                            </div>
                        </div>
                        <div>
                            <label for="reg_phone" class="block text-xs font-semibold text-slate-300 mb-1.5">Phone Number</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-2.5 text-slate-400">
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </span>
                                <input type="tel" name="phone" id="reg_phone" value="{{ old('phone') }}" placeholder="e.g., +91..."
                                    class="w-full pl-10 pr-4 py-2.5 bg-white/[0.03] border border-white/10 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500/60 transition-all text-sm"/>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="reg_email" class="block text-xs font-semibold text-slate-300 mb-1.5">Email Address *</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-2.5 text-slate-400">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </span>
                            <input type="email" name="email" id="reg_email" value="{{ old('email') }}" required placeholder="e.g., jane.doe@domain.com"
                                class="w-full pl-10 pr-4 py-2.5 bg-white/[0.03] border border-white/10 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500/60 transition-all text-sm"/>
                        </div>
                    </div>

                    <div>
                        <label for="reg_dob" class="block text-xs font-semibold text-slate-300 mb-1.5">Date of Birth</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-2.5 text-slate-400">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <style>
                                    
                                    input[type="date"]::-webkit-calendar-picker-indicator {
                                        filter: invert(1);
                                        opacity: 0.5;
                                        cursor: pointer;
                                    }
                                    input[type="date"]::-webkit-calendar-picker-indicator:hover {
                                        opacity: 0.8;
                                    }
                                </style>
                            </span>
                            <input type="date" name="dob" id="reg_dob" value="{{ old('dob') }}"
                                class="w-full pl-10 pr-4 py-2.5 bg-white/[0.03] border border-white/10 rounded-xl text-slate-300 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500/60 transition-all text-sm"/>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3.5">
                        <div>
                            <label for="reg_pass" class="block text-xs font-semibold text-slate-300 mb-1.5">Password *</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-2.5 text-slate-400">
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </span>
                                <input :type="showPassReg ? 'text' : 'password'" name="password" id="reg_pass" required placeholder="Min 8 chars"
                                    class="w-full pl-10 pr-9 py-2.5 bg-white/[0.03] border border-white/10 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500/60 transition-all text-sm"/>
                                <button type="button" @click="showPassReg = !showPassReg" class="absolute right-3 top-2.5 text-slate-400 hover:text-white transition-colors focus:outline-none z-10">
                                    <svg x-show="!showPassReg" class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <svg x-show="showPassReg" class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label for="reg_pass_conf" class="block text-xs font-semibold text-slate-300 mb-1.5">Confirm Password *</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-2.5 text-slate-400">
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </span>
                                <input :type="showPassRegConf ? 'text' : 'password'" name="password_confirmation" id="reg_pass_conf" required placeholder="Repeat password"
                                    class="w-full pl-10 pr-9 py-2.5 bg-white/[0.03] border border-white/10 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500/60 transition-all text-sm"/>
                                <button type="button" @click="showPassRegConf = !showPassRegConf" class="absolute right-3 top-2.5 text-slate-400 hover:text-white transition-colors focus:outline-none z-10">
                                    <svg x-show="!showPassRegConf" class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <svg x-show="showPassRegConf" class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full py-3 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-bold rounded-2xl transition-all shadow-lg shadow-indigo-500/20 flex items-center justify-center gap-2 active:scale-[0.98] text-sm tracking-wide">
                        Create Account
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </form>

            </div>
        </div>

        {{-- ------------------------------------------------------------- --}}
        {{-- SLIDING OVERLAY PANEL: Premium Decorative Sliding Capsule     --}}
        {{-- ------------------------------------------------------------- --}}
        <div class="hidden md:block absolute top-0 w-1/2 h-full z-20 transition-all duration-700 ease-in-out overflow-hidden"
             :class="mode === 'login' ? 'left-1/2 rounded-l-[50px] rounded-r-3xl border-l border-white/10' : 'left-0 rounded-r-[50px] rounded-l-3xl border-r border-white/10'">

            {{-- Slider Console Interior Gradient --}}
            <div class="absolute inset-0 w-[200%] h-full bg-gradient-to-br from-indigo-500 via-purple-700 to-pink-800 transition-all duration-700 ease-in-out flex"
                 :class="mode === 'login' ? '-translate-x-1/2' : 'translate-x-0'">

                {{-- Decorative floating glassy spheres inside the overlay --}}
                <div class="absolute top-10 left-12 w-28 h-28 bg-white/10 rounded-full blur-md animate-pulse"></div>
                <div class="absolute bottom-16 right-16 w-36 h-36 bg-black/20 rounded-full blur-lg animate-pulse" style="animation-delay: 2.5s;"></div>

                {{-- Panel Left (Visible when Register Mode is active on the right) --}}
                <div class="w-1/2 h-full flex flex-col items-center justify-center p-12 text-center text-white select-none">
                    
                    {{-- Elegant Interactive Badge --}}
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/10 backdrop-blur-md rounded-full text-xs font-semibold tracking-wide text-indigo-100 mb-6 border border-white/10">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 shadow-[0_0_8px_#34d399] animate-ping shrink-0"></span>
                        ✨ Welcome Back!
                    </div>

                    <div class="w-16 h-16 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center mb-6 shadow-xl border border-white/20">
                        <svg class="w-8 h-8 text-indigo-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>

                    <h3 class="text-3xl font-extrabold tracking-tight">Already registered?</h3>
                    <p class="text-indigo-100 text-sm mt-4 leading-relaxed max-w-xs">
                        Log in to check your immunization schedules, download certificates, and book your next doses.
                    </p>
                    
                    <button @click="toggle()"
                            class="mt-8 px-8 py-3 bg-transparent border border-white text-white font-bold rounded-full hover:bg-white hover:text-indigo-950 hover:shadow-lg hover:scale-[1.03] transition-all active:scale-[0.97] shadow-md text-sm">
                        Sign In &rarr;
                    </button>
                </div>

                {{-- Panel Right (Visible when Login Mode is active on the left) --}}
                <div class="w-1/2 h-full flex flex-col items-center justify-center p-12 text-center text-white select-none">
                    
                    {{-- Elegant Interactive Badge --}}
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/10 backdrop-blur-md rounded-full text-xs font-semibold tracking-wide text-purple-100 mb-6 border border-white/10">
                        <span class="w-2 h-2 rounded-full bg-indigo-400 shadow-[0_0_8px_#818cf8] animate-pulse shrink-0"></span>
                        🌱 Secure & Free Registration
                    </div>

                    <div class="w-16 h-16 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center mb-6 shadow-xl border border-white/20">
                        <svg class="w-8 h-8 text-indigo-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                    </div>

                    <h3 class="text-3xl font-extrabold tracking-tight">New to VacciCare?</h3>
                    <p class="text-indigo-100 text-sm mt-4 leading-relaxed max-w-xs">
                        Set up your free account today to instantly book vaccine slots, view local clinics, and track your family's health.
                    </p>
                    
                    <button @click="toggle()"
                            class="mt-8 px-8 py-3 bg-white text-indigo-900 font-bold rounded-full hover:bg-slate-50 hover:shadow-lg hover:scale-[1.03] transition-all active:scale-[0.97] shadow-md text-sm">
                        Create Account &rarr;
                    </button>
                </div>

            </div>
        </div>

    </div>
</div>

<style>
    @import url('https:
    
    .auth-container {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    
    @keyframes blob {
        0%, 100% {
            transform: translate(0px, 0px) scale(1) rotate(0deg);
        }
        33% {
            transform: translate(45px, -60px) scale(1.18) rotate(120deg);
        }
        66% {
            transform: translate(-35px, 40px) scale(0.85) rotate(240deg);
        }
    }
    
    .animate-blob {
        animation: blob 28s infinite alternate ease-in-out;
    }

    
    @keyframes float {
        0%, 100% {
            transform: translateY(0px) rotate(0deg) scale(1);
            opacity: 0.15;
        }
        50% {
            transform: translateY(-40px) rotate(180deg) scale(1.15);
            opacity: 0.45;
        }
    }
    
    .animate-float {
        animation: float 18s infinite ease-in-out;
    }

    
    .form-scrollbar::-webkit-scrollbar {
        width: 5px;
    }
    .form-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .form-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 99px;
    }
    .form-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.2);
    }
</style>
@endsection
