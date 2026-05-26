@extends('layouts.app')

@section('title', 'Verify Certificate')

@section('content')
<div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-12 transition-colors duration-200">
    <div class="bg-white dark:bg-[#151c2c] rounded-3xl border border-slate-100 dark:border-slate-800 shadow-xl p-8 relative overflow-hidden transition-all duration-200">
        
        {{-- Success State --}}
        @if($success)
            {{-- Green Top Accenting --}}
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-emerald-400 to-teal-500"></div>

            {{-- Pulsing Success Icon --}}
            <div class="flex flex-col items-center text-center mb-6 pt-2">
                <div class="w-20 h-20 bg-emerald-100 dark:bg-emerald-950/30 border border-emerald-250 dark:border-emerald-800 rounded-full flex items-center justify-center mb-4 relative">
                    <span class="absolute inset-0 rounded-full bg-emerald-400/20 dark:bg-emerald-500/10 animate-ping-slow"></span>
                    <svg class="w-10 h-10 text-emerald-650 dark:text-emerald-405 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h1 class="text-xl font-extrabold text-slate-800 dark:text-white leading-none">Certificate Verified</h1>
                <p class="text-xs text-slate-400 dark:text-slate-450 mt-2 font-medium">Digital Immunization Signature Valid & Authenticated</p>
                <div class="mt-3.5 inline-flex items-center gap-1 bg-emerald-100 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-400 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">
                    ✓ Official MOHFW Record
                </div>
            </div>

            {{-- Verified Information Grid --}}
            <div class="space-y-6 mt-6 border-t border-slate-100 dark:border-slate-800 pt-6">
                {{-- Beneficiary Info --}}
                <div class="space-y-4">
                    <h3 class="text-xs uppercase font-extrabold text-slate-400 tracking-wider">Beneficiary Profile</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm bg-slate-50 dark:bg-slate-900/30 p-4 rounded-2xl border border-transparent dark:border-slate-800/40">
                        <div>
                            <p class="text-[10px] uppercase font-bold text-slate-400">Citizen Name</p>
                            <p class="font-extrabold text-slate-800 dark:text-white mt-0.5">{{ $appointment->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-slate-400">Aadhaar (KYC)</p>
                            <p class="font-bold text-slate-700 dark:text-slate-300 mt-0.5">
                                {{ $appointment->user->aadhar_number ? 'XXXX-XXXX-' . substr($appointment->user->aadhar_number, -4) : 'Not Verified' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Vaccine Info --}}
                <div class="space-y-4">
                    <h3 class="text-xs uppercase font-extrabold text-slate-400 tracking-wider">Immunization Record</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm bg-slate-50 dark:bg-slate-900/30 p-4 rounded-2xl border border-transparent dark:border-slate-800/40">
                        <div>
                            <p class="text-[10px] uppercase font-bold text-slate-400">Vaccine Brand</p>
                            <p class="font-extrabold text-emerald-600 dark:text-emerald-400 mt-0.5">{{ $appointment->vaccine->name }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-slate-400">Dose Completed</p>
                            <p class="font-bold text-slate-800 dark:text-white mt-0.5">Dose {{ $appointment->dose_number }} of {{ $appointment->vaccine->doses_required }}</p>
                        </div>
                        <div class="col-span-2 border-t border-slate-200/50 dark:border-slate-800/50 pt-2.5">
                            <p class="text-[10px] uppercase font-bold text-slate-400">Vaccination Center</p>
                            <p class="font-semibold text-slate-800 dark:text-white mt-0.5 leading-snug">{{ $appointment->center->name }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $appointment->center->city }}, {{ $appointment->center->state }}</p>
                        </div>
                        <div class="col-span-2 border-t border-slate-200/50 dark:border-slate-800/50 pt-2.5">
                            <p class="text-[10px] uppercase font-bold text-slate-400">Date of Immunization</p>
                            <p class="font-bold text-slate-800 dark:text-white mt-0.5">{{ $appointment->appointment_date->format('d F Y') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Security Seal stamp --}}
                <div class="flex items-center justify-between bg-emerald-500/5 border border-emerald-500/10 p-3.5 rounded-2xl text-xs text-slate-500 dark:text-slate-400">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Verified on: {{ now()->format('d M Y, h:i A') }}</span>
                    </div>
                    <span class="font-mono text-[9px] font-black text-emerald-600 dark:text-emerald-450 tracking-wider uppercase bg-emerald-500/10 dark:bg-emerald-950/40 px-2 py-0.5 rounded border border-emerald-500/10">Active Check</span>
                </div>
            </div>

        {{-- Fail State --}}
        @else
            {{-- Red Top Accenting --}}
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-rose-500 to-red-600"></div>

            {{-- Pulsing Warning Icon --}}
            <div class="flex flex-col items-center text-center mb-6 pt-2">
                <div class="w-20 h-20 bg-rose-100 dark:bg-rose-950/30 border border-rose-250 dark:border-rose-800 rounded-full flex items-center justify-center mb-4 relative">
                    <span class="absolute inset-0 rounded-full bg-rose-450/20 dark:bg-rose-500/10 animate-ping-slow"></span>
                    <svg class="w-10 h-10 text-rose-600 dark:text-rose-405 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h1 class="text-xl font-extrabold text-slate-800 dark:text-white leading-none">Verification Failed</h1>
                <p class="text-xs text-rose-500 dark:text-rose-400 mt-2 font-semibold">Security Signature Mismatch / invalid Link</p>
            </div>

            {{-- Warning text --}}
            <div class="bg-rose-50 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-900/30 p-5 rounded-2xl text-center text-sm text-rose-700 dark:text-rose-400 font-semibold leading-relaxed mb-6">
                {{ $message }}
            </div>
            
            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed text-center">
                Vaccination records are digitally signed and timestamped. Modifying or using unauthorized URL routes triggers security signature verification flags. If you scanned a valid QR code, please contact our support desk.
            </p>
        @endif

        {{-- Footer Back action --}}
        <div class="mt-8 border-t border-slate-100 dark:border-slate-800 pt-6">
            <a href="{{ route('login') }}" 
               class="block text-center py-3 bg-slate-50 dark:bg-slate-900/40 hover:bg-slate-100 dark:hover:bg-slate-800/60 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 font-bold rounded-2xl transition-all text-sm cursor-pointer active:scale-98">
                Return to Portal
            </a>
        </div>
    </div>
</div>

<style>
    .animate-ping-slow {
        animation: ping-slow 2s cubic-bezier(0, 0, 0.2, 1) infinite;
    }
    @keyframes ping-slow {
        75%, 100% {
            transform: scale(1.6);
            opacity: 0;
        }
    }
</style>
@endsection
