@extends('layouts.app')

@section('title', 'Aadhaar Exemption & Vaccine Entry Pass')

@section('content')
<div class="max-w-md mx-auto px-4 py-12 transition-colors duration-200 no-print">
    {{-- Go Back Action --}}
    <div class="mb-6 flex justify-between items-center">
        <a href="{{ route('user.dashboard') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 font-semibold cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            Back to Dashboard
        </a>
        <button onclick="window.print()" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline cursor-pointer">Print Entry Pass</button>
    </div>

    {{-- Aadhaar Exemption Pass Card --}}
    <div class="relative bg-gradient-to-br from-rose-900 to-[#1e0e15] dark:from-[#271118] dark:to-[#0c0307] text-white rounded-[32px] p-6 shadow-2xl overflow-hidden border border-white/10 select-none hover-shimmer transform transition-transform hover:scale-[1.01]">
        
        {{-- Card Header --}}
        <div class="flex items-center justify-between border-b border-white/10 pb-4 mb-6">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-rose-500/20 border border-rose-500/30 rounded-xl flex items-center justify-center">
                    <span class="text-rose-400 font-black text-sm">印</span>
                </div>
                <div>
                    <h1 class="text-xs font-black tracking-wider uppercase leading-none text-white/90">VacciCare</h1>
                    <span class="text-[8px] font-bold text-slate-400 leading-none">Government of India</span>
                </div>
            </div>
            <span class="px-2.5 py-0.5 text-[9px] font-black bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-full flex items-center gap-1 uppercase tracking-wider">
                <span class="w-1.5 h-1.5 rounded-full bg-rose-400 animate-pulse"></span>
                Exempted Entry
            </span>
        </div>

        {{-- Main Body Info --}}
        <div class="space-y-6">
            {{-- Big Exemption Header --}}
            <div class="text-center bg-white/5 border border-white/5 p-4 rounded-2xl relative">
                <h2 class="text-lg font-black text-white leading-none tracking-tight">Vaccine Registration Pass</h2>
                <p class="text-[9px] text-rose-400 mt-1.5 font-bold uppercase tracking-wider">Aadhaar Online Verification Exempted</p>
                
                {{-- Advisory Stamp --}}
                <div class="mt-3.5 inline-block bg-rose-500/10 border border-rose-500/20 text-rose-400 px-3 py-1 rounded-xl text-[10px] font-bold uppercase tracking-wider">
                    Show at Vaccination Gate
                </div>
            </div>

            {{-- Beneficiary Details --}}
            <div class="grid grid-cols-2 gap-4 text-xs pt-1">
                <div>
                    <span class="text-slate-400 font-medium block">Citizen Name</span>
                    <span class="font-extrabold text-white mt-1 block text-sm">{{ $user->name }}</span>
                </div>
                <div>
                    <span class="text-slate-400 font-medium block">Reference ID</span>
                    <span class="font-mono font-bold text-slate-200 mt-1 block text-sm">#PAT-{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div>
                    <span class="text-slate-400 font-medium block">Date of Birth</span>
                    <span class="font-bold text-slate-200 mt-1 block text-sm">{{ $user->dob ? $user->dob->format('d M Y') : 'Not Set' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 font-medium block">Verification Link</span>
                    <span class="font-bold text-rose-400 mt-1 block text-xs">Alternative Photo ID Req.</span>
                </div>
            </div>

            {{-- Instructions for the Staff --}}
            <div class="border-t border-white/10 pt-5 space-y-3">
                <div class="bg-white/5 p-3.5 rounded-xl text-[10px] leading-relaxed text-slate-350">
                    <span class="font-extrabold text-white block uppercase tracking-wider mb-1 text-[9px]">Staff Checklist & Process:</span>
                    1. Verify this citizen's physical Identity Card (e.g. Voter ID, PAN, Driving License).<br>
                    2. Administer the vaccine dose.<br>
                    3. Locate appointment on the Admin Dashboard.<br>
                    4. Input the citizen's 12-digit Aadhaar number, check "Mark Verified", and save.
                </div>
            </div>

            {{-- Mock Scanner QR Code --}}
            <div class="flex flex-col items-center gap-2 pt-2">
                <div class="w-28 h-28 bg-white rounded-2xl p-2 flex items-center justify-center shadow-lg">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ urlencode(route('user.profile')) }}" 
                         alt="Exemption Pass QR Code" 
                         class="w-full h-full object-contain" />
                </div>
                <span class="text-[8px] font-black text-slate-500 tracking-widest uppercase">Admin Profile Registration Scan</span>
            </div>
        </div>
        
        {{-- Card security note --}}
        <div class="mt-6 border-t border-white/5 pt-4 text-center">
            <p class="text-[8px] text-slate-500 leading-normal max-w-[280px] mx-auto">
                This pass is issued under alternative verification guidelines. Physical ID verification is mandatory at the hub.
            </p>
        </div>
    </div>

    {{-- Explanatory Banner --}}
    <div class="mt-6 bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900/30 rounded-2xl p-4 text-xs text-amber-800 dark:text-amber-300 space-y-2">
        <p class="font-extrabold text-sm flex items-center gap-1.5">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            Why am I seeing this?
        </p>
        <p class="leading-relaxed">
            Online booking is restricted to verified Aadhaar profiles to prevent slot hoarding and duplicate registration records.
        </p>
        <p class="leading-relaxed">
            If you do not have an Aadhaar card or are having trouble with the online OTP verification, please **print or save this Entry Pass** and take it to your nearest health hub. The staff will register and verify your details offline.
        </p>
    </div>
</div>

<style>
    /* Styling rules for printing mobile card */
    @media print {
        body {
            background: white !important;
            color: black !important;
        }
        .no-print, header, footer, nav, .fixed {
            display: none !important;
        }
        main {
            padding-top: 0 !important;
            margin: 0 !important;
        }
        .max-w-md {
            max-w: 100% !important;
            width: 100% !important;
            padding: 0 !important;
        }
        .rounded-\[32px\] {
            border-radius: 16px !important;
        }
        .bg-gradient-to-br {
            background: #271118 !important;
            color: white !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>
@endsection
