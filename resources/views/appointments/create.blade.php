@extends('layouts.app')

@section('title', 'Book Appointment')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 transition-colors duration-200">

    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('user.appointments.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 transition-colors mb-3">
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
                <label for="vaccine_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                    Select Vaccine <span class="text-rose-500">*</span>
                </label>
                <select name="vaccine_id" id="vaccine_id" required
                    class="w-full px-4 py-3 bg-slate-50 dark:bg-[#0b0f19] border @error('vaccine_id') border-rose-400 bg-rose-50 dark:bg-rose-950/20 @else border-slate-200 dark:border-slate-800 @enderror rounded-xl text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all">
                    <option value="" class="dark:bg-[#0b0f19]">-- Choose a vaccine --</option>
                    @foreach($vaccines as $vaccine)
                        <option value="{{ $vaccine->id }}" class="dark:bg-[#0b0f19]"
                            {{ (old('vaccine_id', $selectedVaccine?->id) == $vaccine->id) ? 'selected' : '' }}>
                            {{ $vaccine->name }} ({{ $vaccine->manufacturer }}) · {{ $vaccine->doses_required }} dose(s)
                            @if($vaccine->price > 0) · ₹{{ $vaccine->price }} @else · Free @endif
                        </option>
                    @endforeach
                </select>
                @error('vaccine_id')
                    <p class="mt-1 text-xs text-rose-600 dark:text-rose-400 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            {{-- Center Selection --}}
            <div class="mb-5">
                <label for="center_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                    Select Vaccination Center <span class="text-rose-500">*</span>
                </label>
                <select name="center_id" id="center_id" required
                    class="w-full px-4 py-3 bg-slate-50 dark:bg-[#0b0f19] border @error('center_id') border-rose-400 bg-rose-50 dark:bg-rose-950/20 @else border-slate-200 dark:border-slate-800 @enderror rounded-xl text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all">
                    <option value="" class="dark:bg-[#0b0f19]">-- Choose a center --</option>
                    @foreach($centers as $center)
                        <option value="{{ $center->id }}" class="dark:bg-[#0b0f19]" {{ old('center_id') == $center->id ? 'selected' : '' }}>
                            {{ $center->name }} — {{ $center->city }}, {{ $center->state }}
                        </option>
                    @endforeach
                </select>
                @error('center_id')
                    <p class="mt-1 text-xs text-rose-600 dark:text-rose-400 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            {{-- Date & Time --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                <div>
                    <label for="appointment_date" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Appointment Date <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="appointment_date" id="appointment_date"
                        value="{{ old('appointment_date') }}"
                        min="{{ date('Y-m-d') }}"
                        required
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-[#0b0f19] border @error('appointment_date') border-rose-400 bg-rose-50 dark:bg-rose-950/20 @else border-slate-200 dark:border-slate-800 @enderror rounded-xl text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all"/>
                    @error('appointment_date')
                        <p class="mt-1 text-xs text-rose-600 dark:text-rose-400 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="appointment_time" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Preferred Time Slot <span class="text-rose-500">*</span>
                    </label>
                    <select name="appointment_time" id="appointment_time" required
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-[#0b0f19] border @error('appointment_time') border-rose-400 bg-rose-50 dark:bg-rose-950/20 @else border-slate-200 dark:border-slate-800 @enderror rounded-xl text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all">
                        <option value="" class="dark:bg-[#0b0f19]">-- Select time --</option>
                        @foreach(['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30'] as $time)
                            <option value="{{ $time }}" class="dark:bg-[#0b0f19]" {{ old('appointment_time') === $time ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::parse($time)->format('h:i A') }}
                            </option>
                        @endforeach
                    </select>
                    @error('appointment_time')
                        <p class="mt-1 text-xs text-rose-600 dark:text-rose-400 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Dose Number --}}
            <div class="mb-5">
                <label for="dose_number" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                    Dose Number <span class="text-rose-500">*</span>
                </label>
                <select name="dose_number" id="dose_number" required
                    class="w-full px-4 py-3 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all">
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" class="dark:bg-[#0b0f19]" {{ old('dose_number', 1) == $i ? 'selected' : '' }}>
                            Dose {{ $i }} {{ $i === 1 ? '(First dose)' : ($i === 2 ? '(Second dose)' : ($i === 3 ? '(Booster)' : '')) }}
                        </option>
                    @endfor
                </select>
            </div>

            {{-- Notes --}}
            <div class="mb-6">
                <label for="notes" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Notes <span class="text-slate-400 dark:text-slate-500 font-normal text-xs">(optional)</span></label>
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
@endsection
