@extends('layouts.app')

@section('title', 'Admin Profile')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-semibold bg-amber-100 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 px-2.5 py-0.5 rounded-full uppercase tracking-wider">System Administrator</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Command Center Profile</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Manage admin credentials and monitor global database allocations.</p>
        </div>
    </div>

    {{-- Stats Cards / Metric grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @php
            $metrics = [
                ['label' => 'Clinics Under Guard', 'value' => $stats['total_centers'], 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'color' => 'blue', 'accent' => 'from-blue-500 to-sky-600'],
                ['label' => 'Vaccines Configured', 'value' => $stats['total_vaccines'], 'icon' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z', 'color' => 'indigo', 'accent' => 'from-indigo-500 to-violet-600'],
                ['label' => 'Total Registered Patients', 'value' => $stats['total_users'], 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'color' => 'purple', 'accent' => 'from-purple-500 to-pink-600'],
                ['label' => 'Processed Bookings', 'value' => $stats['total_appointments'], 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'color' => 'emerald', 'accent' => 'from-emerald-500 to-teal-600'],
            ];
        @endphp

        @foreach($metrics as $m)
            <div class="bg-white dark:bg-[#151c2c] rounded-2xl border border-slate-100 dark:border-slate-800 p-5 shadow-sm transition-all hover:shadow-md hover:scale-[1.01] duration-200">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-gradient-to-br {{ $m['accent'] }} rounded-xl flex items-center justify-center text-white shadow-md shadow-{{ $m['color'] }}-200 dark:shadow-none shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $m['icon'] }}"/>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-black text-slate-800 dark:text-white leading-none">{{ $m['value'] }}</p>
                <p class="text-xs font-semibold text-slate-400 dark:text-slate-400 mt-2 tracking-wide uppercase">{{ $m['label'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- Main Body --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Profile Fields Form --}}
        <div class="lg:col-span-2 bg-white dark:bg-[#151c2c] rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-200">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800">
                <h2 class="font-semibold text-slate-800 dark:text-white">Admin Personal Details</h2>
                <p class="text-xs text-slate-400 dark:text-slate-400 mt-0.5">Edit core system credentials and admin markers.</p>
            </div>

            @if($errors->any())
                <div class="p-6 bg-rose-500/10 border-b border-rose-500/20">
                    <ul class="text-xs text-rose-500 space-y-1">
                        @foreach($errors->all() as $error)
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 bg-rose-500 rounded-full shrink-0"></span>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.profile.update') }}" class="p-6 space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    {{-- Admin Full Name --}}
                    <div>
                        <label for="name" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Display Name *</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </span>
                            <input type="text" name="name" id="name" required value="{{ old('name', $admin->name) }}"
                                   class="w-full pl-12 pr-4 py-2.5 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-800 rounded-xl text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all text-sm"/>
                        </div>
                    </div>

                    {{-- Admin Email (Read-Only) --}}
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">System Login Email</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-slate-400 opacity-60">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </span>
                            <input type="email" id="email" value="{{ $admin->email }}" readonly
                                   class="w-full pl-12 pr-4 py-2.5 bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-500 dark:text-slate-400 cursor-not-allowed text-sm focus:outline-none"/>
                        </div>
                    </div>

                    {{-- Phone Number --}}
                    <div>
                        <label for="phone" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Primary Phone</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h2.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </span>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $admin->phone) }}" placeholder="e.g. 9876543210"
                                   class="w-full pl-12 pr-4 py-2.5 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-800 rounded-xl text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all text-sm"/>
                        </div>
                    </div>

                    {{-- Date of Birth --}}
                    <div>
                        <label for="dob" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">DOB</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </span>
                            <input type="date" name="dob" id="dob" value="{{ old('dob', $admin->dob ? $admin->dob->format('Y-m-d') : '') }}"
                                   class="w-full pl-12 pr-4 py-2.5 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-800 rounded-xl text-slate-800 dark:text-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all text-sm"/>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between border-t border-slate-100 dark:border-slate-800 pt-6">
                    <p class="text-xs text-slate-400 dark:text-slate-400">Administrator Token: <span class="font-mono text-slate-700 dark:text-slate-300">#ADM-{{ str_pad($admin->id, 4, '0', STR_PAD_LEFT) }}</span></p>
                    <button type="submit"
                            class="px-6 py-2.5 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white font-semibold rounded-xl transition-all shadow-sm active:scale-98 text-sm">
                        Apply Changes
                    </button>
                </div>
            </form>
        </div>

        {{-- Admin CommandCenter Info Badge --}}
        <div class="space-y-6">
            
            {{-- Visual Badging --}}
            <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl p-6 text-white shadow-md relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 w-32 h-32 bg-white/5 rounded-full blur-xl pointer-events-none"></div>
                <div class="relative z-10">
                    <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center mb-4 backdrop-blur-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg mb-1 leading-none">Security Officer Clearance</h3>
                    <p class="text-amber-100 text-xs mt-2 leading-relaxed">Authorized access enabled. You are cleared to manage medical centers, create vaccine batches, and process customer appointment clearances globally.</p>
                </div>
            </div>

            {{-- System Metadata Cards --}}
            <div class="bg-white dark:bg-[#151c2c] rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm p-6 space-y-4 transition-colors duration-200">
                <h3 class="font-semibold text-slate-800 dark:text-white">Clearance Info</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500 dark:text-slate-400">System Role</span>
                        <span class="font-bold text-amber-600 dark:text-amber-400 capitalize">GLOBAL_ADMIN</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500 dark:text-slate-400">Enrolled On</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $admin->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500 dark:text-slate-400">Encryption Status</span>
                        <span class="font-bold text-emerald-600 dark:text-emerald-400 inline-flex items-center gap-1">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full animate-ping shrink-0"></span>
                            Secure (SSL)
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
