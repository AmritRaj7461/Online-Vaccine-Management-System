@extends('layouts.app')

@section('title', 'Vaccination Certificate')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 transition-colors duration-200 no-print">
    {{-- Top Action Bar --}}
    <div class="flex items-center justify-between mb-6 bg-white dark:bg-[#151c2c] border border-slate-100 dark:border-slate-800/80 shadow-sm p-4 rounded-2xl">
        <a href="{{ route('user.appointments.index') }}" 
           class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-850 dark:text-slate-400 dark:hover:text-slate-200 font-semibold cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Bookings
        </a>
        <button onclick="window.print()" 
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-650 hover:from-emerald-600 hover:to-teal-705 text-white text-sm font-bold rounded-xl shadow-md shadow-emerald-500/15 hover:shadow-emerald-500/25 active:scale-98 transition-all cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Print / Save PDF
        </button>
    </div>
</div>

{{-- Main Certificate Print Container --}}
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pb-16 print-container">
    <div class="bg-white text-slate-850 rounded-3xl border-[6px] border-double border-emerald-600/35 shadow-xl p-8 sm:p-12 relative overflow-hidden certificate-border select-none bg-radial-gradient">
        
        {{-- Saffron & Green Accent Lines (Representing official styling) --}}
        <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-[#FF9933] via-white to-[#138808]"></div>
        
        {{-- Watermark Emblem in Background --}}
        <div class="absolute inset-0 opacity-[0.03] pointer-events-none flex items-center justify-center overflow-hidden">
            <svg class="w-[85%] h-[85%] text-slate-900" viewBox="0 0 100 100" fill="currentColor">
                <circle cx="50" cy="50" r="45" stroke="currentColor" stroke-width="2" fill="none"/>
                <path d="M50 20 L60 40 L80 43 L65 57 L70 77 L50 67 L30 77 L35 57 L20 43 L40 40 Z" />
            </svg>
        </div>

        {{-- Official Branding --}}
        <div class="text-center border-b border-slate-200/80 pb-6 mb-8 relative">
            <div class="flex justify-center mb-3">
                <span class="w-12 h-12 bg-emerald-600 text-white rounded-2xl flex items-center justify-center font-bold text-2xl shadow-sm border border-emerald-550">印</span>
            </div>
            <h2 class="text-[10px] sm:text-xs uppercase tracking-widest font-extrabold text-slate-500 leading-none">Ministry of Health & Family Welfare</h2>
            <h1 class="text-xs sm:text-sm font-black text-slate-800 tracking-wide mt-1 leading-none">Government of India</h1>
            <div class="mt-4 inline-flex items-center gap-1.5 bg-emerald-50 border border-emerald-200/60 text-emerald-800 px-3 py-1 rounded-full text-[9px] font-extrabold uppercase tracking-wider">
                <svg class="w-3 h-3 text-emerald-650" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 10a1 1 0 011-1h4a1 1 0 110 2H8a1 1 0 01-1-1zm1-5a1 1 0 000 2h4a1 1 0 100-2H8z" clip-rule="evenodd" />
                </svg>
                Digitally Signed & Verified Secure Record
            </div>
        </div>

        {{-- Certificate Main Titles --}}
        <div class="text-center mb-10">
            <h3 class="text-lg sm:text-xl font-black text-slate-900 tracking-wide">Certificate for Vaccination / टीकाकरण प्रमाण पत्र</h3>
            <p class="text-xs text-slate-500 mt-1.5 font-medium">Issued by VacciCare Vaccine Lifecycle Allocation Registry</p>
        </div>

        {{-- Details Block --}}
        <div class="space-y-8 relative">
            {{-- Category 1: Beneficiary --}}
            <div>
                <h4 class="text-xs uppercase font-extrabold text-slate-400 tracking-wider mb-3.5 border-b border-slate-100 pb-1">Beneficiary Details / लाभार्थी का विवरण</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-8 text-sm">
                    <div>
                        <p class="text-[10px] uppercase font-bold text-slate-400">Beneficiary Name / लाभार्थी का नाम</p>
                        <p class="font-extrabold text-slate-800 text-base mt-0.5">{{ $appointment->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold text-slate-400">Aadhaar Number / आधार संख्या</p>
                        <p class="font-bold text-slate-800 mt-0.5">{{ $appointment->user->aadhar_number ? 'XXXX-XXXX-' . substr($appointment->user->aadhar_number, -4) : 'Not Verified' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold text-slate-400">Age / उम्र</p>
                        <p class="font-bold text-slate-800 mt-0.5">{{ $appointment->user->dob ? $appointment->user->dob->age . ' Years' : 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold text-slate-400">Beneficiary Reference ID / लाभार्थी संदर्भ संख्या</p>
                        <p class="font-mono font-bold text-slate-700 mt-0.5">#PAT-{{ str_pad($appointment->user->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>
            </div>

            {{-- Category 2: Vaccination --}}
            <div>
                <h4 class="text-xs uppercase font-extrabold text-slate-400 tracking-wider mb-3.5 border-b border-slate-100 pb-1">Vaccination Details / टीकाकरण का विवरण</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-8 text-sm">
                    <div>
                        <p class="text-[10px] uppercase font-bold text-slate-400">Vaccine Name / वैक्सीन का नाम</p>
                        <p class="font-extrabold text-emerald-700 text-base mt-0.5">{{ $appointment->vaccine->name }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold text-slate-400">Dose Status / खुराक की स्थिति</p>
                        <p class="font-bold text-slate-800 mt-0.5">
                            Dose {{ $appointment->dose_number }} of {{ $appointment->vaccine->doses_required }} 
                            <span class="text-xs text-emerald-655 font-bold ml-1">
                                ({{ $appointment->dose_number == $appointment->vaccine->doses_required ? 'Fully Vaccinated' : 'Partially Vaccinated' }})
                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold text-slate-400">Date of Dose / खुराक की तारीख</p>
                        <p class="font-bold text-slate-800 mt-0.5">{{ $appointment->appointment_date->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold text-slate-400">Vaccine Manufacturer / वैक्सीन निर्माता</p>
                        <p class="font-bold text-slate-700 mt-0.5">{{ $appointment->vaccine->manufacturer }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-[10px] uppercase font-bold text-slate-400">Vaccination At / टीकाकरण का स्थान</p>
                        <p class="font-extrabold text-slate-800 mt-0.5 leading-snug">{{ $appointment->center->name }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $appointment->center->address }}, {{ $appointment->center->city }}, {{ $appointment->center->state }} - {{ $appointment->center->pincode }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Verification Block & Seal --}}
        <div class="flex flex-col sm:flex-row items-center justify-between border-t border-slate-200/80 pt-6 mt-10 gap-6 relative">
            <div>
                <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Verification Reference Code / सत्यापन संदर्भ कोड</p>
                <p class="font-mono text-xs text-slate-800 mt-0.5 font-bold">
                    C-VMS-{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}-{{ strtoupper(substr(md5($appointment->id), 0, 6)) }}
                </p>
                <p class="text-[10px] text-slate-500 mt-2 max-w-sm leading-relaxed">
                    This certificate is cryptographically secured. Scan the QR code using a verification camera client to validate the active digital signatures of the Ministry of Health and Family Welfare database records.
                </p>
            </div>

            {{-- Real Printable QR Code --}}
            <div class="flex flex-col items-center shrink-0">
                <div class="w-32 h-32 bg-emerald-50/50 rounded-2xl p-2.5 flex items-center justify-center shrink-0 border border-emerald-500/20 shadow-sm relative">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($verificationUrl) }}" 
                         alt="Secure Verification QR Code" 
                         class="w-full h-full object-contain" />
                </div>
                <span class="text-[9px] font-extrabold tracking-wider text-emerald-800 uppercase mt-2">Scan to Verify</span>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling rules for screen view */
    .bg-radial-gradient {
        background-image: radial-gradient(circle at 100% 100%, rgba(16, 185, 129, 0.015) 0%, transparent 80%);
    }

    /* Print styles */
    @media print {
        body {
            background: white !important;
            color: #1e293b !important;
        }
        /* Hide layout items */
        .no-print, header, footer, nav, .fixed {
            display: none !important;
        }
        main {
            padding-top: 0 !important;
            margin: 0 !important;
            animation: none !important;
        }
        .print-container {
            max-w: 100% !important;
            width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .certificate-border {
            border: 4px double #059669 !important;
            box-shadow: none !important;
            border-radius: 0 !important;
            padding: 2.5rem !important;
            background: white !important;
        }
    }
</style>
@endsection
