@extends('layouts.app')

@section('title', 'Live Queue Status')

@section('content')
<div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-8 transition-colors duration-200">
    
    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('user.dashboard') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 transition-colors mb-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            Back to Dashboard
        </a>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Live Queue Tracker</h1>
        <p class="text-slate-505 dark:text-slate-400 mt-1 text-sm">Real-time crowding and token queue at your vaccination center.</p>
    </div>

    {{-- Queue Tracker Board --}}
    <div class="bg-white dark:bg-[#151c2c] rounded-3xl border border-slate-100 dark:border-slate-800/80 shadow-lg p-6 sm:p-8 space-y-6 transition-colors">
        
        {{-- Health Center Header Details --}}
        <div class="bg-slate-50 dark:bg-slate-900/35 border border-transparent dark:border-slate-800/40 p-4 rounded-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <span class="text-[9px] uppercase font-black text-blue-600 dark:text-blue-400 tracking-wider">Assigned Health Hub</span>
                <h3 class="font-extrabold text-slate-850 dark:text-white text-base mt-0.5">{{ $appointment->center->name }}</h3>
                <p class="text-xs text-slate-550 dark:text-slate-400 mt-0.5">{{ $appointment->center->city }}, {{ $appointment->center->state }}</p>
            </div>
            <div class="sm:text-right shrink-0">
                <span class="text-[9px] uppercase font-black text-slate-400 tracking-wider">Scheduled Date & Time</span>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-200 mt-0.5">{{ $appointment->appointment_date->format('d M Y') }}</p>
                <p class="text-xs text-slate-500 mt-0.5">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
            </div>
        </div>

        {{-- Token Status Panel --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            {{-- Now Serving Token --}}
            <div class="bg-blue-600 rounded-3xl p-6 text-white text-center relative overflow-hidden shadow-md shadow-blue-500/15">
                <span class="absolute top-2.5 left-0 right-0 text-[10px] uppercase font-black tracking-widest text-blue-100">Now Serving</span>
                <div class="text-5xl font-black mt-6 tracking-tight animate-pulse-slow">
                    #TK-{{ str_pad($servingToken, 3, '0', STR_PAD_LEFT) }}
                </div>
                <p class="text-xs text-blue-100 mt-4 font-semibold uppercase tracking-wider">Active Token ID</p>
            </div>

            {{-- User Token --}}
            <div class="bg-slate-50 dark:bg-slate-900/30 border border-slate-100 dark:border-slate-800/60 rounded-3xl p-6 text-center shadow-sm">
                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-widest block">Your Token</span>
                <div class="text-5xl font-black text-slate-800 dark:text-white mt-6 tracking-tight">
                    #TK-{{ str_pad($userToken, 3, '0', STR_PAD_LEFT) }}
                </div>
                <p class="text-xs text-slate-450 dark:text-slate-400 mt-4 font-semibold uppercase tracking-wider">Scheduled Order</p>
            </div>
        </div>

        {{-- Queue Metrics Progression --}}
        <div class="space-y-4 border-t border-slate-100 dark:border-slate-800 pt-6">
            <h4 class="text-xs uppercase font-extrabold text-slate-400 tracking-wider">Real-time Parameters</h4>
            
            <div class="grid grid-cols-3 gap-4 text-center">
                {{-- Metric 1: People Ahead --}}
                <div class="p-4 bg-slate-50/50 dark:bg-slate-900/15 border border-slate-100/50 dark:border-slate-800/40 rounded-2xl">
                    <span class="text-[9px] uppercase font-bold text-slate-400 tracking-wider block">People Ahead</span>
                    <span class="text-2xl font-black text-slate-800 dark:text-white mt-1 block">{{ $peopleAhead }}</span>
                    <span class="text-[9px] text-slate-450 mt-1 block">In queue line</span>
                </div>

                {{-- Metric 2: Estimated Wait --}}
                <div class="p-4 bg-slate-50/50 dark:bg-slate-900/15 border border-slate-100/50 dark:border-slate-800/40 rounded-2xl">
                    <span class="text-[9px] uppercase font-bold text-slate-400 tracking-wider block">Est. Wait</span>
                    <span class="text-2xl font-black text-blue-600 dark:text-blue-400 mt-1 block">{{ $waitTimeMinutes }}m</span>
                    <span class="text-[9px] text-slate-455 mt-1 block">Queue countdown</span>
                </div>

                {{-- Metric 3: Crowd Density --}}
                <div class="p-4 bg-slate-50/50 dark:bg-slate-900/15 border border-slate-100/50 dark:border-slate-800/40 rounded-2xl">
                    <span class="text-[9px] uppercase font-bold text-slate-400 tracking-wider block">Crowd Density</span>
                    @php
                        $densityClasses = [
                            'Low' => 'text-emerald-600 dark:text-emerald-400',
                            'Medium' => 'text-amber-600 dark:text-amber-400',
                            'High' => 'text-rose-600 dark:text-rose-400',
                        ];
                        $dc = $densityClasses[$density] ?? 'text-slate-600';
                    @endphp
                    <span class="text-2xl font-black mt-1 block {{ $dc }}">{{ $density }}</span>
                    <span class="text-[9px] text-slate-455 mt-1 block">Center activity</span>
                </div>
            </div>
        </div>

        {{-- Interactive Live Queue Progress simulation visual --}}
        <div class="border-t border-slate-100 dark:border-slate-800 pt-6">
            <h4 class="text-xs uppercase font-extrabold text-slate-400 tracking-wider mb-3">Live Line Simulation</h4>
            <div class="relative w-full h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                @php
                    $percentage = 100 - ($peopleAhead / $userToken * 100);
                    if ($percentage < 0) $percentage = 0;
                    if ($percentage > 100) $percentage = 100;
                @endphp
                <div class="absolute left-0 top-0 bottom-0 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full transition-all duration-500"
                     style="width: {{ $percentage }}%;"></div>
            </div>
            <div class="flex justify-between items-center text-[10px] text-slate-400 dark:text-slate-500 mt-2 font-bold uppercase tracking-wider">
                <span>Check-in Gate</span>
                <span class="text-blue-600 dark:text-blue-400 font-extrabold">Your Position (TK-{{ $userToken }})</span>
            </div>
        </div>

        {{-- Advisory Notification Box --}}
        <div class="flex gap-3 text-xs text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-900/40 p-4 border border-transparent dark:border-slate-800/40 rounded-2xl">
            <svg class="w-5 h-5 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div class="leading-relaxed">
                <span class="font-extrabold text-slate-700 dark:text-slate-200">Patient Queue Advisory:</span>
                Please arrive at the center **10–15 minutes** before your serving countdown token. Ensure you have your Aadhaar registered details or Booking Confirmation PDF ready for verification gates.
            </div>
        </div>
    </div>
</div>
@endsection
