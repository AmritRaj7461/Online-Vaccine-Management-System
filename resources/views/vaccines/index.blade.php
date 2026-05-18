@extends('layouts.app')

@section('title', 'Available Vaccines')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 transition-colors duration-200">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Available Vaccines</h1>
        <p class="text-slate-500 dark:text-slate-400 mt-1">Browse all available vaccines and book an appointment slot today.</p>
    </div>

    {{-- Vaccine Grid --}}
    @if($vaccines->isEmpty())
        <div class="text-center py-16">
            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 transition-colors">
                <svg class="w-8 h-8 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-slate-500 dark:text-slate-400 font-medium">No vaccines available at the moment.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($vaccines as $vaccine)
                <div class="bg-white dark:bg-[#151c2c] rounded-2xl border border-slate-100 dark:border-slate-800/80 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 overflow-hidden group flex flex-col justify-between">

                    <div>
                        {{-- Card Header --}}
                        <div class="h-32 bg-gradient-to-br from-sky-400 to-blue-600 dark:from-sky-500 dark:to-blue-700 relative flex items-center justify-center">
                            <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                            <div class="absolute top-3 right-3">
                                <span class="@if($vaccine->status === 'available') bg-emerald-400/90 dark:bg-emerald-500/90 @else bg-rose-400/90 dark:bg-rose-500/90 @endif text-white text-xs font-semibold px-2.5 py-1 rounded-full capitalize">
                                    {{ $vaccine->status }}
                                </span>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="p-5">
                            <h3 class="font-bold text-slate-800 dark:text-white text-lg leading-tight">{{ $vaccine->name }}</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">by {{ $vaccine->manufacturer }}</p>

                            <p class="text-sm text-slate-600 dark:text-slate-300 mt-3 line-clamp-2 leading-relaxed">{{ $vaccine->description }}</p>

                            {{-- Details --}}
                            <div class="grid grid-cols-2 gap-3 mt-4">
                                <div class="bg-slate-50 dark:bg-slate-900/40 rounded-xl p-3 text-center transition-colors">
                                    <p class="text-xs text-slate-500 dark:text-slate-500 mb-0.5 font-medium uppercase tracking-wider">Doses</p>
                                    <p class="font-bold text-slate-700 dark:text-slate-200">{{ $vaccine->doses_required }}</p>
                                </div>
                                <div class="bg-slate-50 dark:bg-slate-900/40 rounded-xl p-3 text-center transition-colors">
                                    <p class="text-xs text-slate-500 dark:text-slate-500 mb-0.5 font-medium uppercase tracking-wider">Stock</p>
                                    <p class="font-bold @if($vaccine->stock < 20) text-rose-600 dark:text-rose-400 @else text-slate-700 dark:text-slate-200 @endif">{{ $vaccine->stock }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-5 pb-5">
                        @if($vaccine->price > 0)
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">₹{{ number_format($vaccine->price, 2) }} per dose</p>
                        @else
                            <p class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">Free of charge</p>
                        @endif

                        <a href="{{ route('user.appointments.create', ['vaccine_id' => $vaccine->id]) }}"
                           class="mt-4 block text-center py-2.5 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-600 hover:to-blue-700 text-white text-sm font-semibold rounded-xl transition-all shadow-sm active:scale-98">
                            Book Appointment
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $vaccines->links() }}
        </div>
    @endif
</div>
@endsection
