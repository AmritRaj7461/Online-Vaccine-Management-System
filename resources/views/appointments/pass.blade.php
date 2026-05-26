@extends('layouts.app')

@section('title', 'Digital Immunization Pass')

@section('content')
<div class="max-w-md mx-auto px-4 py-12 transition-colors duration-200">
    {{-- Go Back Action --}}
    <div class="mb-6 flex justify-between items-center no-print">
        <a href="{{ route('user.dashboard') }}" class="inline-flex items-center gap-1 text-sm text-slate-505 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 font-semibold cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            Back
        </a>
        <button onclick="window.print()" class="text-xs font-bold text-emerald-600 dark:text-emerald-450 hover:underline cursor-pointer">Print Pass</button>
    </div>

    {{-- Glassmorphism Mobile Card Pass (Emerald verified theme) --}}
    <div class="relative bg-gradient-to-br from-emerald-900 to-[#061e16] dark:from-[#052216] dark:to-[#020c08] text-white rounded-[32px] p-6 shadow-2xl overflow-hidden border border-white/10 select-none hover-shimmer transform transition-transform hover:scale-[1.01]">
        
        {{-- Card Header --}}
        <div class="flex items-center justify-between border-b border-white/10 pb-4 mb-6">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-emerald-500/20 border border-emerald-500/30 rounded-xl flex items-center justify-center">
                    <span class="text-emerald-405 font-black text-sm">印</span>
                </div>
                <div>
                    <h1 class="text-xs font-black tracking-wider uppercase leading-none text-white/90">Digital Immunization Pass</h1>
                    <span class="text-[8px] font-bold text-slate-400 leading-none">Government of India</span>
                </div>
            </div>
            <span class="px-2.5 py-0.5 text-[9px] font-black bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-full flex items-center gap-1 uppercase tracking-wider">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                Immunized
            </span>
        </div>

        {{-- Main Body Info --}}
        <div class="space-y-6">
            {{-- Big Vaccine Banner --}}
            <div class="text-center bg-white/5 border border-white/5 p-4 rounded-2xl relative">
                <div class="absolute top-2 right-2 text-[9px] font-bold text-emerald-405">Completed</div>
                <h2 class="text-2xl font-black text-white leading-none tracking-tight">{{ $appointment->vaccine->name }}</h2>
                <p class="text-[10px] text-slate-400 mt-1.5 font-bold uppercase tracking-wider">{{ $appointment->vaccine->manufacturer }}</p>
                
                {{-- Dose Badge --}}
                <div class="mt-3.5 inline-block bg-blue-500/10 border border-blue-500/20 text-blue-400 px-3 py-1 rounded-xl text-xs font-bold uppercase tracking-wider">
                    Dose {{ $appointment->dose_number }} of {{ $appointment->vaccine->doses_required }}
                </div>
            </div>

            {{-- Beneficiary Details --}}
            <div class="grid grid-cols-2 gap-4 text-xs pt-1">
                <div>
                    <span class="text-slate-400 font-medium block">Beneficiary Name</span>
                    <span class="font-extrabold text-white mt-1 block text-sm">{{ $appointment->user->name }}</span>
                </div>
                <div>
                    <span class="text-slate-400 font-medium block">Aadhaar KYC</span>
                    <span class="font-bold text-slate-200 mt-1 block text-sm">
                        {{ $appointment->user->aadhar_number ? 'XXXX-XXXX-' . substr($appointment->user->aadhar_number, -4) : 'Not Verified' }}
                    </span>
                </div>
                <div>
                    <span class="text-slate-400 font-medium block">Vaccination Date</span>
                    <span class="font-bold text-slate-200 mt-1 block text-sm">{{ $appointment->appointment_date->format('d M Y') }}</span>
                </div>
                <div>
                    <span class="text-slate-400 font-medium block">Reference ID</span>
                    <span class="font-mono font-bold text-slate-200 mt-1 block text-sm">#PAT-{{ str_pad($appointment->user->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="col-span-2 border-t border-white/5 pt-3">
                    <span class="text-slate-400 font-medium block">Vaccinated At</span>
                    <span class="font-bold text-slate-200 mt-1 block leading-tight text-xs">{{ $appointment->center->name }}</span>
                </div>
            </div>

            {{-- Secure QR Code scan section --}}
            <div class="border-t border-white/10 pt-5 flex flex-col items-center gap-3">
                <div class="w-36 h-36 bg-white rounded-2xl p-2.5 flex items-center justify-center shadow-lg relative">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($verificationUrl) }}" 
                         alt="Verification QR Code" 
                         class="w-full h-full object-contain" />
                </div>
                <span class="text-[9px] font-black text-slate-400 tracking-widest uppercase">Digital Pass QR Code</span>
            </div>
        </div>
        
        {{-- Card footer security disclaimer --}}
        <div class="mt-6 border-t border-white/5 pt-4 text-center">
            <p class="text-[8px] text-slate-500 leading-normal max-w-[280px] mx-auto">
                This digital pass is generated by VacciCare. It is cryptographically linked to active registries of MOHFW.
            </p>
        </div>
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
            background: #111827 !important;
            color: white !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>
@endsection
