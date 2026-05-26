@extends('layouts.app')

@section('title', 'Verify Exemption Pass')

@section('content')
<div class="max-w-md mx-auto px-4 py-16 text-center transition-colors duration-200">
    
    {{-- Main verification card --}}
    <div class="bg-white dark:bg-[#151c2c] rounded-3xl border border-slate-100 dark:border-slate-800 shadow-xl p-8 space-y-6">
        
        {{-- Status emblem --}}
        <div class="w-16 h-16 bg-amber-100 dark:bg-amber-950/30 rounded-full flex items-center justify-center mx-auto mb-2 border border-amber-500/20">
            <svg class="w-8 h-8 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>

        <div>
            <h1 class="text-xl font-black text-slate-800 dark:text-white">Offline Registration Entry Pass</h1>
            <p class="text-xs text-amber-600 dark:text-amber-400 font-extrabold uppercase tracking-wider mt-1.5">Aadhaar Verification Required</p>
        </div>

        {{-- Patient details --}}
        <div class="bg-slate-50 dark:bg-slate-900/40 p-4 border border-transparent dark:border-slate-800/40 rounded-2xl text-left text-xs space-y-3">
            <div class="flex justify-between border-b border-slate-100 dark:border-slate-850 pb-2">
                <span class="text-slate-400 font-medium">Beneficiary Name</span>
                <span class="font-bold text-slate-800 dark:text-slate-200">{{ $user->name }}</span>
            </div>
            <div class="flex justify-between border-b border-slate-100 dark:border-slate-850 pb-2">
                <span class="text-slate-400 font-medium">Citizen Email</span>
                <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $user->email }}</span>
            </div>
            <div class="flex justify-between border-b border-slate-100 dark:border-slate-850 pb-2">
                <span class="text-slate-400 font-medium">Reference ID</span>
                <span class="font-mono font-bold text-slate-800 dark:text-slate-200">#PAT-{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400 font-medium">Registration Mode</span>
                <span class="font-black text-rose-600 dark:text-rose-400 uppercase tracking-widest text-[9px]">Alternative Verification</span>
            </div>
        </div>

        {{-- Advisory Box --}}
        <div class="text-xs text-slate-550 dark:text-slate-400 leading-relaxed text-center px-2">
            This entry pass authorizes the citizen to get vaccinated offline. The health hub administrator must physically verify their photo ID and enter their 12-digit Aadhaar card details on the Admin Dashboard to complete official verification.
        </div>

        @auth
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.appointments.index', ['search' => $user->email]) }}" 
                   class="block w-full py-3 bg-gradient-to-r from-sky-500 to-blue-600 text-white font-bold rounded-2xl hover:from-sky-600 hover:to-blue-700 transition-all shadow-md text-xs uppercase tracking-wider">
                    Go to Admin Verification Form
                </a>
            @else
                <a href="{{ route('user.dashboard') }}" 
                   class="block w-full py-3 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-slate-700 font-bold rounded-2xl transition-all text-xs uppercase tracking-wider">
                    Go to Dashboard
                </a>
            @endif
        @else
            <a href="{{ route('login') }}" 
               class="block w-full py-3 bg-gradient-to-r from-sky-500 to-blue-600 text-white font-bold rounded-2xl hover:from-sky-600 hover:to-blue-700 transition-all shadow-md text-xs uppercase tracking-wider">
                Log In to VacciCare
            </a>
        @endauth
    </div>
</div>
@endsection
