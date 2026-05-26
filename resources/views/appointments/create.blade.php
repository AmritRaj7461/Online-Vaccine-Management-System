@extends('layouts.app')

@section('title', 'Book Appointment')

@section('content')
@php
    $vaccinesJson = $vaccines->map(function($v) {
        return [
            'id' => $v->id,
            'name' => $v->name,
            'manufacturer' => $v->manufacturer,
            'days_between_doses' => $v->days_between_doses,
            'doses_required' => $v->doses_required,
            'price' => $v->price,
            'stock' => $v->stock,
        ];
    })->toJson();

    $dateSlots = [];
    for ($i = 0; $i < 7; $i++) {
        $d = now()->addDays($i);
        $dateSlots[] = [
            'value' => $d->toDateString(),
            'day' => $d->format('D'),
            'num' => $d->format('d'),
            'month' => $d->format('M'),
            'label' => $d->format('d M Y'),
        ];
    }
    $dateSlotsJson = json_encode($dateSlots);

    $morningSlots = ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00'];
    $afternoonSlots = ['14:00', '14:30', '15:00', '15:30', '16:00', '16:30'];
@endphp

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 transition-colors duration-200"
     x-data="{ 
         selectedVaccineId: '{{ old('vaccine_id', $selectedVaccine?->id) }}', 
         vaccines: {{ $vaccinesJson }},
         selectedDate: '{{ old('appointment_date', date('Y-m-d')) }}',
         dateSlots: {{ $dateSlotsJson }},
         selectedTime: '{{ old('appointment_time') }}',
         selectedCenterId: '{{ old('center_id') }}'
     }">

    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('user.appointments.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-505 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 transition-colors mb-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            Back to appointments
        </a>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Book an Appointment</h1>
        <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium">Fill in the details to schedule your vaccination slot.</p>
    </div>

    {{-- Form Container --}}
    <div class="bg-white dark:bg-[#151c2c] rounded-2xl border border-slate-100 dark:border-slate-800/80 shadow-sm p-8 transition-colors duration-200">

        @if($errors->any())
            <div class="mb-6 bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-900/30 rounded-xl p-4">
                <p class="text-sm font-bold text-rose-700 dark:text-rose-400 mb-2">Please fix the following errors:</p>
                <ul class="text-sm text-rose-650 dark:text-rose-400 space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 bg-rose-500 rounded-full shrink-0"></span>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('user.appointments.store') }}" id="booking-form">
            @csrf

            {{-- Vaccine Selection --}}
            <div class="mb-5">
                <label for="vaccine_id" class="block text-sm font-semibold text-slate-800 dark:text-slate-200 mb-1.5">
                    Select Vaccine <span class="text-rose-550">*</span>
                </label>
                <select name="vaccine_id" id="vaccine_id" required x-model="selectedVaccineId"
                    class="w-full px-4 py-3 bg-slate-50 dark:bg-[#0b0f19] border @error('vaccine_id') border-rose-400 bg-rose-50 dark:bg-rose-950/20 @else border-slate-200 dark:border-slate-800 @enderror rounded-xl text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all cursor-pointer">
                    <option value="" class="dark:bg-[#0b0f19]">-- Choose a vaccine --</option>
                    @foreach($vaccines as $vaccine)
                        <option value="{{ $vaccine->id }}" class="dark:bg-[#0b0f19]">
                            {{ $vaccine->name }} ({{ $vaccine->manufacturer }}) · {{ $vaccine->doses_required }} dose(s)
                            @if($vaccine->price > 0) · ₹{{ $vaccine->price }} @else · Free @endif
                        </option>
                    @endforeach
                </select>
                @error('vaccine_id')
                    <p class="mt-1 text-xs text-rose-600 dark:text-rose-450 font-semibold">{{ $message }}</p>
                @enderror

                {{-- Interactive Vaccine Info Preview Card --}}
                <div x-show="selectedVaccineId" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" class="mt-3">
                    <div x-data="{ get vaccine() { return vaccines.find(v => v.id == selectedVaccineId) } }">
                        <template x-if="vaccine">
                            <div class="p-4 bg-blue-50/40 dark:bg-blue-950/15 border border-blue-100/60 dark:border-blue-900/30 rounded-xl space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest">Vaccine Profile</span>
                                    <span class="text-[10px] font-black px-2 py-0.5 rounded-md bg-emerald-100/70 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-500/10" x-text="vaccine.stock > 0 ? 'Allocated (' + vaccine.stock + ' left)' : 'Out of Stock'"></span>
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs mt-2 pt-2 border-t border-blue-100/50 dark:border-blue-900/20">
                                    <div>
                                        <span class="text-slate-400 dark:text-slate-500">Manufacturer</span>
                                        <span class="font-bold text-slate-800 dark:text-slate-200 mt-0.5 block" x-text="vaccine.manufacturer"></span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 dark:text-slate-500">Required Doses</span>
                                        <span class="font-bold text-slate-800 dark:text-slate-200 mt-0.5 block" x-text="vaccine.doses_required + ' Dose(s)'"></span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 dark:text-slate-500">Dose Interval</span>
                                        <span class="font-bold text-slate-800 dark:text-slate-200 mt-0.5 block" x-text="vaccine.days_between_doses + ' Days'"></span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 dark:text-slate-500">Price Details</span>
                                        <span class="font-bold text-slate-800 dark:text-slate-200 mt-0.5 block" x-text="vaccine.price > 0 ? '₹' + vaccine.price : 'Free / Govt Subs.'"></span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Center Selection --}}
            <div class="mb-5">
                <label for="center_id" class="block text-sm font-semibold text-slate-800 dark:text-slate-200 mb-1.5">
                    Select Vaccination Center <span class="text-rose-550">*</span>
                </label>
                <select name="center_id" id="center_id" required x-model="selectedCenterId"
                    class="w-full px-4 py-3 bg-slate-50 dark:bg-[#0b0f19] border @error('center_id') border-rose-400 bg-rose-50 dark:bg-rose-950/20 @else border-slate-200 dark:border-slate-800 @enderror rounded-xl text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all cursor-pointer">
                    <option value="" class="dark:bg-[#0b0f19]">-- Choose a center --</option>
                    @foreach($centers as $center)
                        <option value="{{ $center->id }}" class="dark:bg-[#0b0f19]">
                            {{ $center->name }} — {{ $center->city }}, {{ $center->state }}
                        </option>
                    @endforeach
                </select>
                @error('center_id')
                    <p class="mt-1 text-xs text-rose-600 dark:text-rose-455 font-semibold">{{ $message }}</p>
                @enderror

                {{-- Interactive Map Widget --}}
                <div class="mt-4 bg-slate-50 dark:bg-slate-900/35 p-4 border border-slate-200/60 dark:border-slate-800/40 rounded-2xl">
                    <span class="text-[9px] uppercase font-black text-slate-400 dark:text-slate-500 tracking-wider block mb-2.5">Interactive Geographic Hub Map (Click pins to select center)</span>
                    
                    <div class="relative w-full aspect-[2/1] bg-slate-100 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden flex items-center justify-center shadow-inner">
                        {{-- Background Map Silhouette --}}
                        <svg class="absolute inset-0 w-full h-full text-slate-200 dark:text-slate-800/25 pointer-events-none" viewBox="0 0 400 200" fill="currentColor">
                            <path d="M50,40 Q80,20 120,40 T200,60 T280,30 T350,70 L380,150 Q300,180 200,160 T80,180 Z" />
                            <path d="M300,110 Q320,90 350,110 T380,130" stroke="currentColor" stroke-width="1.5" fill="none" opacity="0.15" />
                        </svg>

                        {{-- Dynamic map pins representing active centers --}}
                        @php
                            $coords = [
                                0 => ['x' => 120, 'y' => 70],
                                1 => ['x' => 240, 'y' => 90],
                                2 => ['x' => 160, 'y' => 130],
                                3 => ['x' => 290, 'y' => 70],
                                4 => ['x' => 80, 'y' => 120],
                            ];
                        @endphp
                        @foreach($centers as $index => $center)
                            @php
                                $coord = $coords[$index % 5];
                            @endphp
                            <button type="button" 
                                    @click="selectedCenterId = '{{ $center->id }}'"
                                    class="absolute w-8 h-8 flex items-center justify-center transition-all duration-300 focus:outline-none cursor-pointer group"
                                    style="left: {{ $coord['x'] }}px; top: {{ $coord['y'] }}px; transform: translate(-50%, -50%);"
                                    :class="selectedCenterId == '{{ $center->id }}' ? 'z-20 scale-110' : 'z-10 hover:scale-105'">
                                
                                {{-- Pulsing indicator ring --}}
                                <span class="absolute w-6 h-6 rounded-full transition-all duration-300"
                                      :class="selectedCenterId == '{{ $center->id }}' ? 'bg-blue-500/30 animate-ping' : 'bg-transparent'"></span>
                                
                                {{-- Pin Icon --}}
                                <svg class="w-5 h-5 transition-colors duration-300" 
                                     :class="selectedCenterId == '{{ $center->id }}' ? 'text-blue-550' : 'text-slate-400 dark:text-slate-600 hover:text-blue-400'"
                                     fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>

                                {{-- Floating Tooltip --}}
                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1.5 hidden group-hover:block bg-slate-900 dark:bg-slate-950 text-white text-[9px] py-1 px-2.5 rounded-lg font-black whitespace-nowrap shadow-md z-30 opacity-95">
                                    {{ $center->name }}
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>   </div>

            {{-- Date Carousel Picker --}}
            <div class="mb-5">
                <label class="block text-sm font-semibold text-slate-800 dark:text-slate-200 mb-2">
                    Select Date <span class="text-rose-550">*</span>
                </label>
                <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-none">
                    <template x-for="slot in dateSlots" :key="slot.value">
                        <button type="button" @click="selectedDate = slot.value"
                                class="flex flex-col items-center justify-center min-w-[76px] py-3.5 px-2.5 rounded-2xl border text-center transition-all cursor-pointer select-none"
                                :class="selectedDate === slot.value ? 
                                        'bg-blue-600 border-blue-600 text-white shadow-md shadow-blue-500/25 scale-[1.02]' : 
                                        'bg-slate-50 dark:bg-[#0b0f19] border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:border-slate-350 dark:hover:border-slate-700'">
                            <span class="text-[9px] uppercase font-black tracking-wider opacity-80" x-text="slot.day"></span>
                            <span class="text-base font-black my-0.5 leading-none" x-text="slot.num"></span>
                            <span class="text-[9px] uppercase font-black tracking-wider opacity-85" x-text="slot.month"></span>
                        </button>
                    </template>
                </div>
                <input type="hidden" name="appointment_date" :value="selectedDate" required />
                @error('appointment_date')
                    <p class="mt-1 text-xs text-rose-600 dark:text-rose-450 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            {{-- Visual Time Slot Grid --}}
            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-800 dark:text-slate-200 mb-2.5">
                    Select Time Slot <span class="text-rose-550">*</span>
                </label>
                
                <div class="space-y-4">
                    {{-- Morning Session --}}
                    <div>
                        <span class="text-[9px] uppercase font-black text-slate-400 dark:text-slate-500 tracking-wider block mb-1.5">Morning Session</span>
                        <div class="grid grid-cols-3 sm:grid-cols-4 gap-2">
                            @foreach($morningSlots as $time)
                                <button type="button" @click="selectedTime = '{{ $time }}'"
                                        class="py-2.5 px-3 rounded-xl border text-center transition-all cursor-pointer text-xs font-bold flex items-center justify-center gap-1.5"
                                        :class="selectedTime === '{{ $time }}' ? 
                                                'bg-blue-650 border-blue-650 text-white shadow-sm shadow-blue-500/20' : 
                                                'bg-slate-50 dark:bg-[#0b0f19] border-slate-200 dark:border-slate-800/80 text-slate-700 dark:text-slate-300 hover:border-slate-350 dark:hover:border-slate-750'">
                                    <svg x-show="selectedTime === '{{ $time }}'" class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    {{ \Carbon\Carbon::parse($time)->format('h:i A') }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                    
                    {{-- Afternoon Session --}}
                    <div>
                        <span class="text-[9px] uppercase font-black text-slate-400 dark:text-slate-500 tracking-wider block mb-1.5">Afternoon Session</span>
                        <div class="grid grid-cols-3 sm:grid-cols-4 gap-2">
                            @foreach($afternoonSlots as $time)
                                <button type="button" @click="selectedTime = '{{ $time }}'"
                                        class="py-2.5 px-3 rounded-xl border text-center transition-all cursor-pointer text-xs font-bold flex items-center justify-center gap-1.5"
                                        :class="selectedTime === '{{ $time }}' ? 
                                                'bg-blue-650 border-blue-650 text-white shadow-sm shadow-blue-500/20' : 
                                                'bg-slate-50 dark:bg-[#0b0f19] border-slate-200 dark:border-slate-800/80 text-slate-700 dark:text-slate-300 hover:border-slate-350 dark:hover:border-slate-750'">
                                    <svg x-show="selectedTime === '{{ $time }}'" class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    {{ \Carbon\Carbon::parse($time)->format('h:i A') }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
                <input type="hidden" name="appointment_time" :value="selectedTime" required />
                @error('appointment_time')
                    <p class="mt-1 text-xs text-rose-600 dark:text-rose-450 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            {{-- Dose Number --}}
            <div class="mb-5">
                <label for="dose_number" class="block text-sm font-semibold text-slate-800 dark:text-slate-200 mb-1.5">
                    Dose Number <span class="text-rose-550">*</span>
                </label>
                <select name="dose_number" id="dose_number" required
                    class="w-full px-4 py-3 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all cursor-pointer">
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" class="dark:bg-[#0b0f19]" {{ old('dose_number', 1) == $i ? 'selected' : '' }}>
                            Dose {{ $i }} {{ $i === 1 ? '(First dose)' : ($i === 2 ? '(Second dose)' : ($i === 3 ? '(Booster)' : '')) }}
                        </option>
                    @endfor
                </select>
            </div>

            {{-- Notes --}}
            <div class="mb-6">
                <label for="notes" class="block text-sm font-semibold text-slate-800 dark:text-slate-200 mb-1.5">Notes <span class="text-slate-400 dark:text-slate-500 font-normal text-xs">(optional)</span></label>
                <textarea name="notes" id="notes" rows="3" placeholder="Any allergies, dietary requirements, or pre-existing conditions..."
                    class="w-full px-4 py-3 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all resize-none">{{ old('notes') }}</textarea>
            </div>

            {{-- Security Info Footer Box --}}
            <div class="flex items-center gap-2.5 text-xs text-slate-400 dark:text-slate-400 mb-6 p-3 bg-slate-50 dark:bg-slate-900/40 border border-transparent dark:border-slate-800/40 rounded-xl transition-colors">
                <svg class="w-4.5 h-4.5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                This request is protected by double-layer CSRF security tokens.
            </div>

            <div class="flex gap-3">
                <a href="{{ route('user.appointments.index') }}"
                   class="flex-1 text-center py-3 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/40 font-semibold rounded-xl transition-all text-sm active:scale-98">
                    Cancel
                </a>
                <button type="submit"
                    class="flex-1 py-3 bg-gradient-to-r from-sky-500 to-blue-600 text-white font-bold rounded-xl hover:from-sky-600 hover:to-blue-700 transition-all shadow-md active:scale-98 text-sm cursor-pointer">
                    Confirm Booking
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Custom style to hide scrollbars for horizontal date picker */
    .scrollbar-none::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-none {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endsection
