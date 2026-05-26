@extends('layouts.app')
@section('title', 'Edit Vaccine')
@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <a href="{{ route('admin.vaccines.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 mb-3 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to vaccines
        </a>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Edit Vaccine: {{ $vaccine->name }}</h1>
    </div>
    <div class="bg-white dark:bg-[#151c2c] rounded-2xl border border-slate-100 dark:border-slate-800/80 shadow-md p-8 animate-fade-in-up transition-colors duration-200">
        @if($errors->any())
            <div class="mb-6 bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-900/30 rounded-xl p-4">
                <ul class="text-sm text-rose-600 dark:text-rose-400 space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 bg-rose-500 rounded-full shrink-0"></span>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('admin.vaccines.update', $vaccine) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.vaccines._form')
            <div class="flex gap-3 mt-6">
                <a href="{{ route('admin.vaccines.index') }}" class="flex-1 text-center py-3 border border-slate-200 dark:border-slate-800 text-slate-650 dark:text-slate-400 font-semibold rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/40 text-sm transition-all duration-200 active:scale-98">Cancel</a>
                <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-600 hover:to-blue-700 text-white font-semibold rounded-xl text-sm transition-all duration-200 active:scale-98 hover:shadow-lg hover:shadow-blue-500/15">Update Vaccine</button>
            </div>
        </form>
    </div>
</div>
@endsection
