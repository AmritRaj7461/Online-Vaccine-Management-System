@extends('layouts.app')

@section('title', 'My Dashboard')

@section('content')
@php
    // Calculate milestone variables dynamically
    $completedAppts = $allAppointments->where('status', 'completed')->values();
    $scheduledAppts = $allAppointments->whereIn('status', ['pending', 'confirmed'])->values();
    
    $dose1Status = 'not_booked'; // not_booked, scheduled, completed
    $dose1Appt = null;
    $dose2Status = 'locked'; // locked, not_booked, scheduled, completed
    $dose2Appt = null;
    $isFullyImmunized = false;
    $activeVaccine = null;

    if ($completedAppts->count() >= 1) {
        $dose1Status = 'completed';
        $dose1Appt = $completedAppts[0];
        $activeVaccine = $dose1Appt->vaccine;
        
        if ($activeVaccine->doses_required > 1) {
            $dose2Status = 'not_booked';
            
            if ($completedAppts->count() >= 2) {
                $dose2Status = 'completed';
                $dose2Appt = $completedAppts[1];
                $isFullyImmunized = true;
            } elseif ($scheduledAppts->count() > 0) {
                // Find if there is a scheduled dose 2
                $schedDose2 = $scheduledAppts->first(function($a) use ($dose1Appt) {
                    return $a->id !== $dose1Appt->id && $a->dose_number > 1;
                });
                if ($schedDose2) {
                    $dose2Status = 'scheduled';
                    $dose2Appt = $schedDose2;
                }
            }
        } else {
            $isFullyImmunized = true;
            $dose2Status = 'not_required';
        }
    } elseif ($scheduledAppts->count() >= 1) {
        $dose1Status = 'scheduled';
        $dose1Appt = $scheduledAppts[0];
        $activeVaccine = $dose1Appt->vaccine;
    }

    $dose1Url = $dose1Appt ? \Illuminate\Support\Facades\URL::signedRoute('verify.certificate', ['appointment' => $dose1Appt->id]) : '';
    $dose2Url = $dose2Appt ? \Illuminate\Support\Facades\URL::signedRoute('verify.certificate', ['appointment' => $dose2Appt->id]) : '';
    $dose1PrintUrl = $dose1Appt ? route('user.appointments.certificate', ['appointment' => $dose1Appt->id]) : '';
    $dose2PrintUrl = $dose2Appt ? route('user.appointments.certificate', ['appointment' => $dose2Appt->id]) : '';
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 transition-colors duration-200" 
     x-data="{ certificateOpen: false, selectedCertDose: null, selectedCertDate: '', selectedCertName: '', selectedCertCenter: '', selectedCertRef: '', selectedCertAadhar: '{{ auth()->user()->aadhar_number ? 'XXXX-XXXX-' . substr(auth()->user()->aadhar_number, -4) : 'Not Verified' }}', selectedCertQrUrl: '', selectedCertPrintUrl: '' }">

    {{-- Welcome Header with animation --}}
    <div class="mb-8 animate-fade-in-up">
        <h1 class="text-3xl font-extrabold text-slate-800 dark:text-white">Good {{ date('H') < 12 ? 'morning' : (date('H') < 17 ? 'afternoon' : 'evening') }}, {{ auth()->user()->name }}! 👋</h1>
        <p class="text-slate-500 dark:text-slate-400 mt-1">Track your vaccination status, bookings, and retrieve digital certificates.</p>
    </div>

    {{-- Stats Cards with stagger animation --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
        {{-- Card 1: Total Bookings --}}
        <div class="bg-white dark:bg-[#151c2c] rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm p-6 flex items-center gap-4 transition-all duration-300 hover:shadow-md animate-fade-in-up hover-shimmer delay-100">
            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-950/40 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-slate-800 dark:text-white leading-none"
                   x-data="{ current: 0, target: {{ $totalAppointments }} }"
                   x-init="let end = target; if (end > 0) { let duration = 800; let step = Math.ceil(end / (duration / 16)); let timer = setInterval(() => { current += step; if (current >= end) { current = end; clearInterval(timer); } }, 16); } else { current = 0; }"
                   x-text="current">
                    {{ $totalAppointments }}
                </p>
                <p class="text-xs font-semibold text-slate-400 dark:text-slate-400 mt-1.5 uppercase tracking-wider">Total Bookings</p>
            </div>
        </div>

        {{-- Card 2: Pending --}}
        <div class="bg-white dark:bg-[#151c2c] rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm p-6 flex items-center gap-4 transition-all duration-300 hover:shadow-md animate-fade-in-up hover-shimmer delay-200">
            <div class="w-12 h-12 bg-amber-100 dark:bg-amber-950/40 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-amber-600 dark:text-amber-400 animate-pulse-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-slate-800 dark:text-white leading-none"
                   x-data="{ current: 0, target: {{ $pendingAppointments }} }"
                   x-init="let end = target; if (end > 0) { let duration = 800; let step = Math.ceil(end / (duration / 16)); let timer = setInterval(() => { current += step; if (current >= end) { current = end; clearInterval(timer); } }, 16); } else { current = 0; }"
                   x-text="current">
                    {{ $pendingAppointments }}
                </p>
                <p class="text-xs font-semibold text-slate-400 dark:text-slate-400 mt-1.5 uppercase tracking-wider">Pending Approvals</p>
            </div>
        </div>

        {{-- Card 3: Completed --}}
        <div class="bg-white dark:bg-[#151c2c] rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm p-6 flex items-center gap-4 transition-all duration-300 hover:shadow-md animate-fade-in-up hover-shimmer delay-300">
            <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-950/40 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-slate-800 dark:text-white leading-none"
                   x-data="{ current: 0, target: {{ $completedAppointments }} }"
                   x-init="let end = target; if (end > 0) { let duration = 800; let step = Math.ceil(end / (duration / 16)); let timer = setInterval(() => { current += step; if (current >= end) { current = end; clearInterval(timer); } }, 16); } else { current = 0; }"
                   x-text="current">
                    {{ $completedAppointments }}
                </p>
                <p class="text-xs font-semibold text-slate-400 dark:text-slate-400 mt-1.5 uppercase tracking-wider">Immunization Completed</p>
            </div>
        </div>
    </div>

    {{-- Layout Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left & Center Column: Timeline & Upcoming --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Interactive Vaccination Timeline Card --}}
            <div class="bg-white dark:bg-[#151c2c] rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm p-6 animate-fade-in-up delay-100">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div>
                        <h2 class="font-bold text-slate-800 dark:text-white text-lg">My Vaccination Journey</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Real-time status of your COVID-19 immunization lifecycle</p>
                    </div>
                    @if($isFullyImmunized)
                        <span class="inline-flex items-center gap-1.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            Fully Immunized
                        </span>
                    @endif
                </div>

                {{-- Milestone Steps --}}
                <div class="relative pl-6 border-l-2 border-slate-150 dark:border-slate-800 space-y-8 ml-3">
                    
                    {{-- Step 1: Account Created --}}
                    <div class="relative">
                        <span class="absolute -left-[35px] top-0 w-6 h-6 bg-emerald-500 text-white rounded-full flex items-center justify-center border-4 border-white dark:border-[#151c2c] shadow-sm select-none">
                            ✓
                        </span>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 dark:text-white">Account Registration Completed</h3>
                            <p class="text-xs text-slate-600 dark:text-slate-300 mt-0.5">Profile set up on {{ auth()->user()->created_at->format('d M Y') }}.</p>
                        </div>
                    </div>

                    {{-- Step 2: Aadhaar Verified --}}
                    <div class="relative">
                        @if(auth()->user()->aadhar_verified)
                            <span class="absolute -left-[35px] top-0 w-6 h-6 bg-emerald-500 text-white rounded-full flex items-center justify-center border-4 border-white dark:border-[#151c2c] shadow-sm select-none">
                                ✓
                            </span>
                            <div>
                                <h3 class="text-sm font-bold text-slate-800 dark:text-white">Aadhaar e-KYC Verification</h3>
                                <p class="text-xs text-slate-600 dark:text-slate-300 mt-0.5">Identity verified using UIDAI biometric registries.</p>
                            </div>
                        @else
                            <span class="absolute -left-[35px] top-0 w-6 h-6 bg-amber-500 text-white rounded-full flex items-center justify-center border-4 border-white dark:border-[#151c2c] shadow-sm animate-pulse select-none">
                                !
                            </span>
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <h3 class="text-sm font-bold text-amber-600 dark:text-amber-400">Aadhaar Verification Pending</h3>
                                    <p class="text-xs text-slate-600 dark:text-slate-300 mt-0.5">Please verify your Aadhaar to book online, or print the vaccine center entry pass.</p>
                                </div>
                                <div class="flex gap-2 sm:self-start shrink-0">
                                    <a href="{{ route('user.profile') }}" class="bg-amber-500 hover:bg-amber-600 text-white px-3.5 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm transition-colors text-center shrink-0">
                                        Verify e-KYC
                                    </a>
                                    <a href="{{ route('user.aadhar.exemption-pass') }}" class="bg-rose-605 hover:bg-rose-700 text-white px-3.5 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm transition-colors text-center shrink-0">
                                        Get Entry Pass 📄
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Step 3: Dose 1 --}}
                    <div class="relative">
                        @if($dose1Status === 'completed')
                            <span class="absolute -left-[35px] top-0 w-6 h-6 bg-emerald-500 text-white rounded-full flex items-center justify-center border-4 border-white dark:border-[#151c2c] shadow-sm select-none">
                                ✓
                            </span>
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <h3 class="text-sm font-bold text-slate-800 dark:text-white">Dose 1: Immunized</h3>
                                    <p class="text-xs text-slate-600 dark:text-slate-300 mt-0.5">
                                        Received **{{ $dose1Appt->vaccine->name }}** on {{ $dose1Appt->appointment_date->format('d M Y') }} at {{ $dose1Appt->center->name }}.
                                    </p>
                                </div>
                                <div class="flex flex-wrap gap-2 sm:self-start shrink-0">
                                    <button type="button" 
                                            @click="selectedCertDose = 1; selectedCertDate = '{{ $dose1Appt->appointment_date->format('d/m/Y') }}'; selectedCertName = '{{ $dose1Appt->vaccine->name }}'; selectedCertCenter = '{{ $dose1Appt->center->name }}'; selectedCertRef = '#TXN-VACC-{{ str_pad($dose1Appt->id, 6, '0', STR_PAD_LEFT) }}'; selectedCertQrUrl = '{{ $dose1Url }}'; selectedCertPrintUrl = '{{ $dose1PrintUrl }}'; certificateOpen = true"
                                            class="bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-800 px-3.5 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm transition-all flex items-center gap-1.5 cursor-pointer">
                                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        Certificate
                                    </button>
                                    <a href="{{ route('user.appointments.pass', $dose1Appt) }}" 
                                       class="bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-800 px-3.5 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm transition-all flex items-center gap-1.5 cursor-pointer">
                                        💳 Wallet Pass
                                    </a>
                                    <a href="{{ route('user.appointments.wellness', $dose1Appt) }}" 
                                       class="bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-800 px-3.5 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm transition-all flex items-center gap-1.5 cursor-pointer">
                                        🤒 Side-Effects
                                    </a>
                                </div>
                            </div>
                        @elseif($dose1Status === 'scheduled')
                            <span class="absolute -left-[35px] top-0 w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center border-4 border-white dark:border-[#151c2c] shadow-sm select-none">
                                ●
                            </span>
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <h3 class="text-sm font-bold text-blue-600 dark:text-blue-400">Dose 1: Scheduled</h3>
                                    <p class="text-xs text-slate-600 dark:text-slate-300 mt-0.5">
                                        Booked for **{{ $dose1Appt->vaccine->name }}** on {{ $dose1Appt->appointment_date->format('d M Y') }} at {{ \Carbon\Carbon::parse($dose1Appt->appointment_time)->format('h:i A') }} ({{ $dose1Appt->center->name }}).
                                    </p>
                                </div>
                                <a href="{{ route('user.appointments.queue', $dose1Appt) }}" 
                                   class="sm:self-start bg-blue-500 hover:bg-blue-600 text-white px-3.5 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm transition-all flex items-center gap-1.5 shrink-0 cursor-pointer text-center">
                                    🚦 Queue Tracker
                                </a>
                            </div>
                        @else
                            <span class="absolute -left-[35px] top-0 w-6 h-6 bg-slate-350 dark:bg-slate-700 text-white rounded-full flex items-center justify-center border-4 border-white dark:border-[#151c2c] shadow-sm select-none">
                                3
                            </span>
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <h3 class="text-sm font-bold text-slate-500 dark:text-slate-400">Dose 1: Not Booked</h3>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Please book your initial vaccination dose to begin protection.</p>
                                </div>
                                @if(auth()->user()->aadhar_verified)
                                    <a href="{{ route('user.appointments.create') }}" class="sm:self-start bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-600 hover:to-blue-700 text-white px-4 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm transition-all shrink-0">
                                        Book Dose 1
                                    </a>
                                @else
                                    <a href="{{ route('user.aadhar.exemption-pass') }}" class="sm:self-start bg-rose-605 hover:bg-rose-700 text-white px-4 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm transition-all shrink-0">
                                        Get Entry Pass 📄
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- Step 4: Dose 2 --}}
                    <div class="relative">
                        @if($dose2Status === 'completed')
                            <span class="absolute -left-[35px] top-0 w-6 h-6 bg-emerald-500 text-white rounded-full flex items-center justify-center border-4 border-white dark:border-[#151c2c] shadow-sm select-none">
                                ✓
                            </span>
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <h3 class="text-sm font-bold text-slate-800 dark:text-white">Dose 2: Fully Protected</h3>
                                    <p class="text-xs text-slate-600 dark:text-slate-300 mt-0.5">
                                        Received **{{ $dose2Appt->vaccine->name }}** on {{ $dose2Appt->appointment_date->format('d M Y') }} at {{ $dose2Appt->center->name }}.
                                    </p>
                                </div>
                                <div class="flex flex-wrap gap-2 sm:self-start shrink-0">
                                    <button type="button" 
                                            @click="selectedCertDose = 2; selectedCertDate = '{{ $dose2Appt->appointment_date->format('d/m/Y') }}'; selectedCertName = '{{ $dose2Appt->vaccine->name }}'; selectedCertCenter = '{{ $dose2Appt->center->name }}'; selectedCertRef = '#TXN-VACC-{{ str_pad($dose2Appt->id, 6, '0', STR_PAD_LEFT) }}'; selectedCertQrUrl = '{{ $dose2Url }}'; selectedCertPrintUrl = '{{ $dose2PrintUrl }}'; certificateOpen = true"
                                            class="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white px-3.5 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm transition-all flex items-center gap-1.5 cursor-pointer">
                                        <svg class="w-3.5 h-3.5 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        Final Certificate
                                    </button>
                                    <a href="{{ route('user.appointments.pass', $dose2Appt) }}" 
                                       class="bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-800 px-3.5 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm transition-all flex items-center gap-1.5 cursor-pointer">
                                        💳 Wallet Pass
                                    </a>
                                    <a href="{{ route('user.appointments.wellness', $dose2Appt) }}" 
                                       class="bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-800 px-3.5 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm transition-all flex items-center gap-1.5 cursor-pointer">
                                        🤒 Side-Effects
                                    </a>
                                </div>
                            </div>
                        @elseif($dose2Status === 'scheduled')
                            <span class="absolute -left-[35px] top-0 w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center border-4 border-white dark:border-[#151c2c] shadow-sm select-none">
                                ●
                            </span>
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <h3 class="text-sm font-bold text-blue-600 dark:text-blue-400">Dose 2: Scheduled</h3>
                                    <p class="text-xs text-slate-600 dark:text-slate-300 mt-0.5">
                                        Booked for **{{ $dose2Appt->vaccine->name }}** on {{ $dose2Appt->appointment_date->format('d M Y') }} at {{ \Carbon\Carbon::parse($dose2Appt->appointment_time)->format('h:i A') }} ({{ $dose2Appt->center->name }}).
                                    </p>
                                </div>
                                <a href="{{ route('user.appointments.queue', $dose2Appt) }}" 
                                   class="sm:self-start bg-blue-500 hover:bg-blue-600 text-white px-3.5 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm transition-all flex items-center gap-1.5 shrink-0 cursor-pointer text-center">
                                    🚦 Queue Tracker
                                </a>
                            </div>
                        @elseif($dose2Status === 'not_booked')
                            <span class="absolute -left-[35px] top-0 w-6 h-6 bg-slate-350 dark:bg-slate-700 text-white rounded-full flex items-center justify-center border-4 border-white dark:border-[#151c2c] shadow-sm select-none">
                                4
                            </span>
                            @php
                                $eligibleDate = $dose1Appt->appointment_date->addDays($activeVaccine->days_between_doses);
                                $isEligibleNow = now()->toDateString() >= $eligibleDate->toDateString();
                            @endphp
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <h3 class="text-sm font-bold text-slate-750 dark:text-slate-200">Dose 2: Not Booked</h3>
                                    <p class="text-xs text-slate-600 dark:text-slate-300 mt-0.5">
                                        Requires **{{ $activeVaccine->name }}** ({{ $activeVaccine->days_between_doses }} days gap). 
                                        Eligible from: <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $eligibleDate->format('d M Y') }}</span>.
                                    </p>
                                </div>
                                @if($isEligibleNow)
                                    @if(auth()->user()->aadhar_verified)
                                        <a href="{{ route('user.appointments.create', ['vaccine_id' => $activeVaccine->id]) }}" class="sm:self-start bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-600 hover:to-blue-700 text-white px-4 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm transition-all shrink-0">
                                            Book Dose 2
                                        </a>
                                    @else
                                        <a href="{{ route('user.aadhar.exemption-pass') }}" class="sm:self-start bg-rose-605 hover:bg-rose-700 text-white px-4 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm transition-all shrink-0">
                                            Get Entry Pass 📄
                                        </a>
                                    @endif
                                @else
                                    <span class="sm:self-start bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider border border-slate-200 dark:border-slate-800 select-none shrink-0 cursor-not-allowed">
                                        Soon
                                    </span>
                                @endif
                            </div>
                        @elseif($dose2Status === 'not_required')
                            <span class="absolute -left-[35px] top-0 w-6 h-6 bg-slate-300 dark:bg-slate-700 text-slate-500 rounded-full flex items-center justify-center border-4 border-white dark:border-[#151c2c] shadow-sm select-none">
                                ✓
                            </span>
                            <div>
                                <h3 class="text-sm font-semibold text-slate-500 dark:text-slate-400">Dose 2: Not Required</h3>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Completed single-dose protection protocol.</p>
                            </div>
                        @else
                            <span class="absolute -left-[35px] top-0 w-6 h-6 bg-slate-200 dark:bg-slate-800 text-slate-400 rounded-full flex items-center justify-center border-4 border-white dark:border-[#151c2c] shadow-sm select-none">
                                4
                            </span>
                            <div>
                                <h3 class="text-sm font-semibold text-slate-600 dark:text-slate-400">Dose 2: Locked</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Unlock by completing Dose 1 immunization first.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Upcoming Appointments --}}
            <div class="bg-white dark:bg-[#151c2c] rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md animate-fade-in-up delay-200">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800">
                    <h2 class="font-bold text-slate-800 dark:text-white">Scheduled Appointments</h2>
                    <a href="{{ route('user.appointments.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline font-medium">View all →</a>
                </div>

                @if($upcomingAppointments->isEmpty())
                    <div class="flex flex-col items-center justify-center py-12 text-center px-6">
                        <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800/50 rounded-full flex items-center justify-center mb-4 transition-colors">
                            <svg class="w-8 h-8 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-slate-500 dark:text-slate-400 font-medium">No upcoming appointments scheduled</p>
                        @if(auth()->user()->aadhar_verified)
                            <a href="{{ route('user.appointments.create') }}" class="mt-4 px-5 py-2.5 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-600 hover:to-blue-700 text-white text-sm font-semibold rounded-xl transition-all shadow-sm">
                                Book Appointment
                            </a>
                        @endif
                    </div>
                @else
                    <div class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach($upcomingAppointments as $appointment)
                            <div class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                                <div class="w-10 h-10 bg-gradient-to-br from-sky-100 to-blue-100 dark:from-sky-950/20 dark:to-blue-950/20 rounded-xl flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-slate-800 dark:text-white text-sm truncate leading-tight">{{ $appointment->vaccine->name }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $appointment->center->name }} · Dose {{ $appointment->dose_number }}</p>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ $appointment->appointment_date->format('d M Y') }}</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                                </div>
                                <span class="bg-amber-100 dark:bg-amber-950/30 text-amber-700 dark:text-amber-400 text-xs font-semibold px-2.5 py-1 rounded-full capitalize shrink-0 ml-2">
                                    {{ $appointment->status }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Right Column: Quick Actions & Sidebar Links --}}
        <div class="space-y-4 animate-fade-in-up delay-200">
            {{-- Quick book banner --}}
            <div class="bg-gradient-to-br from-sky-500 to-blue-600 rounded-2xl p-6 text-white shadow-md relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 w-28 h-28 bg-white/5 rounded-full blur-xl group-hover:scale-110 transition-transform duration-500 pointer-events-none"></div>
                <div class="relative z-10">
                    <h3 class="font-bold text-lg mb-1 leading-none">Ready to vaccinate?</h3>
                    <p class="text-sky-100 text-xs mt-2.5 leading-relaxed">Book vaccine allocations easily at municipal health hubs close to your address.</p>
                    @if(auth()->user()->aadhar_verified)
                        <a href="{{ route('user.appointments.create') }}"
                           class="block text-center bg-white text-blue-600 font-bold py-2.5 rounded-xl hover:bg-sky-50 transition-all text-sm mt-5 shadow-sm active:scale-98">
                            + Book Slot Now
                        </a>
                    @else
                        <button disabled class="w-full text-center bg-white/30 text-white/90 font-bold py-2.5 rounded-xl text-sm mt-5 cursor-not-allowed select-none opacity-60">
                            Aadhaar Required
                        </button>
                    @endif
                </div>
            </div>

            {{-- Quick Links Card --}}
            <div class="bg-white dark:bg-[#151c2c] rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm p-5 transition-all duration-300 hover:shadow-md">
                <h3 class="font-semibold text-slate-800 dark:text-white mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('user.vaccines.index') }}" class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors text-sm text-slate-600 dark:text-slate-300 font-medium group">
                        <div class="w-8 h-8 bg-sky-100 dark:bg-sky-950/40 rounded-lg flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-4 h-4 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        </div>
                        Browse Vaccines
                    </a>
                    <a href="{{ route('user.appointments.index') }}" class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors text-sm text-slate-600 dark:text-slate-300 font-medium group">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-950/40 rounded-lg flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        My Bookings
                    </a>
                    <a href="{{ route('user.profile') }}" class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors text-sm text-slate-600 dark:text-slate-300 font-medium group">
                        <div class="w-8 h-8 bg-emerald-100 dark:bg-emerald-950/40 rounded-lg flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        Profile & e-KYC
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Digital Vaccination Certificate Modal Overlay --}}
    <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-sm"
         x-show="certificateOpen"
         x-transition.opacity
         style="display: none;">
        
        <div class="relative max-w-2xl w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-850 rounded-3xl p-6 shadow-2xl transition-all transform flex flex-col justify-between"
             x-show="certificateOpen"
             x-transition.scale
             @click.away="certificateOpen = false">
            
            {{-- Header close button (no-print) --}}
            <button @click="certificateOpen = false" 
                    class="absolute top-4 right-4 p-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-500 dark:text-slate-400 rounded-full transition-colors no-print">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            {{-- The Certificate Block to Print --}}
            <div id="print-certificate" class="bg-white text-slate-800 p-6 sm:p-8 rounded-2xl border-4 border-emerald-600/30 shadow-inner relative select-none">
                
                {{-- Decorative Emblem Watermark --}}
                <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.05] pointer-events-none flex items-center justify-center overflow-hidden">
                    <span class="text-[18rem] font-bold">印</span>
                </div>

                {{-- Certificate Header --}}
                <div class="text-center border-b border-slate-200 pb-5 mb-6 relative">
                    <div class="w-12 h-12 bg-emerald-600 rounded-xl flex items-center justify-center text-white text-2xl font-black shadow-md mx-auto mb-2 select-none">印</div>
                    <h2 class="text-xs uppercase tracking-widest font-black text-slate-500 leading-none">Ministry of Health & Family Welfare</h2>
                    <h1 class="text-sm font-extrabold text-slate-800 tracking-wide mt-1 leading-none">Government of India</h1>
                    <div class="mt-3 inline-flex items-center gap-1.5 bg-emerald-100 border border-emerald-200 text-emerald-800 px-3.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider">
                        ✓ Secured Digitally Signed
                    </div>
                </div>

                <div class="text-center mb-8">
                    <h3 class="text-lg font-black text-slate-900 tracking-wide">Certificate for Immunization / टीकाकरण प्रमाण पत्र</h3>
                    <p class="text-xs text-slate-500 mt-1">Issued by VacciCare Vaccine Allocation and Lifecycle Registry</p>
                </div>

                {{-- Metadata Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4 text-sm mb-8 relative">
                    
                    {{-- Left column --}}
                    <div class="space-y-4">
                        <div>
                            <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Beneficiary Name / लाभार्थी का नाम</p>
                            <p class="font-bold text-slate-800 text-base mt-0.5">{{ auth()->user()->name }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Aadhaar Number / आधार संख्या</p>
                            <p class="font-bold text-slate-800 mt-0.5" x-text="selectedCertAadhar"></p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Beneficiary ID / लाभार्थी पहचान संख्या</p>
                            <p class="font-mono font-bold text-slate-700 mt-0.5">#PAT-{{ str_pad(auth()->user()->id, 5, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>

                    {{-- Right column --}}
                    <div class="space-y-4">
                        <div>
                            <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Vaccine Name / वैक्सीन का नाम</p>
                            <p class="font-bold text-emerald-700 text-base mt-0.5" x-text="selectedCertName"></p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Immunization Dose / खुराक</p>
                            <p class="font-bold text-slate-800 mt-0.5" x-text="'Dose ' + selectedCertDose + ' of ' + (selectedCertDose == 2 ? '2' : 'Completed')"></p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Date of Vaccination / टीकाकरण की तिथि</p>
                            <p class="font-bold text-slate-800 mt-0.5" x-text="selectedCertDate"></p>
                        </div>
                    </div>

                    <div class="sm:col-span-2 border-t border-slate-100 pt-4 mt-2">
                        <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Vaccinated At / टीकाकरण स्थान</p>
                        <p class="font-bold text-slate-800 mt-0.5" x-text="selectedCertCenter"></p>
                    </div>
                </div>

                {{-- Security QR Code & Seal section --}}
                <div class="flex flex-col sm:flex-row items-center justify-between border-t border-slate-200 pt-6 gap-6 relative">
                    <div>
                        <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Verification Reference / सत्यापन संदर्भ</p>
                        <p class="font-mono text-xs text-slate-600 mt-0.5 font-bold" x-text="selectedCertRef"></p>
                        <p class="text-[10px] text-slate-400 mt-2 max-w-sm">Scan the secure QR code using any verification client or portal scanner to fetch and validate the digital signature of this official health record.</p>
                    </div>

                    {{-- Real Printable QR Code --}}
                    <div class="w-24 h-24 bg-emerald-50 rounded-xl p-1.5 flex items-center justify-center shrink-0 border border-emerald-500/20 select-none shadow-sm">
                        <img :src="'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' + encodeURIComponent(selectedCertQrUrl)" 
                             alt="QR Code" 
                             class="w-full h-full object-contain" />
                    </div>
                </div>
            </div>

            {{-- Footer actions (no-print) --}}
            <div class="flex gap-3 mt-6 border-t border-slate-100 dark:border-slate-800 pt-4 no-print">
                <button type="button" @click="certificateOpen = false"
                        class="flex-1 py-3 border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/40 text-slate-500 dark:text-slate-400 rounded-xl text-sm font-semibold transition-all">
                    Dismiss
                </button>
                <a :href="selectedCertPrintUrl" target="_blank"
                   class="flex-1 py-3 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-600 hover:to-blue-700 text-white rounded-xl text-sm font-bold shadow-md shadow-blue-500/15 hover:shadow-blue-500/25 transition-all flex items-center justify-center gap-2 cursor-pointer text-center">
                    <svg class="w-4 h-4 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Print Certificate
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
