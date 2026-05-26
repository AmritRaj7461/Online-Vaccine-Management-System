@extends('layouts.app')

@section('title', 'Wellness Side-Effects Logger')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8 transition-colors duration-200" x-data="{ 
    hasFever: {{ old('fever', $appointment->wellnessLog?->fever ? 'true' : 'false') }},
    hasSoreness: {{ old('soreness', $appointment->wellnessLog?->soreness ? 'true' : 'false') }},
    hasHeadache: {{ old('headache', $appointment->wellnessLog?->headache ? 'true' : 'false') }},
    hasFatigue: {{ old('fatigue', $appointment->wellnessLog?->fatigue ? 'true' : 'false') }}
}">
    
    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('user.dashboard') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-550 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 transition-colors mb-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            Back to Dashboard
        </a>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Wellness & Side-Effects Logger</h1>
        <p class="text-slate-505 dark:text-slate-400 mt-1 text-sm">Report symptoms after your vaccine dose to receive immediate care advice.</p>
    </div>

    {{-- Split Layout: Form and Advice --}}
    <div class="space-y-6">
        
        {{-- Section 1: Logger Form Card --}}
        <div class="bg-white dark:bg-[#151c2c] rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm p-6 sm:p-8 transition-all">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4 mb-6">
                <div>
                    <h3 class="font-extrabold text-slate-850 dark:text-white text-base">Symptom Log Form</h3>
                    <p class="text-xs text-slate-450 dark:text-slate-400 mt-0.5">Dose {{ $appointment->dose_number }} · {{ $appointment->vaccine->name }}</p>
                </div>
                @if($appointment->wellnessLog)
                    <span class="px-2.5 py-0.5 text-[9px] font-black bg-emerald-500/10 border border-emerald-500/20 text-emerald-450 rounded-full flex items-center gap-1 uppercase tracking-wider">
                        ✓ Registered
                    </span>
                @else
                    <span class="px-2.5 py-0.5 text-[9px] font-black bg-amber-500/10 border border-amber-500/20 text-amber-550 rounded-full flex items-center gap-1 uppercase tracking-wider">
                        Pending Log
                    </span>
                @endif
            </div>

            <form method="POST" action="{{ route('user.appointments.wellness.store', $appointment) }}">
                @csrf
                <div class="space-y-4">
                    <span class="text-[10px] uppercase font-black text-slate-400 tracking-wider block">Check all active side-effects</span>
                    
                    {{-- Checkboxes Grid --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        {{-- Fever --}}
                        <label class="flex items-center gap-3 p-3.5 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200/60 dark:border-slate-850 rounded-2xl cursor-pointer hover:border-slate-350 dark:hover:border-slate-750 transition-all select-none">
                            <input type="checkbox" name="fever" value="1" x-model="hasFever"
                                   class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500 cursor-pointer" />
                            <div>
                                <span class="text-xs font-bold text-slate-800 dark:text-white block">Fever / Temperature</span>
                                <span class="text-[10px] text-slate-450 dark:text-slate-500 block">Hot skin, chills, sweating</span>
                            </div>
                        </label>

                        {{-- Soreness --}}
                        <label class="flex items-center gap-3 p-3.5 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200/60 dark:border-slate-850 rounded-2xl cursor-pointer hover:border-slate-350 dark:hover:border-slate-750 transition-all select-none">
                            <input type="checkbox" name="soreness" value="1" x-model="hasSoreness"
                                   class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500 cursor-pointer" />
                            <div>
                                <span class="text-xs font-bold text-slate-800 dark:text-white block">Injection Site Pain</span>
                                <span class="text-[10px] text-slate-450 dark:text-slate-500 block">Arm soreness, arm swelling, redness</span>
                            </div>
                        </label>

                        {{-- Headache --}}
                        <label class="flex items-center gap-3 p-3.5 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200/60 dark:border-slate-850 rounded-2xl cursor-pointer hover:border-slate-350 dark:hover:border-slate-750 transition-all select-none">
                            <input type="checkbox" name="headache" value="1" x-model="hasHeadache"
                                   class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500 cursor-pointer" />
                            <div>
                                <span class="text-xs font-bold text-slate-800 dark:text-white block">Headache / Migraine</span>
                                <span class="text-[10px] text-slate-455 dark:text-slate-505 block">Dull ache, head tension</span>
                            </div>
                        </label>

                        {{-- Fatigue --}}
                        <label class="flex items-center gap-3 p-3.5 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200/60 dark:border-slate-850 rounded-2xl cursor-pointer hover:border-slate-350 dark:hover:border-slate-750 transition-all select-none">
                            <input type="checkbox" name="fatigue" value="1" x-model="hasFatigue"
                                   class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500 cursor-pointer" />
                            <div>
                                <span class="text-xs font-bold text-slate-800 dark:text-white block">Fatigue / Exhaustion</span>
                                <span class="text-[10px] text-slate-455 dark:text-slate-505 block">Feeling tired, joint pains, laziness</span>
                            </div>
                        </label>
                    </div>

                    {{-- Text area details --}}
                    <div>
                        <label for="other_symptoms" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Additional Symptoms Details</label>
                        <textarea name="other_symptoms" id="other_symptoms" rows="3" placeholder="Describe any swelling, rashes, allergic issues, or pre-existing pain..."
                                  class="w-full px-4 py-3 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-800 rounded-2xl text-xs text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all resize-none">{{ old('other_symptoms', $appointment->wellnessLog?->other_symptoms) }}</textarea>
                    </div>

                    <button type="submit"
                            class="w-full py-3.5 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-bold rounded-2xl shadow-md shadow-blue-500/15 hover:shadow-blue-500/25 active:scale-98 transition-all cursor-pointer text-sm">
                        Submit & Save Symptoms Log
                    </button>
                </div>
            </form>
        </div>

        {{-- Section 2: Clinical Advice Card --}}
        <div class="bg-white dark:bg-[#151c2c] rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm p-6 sm:p-8 space-y-6 transition-all"
             x-show="hasFever || hasSoreness || hasHeadache || hasFatigue"
             x-transition:enter="transition ease-out duration-200"
             style="display: none;">
            
            <div class="border-b border-slate-100 dark:border-slate-800 pb-3 mb-2 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                <h3 class="font-extrabold text-slate-800 dark:text-white text-sm uppercase tracking-wider">Automated Medical Guidance</h3>
            </div>

            <div class="space-y-4">
                {{-- Fever Advice --}}
                <div x-show="hasFever" x-transition.fade class="flex gap-3 text-xs bg-slate-50 dark:bg-[#0b0f19] p-4 border border-transparent dark:border-slate-800/40 rounded-2xl">
                    <span class="w-7 h-7 rounded-xl bg-orange-100 dark:bg-orange-950/30 text-orange-600 dark:text-orange-400 font-extrabold flex items-center justify-center shrink-0">🌡</span>
                    <div class="space-y-1">
                        <span class="font-bold text-slate-800 dark:text-white block">Fever Guidance</span>
                        <p class="text-slate-550 dark:text-slate-400 leading-relaxed font-semibold">
                            Fever is a standard sign of the body building protection. Keep hydrated by drinking plenty of water/fluids. You can take Paracetamol (500mg/650mg) under medical consultation to reduce temperature. Avoid cold baths.
                        </p>
                    </div>
                </div>

                {{-- Soreness Advice --}}
                <div x-show="hasSoreness" x-transition.fade class="flex gap-3 text-xs bg-slate-50 dark:bg-[#0b0f19] p-4 border border-transparent dark:border-slate-800/40 rounded-2xl">
                    <span class="w-7 h-7 rounded-xl bg-blue-100 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 font-extrabold flex items-center justify-center shrink-0">💪</span>
                    <div class="space-y-1">
                        <span class="font-bold text-slate-800 dark:text-white block">Arm Pain Guidance</span>
                        <p class="text-slate-550 dark:text-slate-400 leading-relaxed font-semibold">
                            Keep your vaccinated arm moving with gentle stretching exercises to reduce pain. A clean, cool, damp cloth can be placed over the injection site to soothe redness or swelling. Do not rub the site.
                        </p>
                    </div>
                </div>

                {{-- Headache/Fatigue Advice --}}
                <div x-show="hasHeadache || hasFatigue" x-transition.fade class="flex gap-3 text-xs bg-slate-50 dark:bg-[#0b0f19] p-4 border border-transparent dark:border-slate-800/40 rounded-2xl">
                    <span class="w-7 h-7 rounded-xl bg-indigo-100 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400 font-extrabold flex items-center justify-center shrink-0">💤</span>
                    <div class="space-y-1">
                        <span class="font-bold text-slate-800 dark:text-white block">Rest & Recovery Guidance</span>
                        <p class="text-slate-550 dark:text-slate-400 leading-relaxed font-semibold">
                            Avoid strenuous physical work, heavy lifting, or intense exercise for 24–48 hours. Ensure you get 8 hours of sleep. Headaches usually resolve within a day; stay in a quiet, dark room to reduce eye tension.
                        </p>
                    </div>
                </div>

                {{-- Severe Symptoms warning --}}
                <div class="flex gap-3 text-xs bg-rose-50 dark:bg-rose-950/20 border border-rose-100/50 dark:border-rose-900/20 p-4 rounded-2xl">
                    <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <div class="space-y-1">
                        <span class="font-bold text-rose-700 dark:text-rose-400 block">Caution & Consultations</span>
                        <p class="text-rose-650 dark:text-rose-400 leading-relaxed">
                            Minor side-effects are normal. However, if you develop breathing difficulties, chest pains, severe abdominal pain, or if your symptoms persist past **72 hours**, please visit your nearest healthcare facility or call the National Helpline at **1075** immediately.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
