@extends('layouts.app')

@section('title', $vaccine->name)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Back Link --}}
    <div class="mb-6">
        <a href="{{ route('user.vaccines.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Available Vaccines
        </a>
    </div>

    {{-- Detailed Card --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        {{-- Header block --}}
        <div class="bg-gradient-to-br from-sky-400 to-blue-600 p-8 text-white relative">
            <div class="absolute top-6 right-6">
                <span class="@if($vaccine->status === 'available') bg-emerald-400/90 @else bg-rose-400/90 @endif text-xs font-bold px-3 py-1.5 rounded-full capitalize shadow-sm">
                    {{ $vaccine->status }}
                </span>
            </div>
            <h1 class="text-3xl font-extrabold">{{ $vaccine->name }}</h1>
            <p class="text-sky-100 font-medium mt-1">Manufactured by {{ $vaccine->manufacturer }}</p>
        </div>

        {{-- Content block --}}
        <div class="p-8">
            <h2 class="text-lg font-bold text-slate-800 mb-3">About this Vaccine</h2>
            <p class="text-slate-600 leading-relaxed">{{ $vaccine->description }}</p>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-8">
                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Doses Required</p>
                    <p class="text-xl font-bold text-slate-800">{{ $vaccine->doses_required }} Dose(s)</p>
                </div>
                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Interval Between Doses</p>
                    <p class="text-xl font-bold text-slate-800">
                        @if($vaccine->days_between_doses > 0)
                            {{ $vaccine->days_between_doses }} Days
                        @else
                            Not Applicable
                        @endif
                    </p>
                </div>
                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Price per Dose</p>
                    <p class="text-xl font-bold @if($vaccine->price > 0) text-slate-800 @else text-emerald-600 @endif">
                        @if($vaccine->price > 0)
                            ₹{{ number_format($vaccine->price, 2) }}
                        @else
                            Free of Charge
                        @endif
                    </p>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <span class="text-xs font-medium text-slate-400 uppercase tracking-wider block">Current Stock</span>
                    <span class="text-lg font-bold @if($vaccine->stock < 20) text-rose-600 @else text-slate-700 @endif">{{ $vaccine->stock }} units available</span>
                </div>
                <div>
                    @if($vaccine->stock > 0 && $vaccine->status === 'available')
                        <a href="{{ route('user.appointments.create', ['vaccine_id' => $vaccine->id]) }}"
                           class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-sky-500 to-blue-600 text-white font-bold rounded-xl hover:from-sky-600 hover:to-blue-700 transition-all shadow-md shadow-blue-200">
                            Book Appointment Now
                        </a>
                    @else
                        <button disabled class="px-6 py-3 bg-slate-100 text-slate-400 font-bold rounded-xl cursor-not-allowed">
                            Out of Stock
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
