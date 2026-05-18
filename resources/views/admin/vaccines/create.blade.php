@extends('layouts.app')
@section('title', 'Add Vaccine')
@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <a href="{{ route('admin.vaccines.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-700 mb-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to vaccines
        </a>
        <h1 class="text-2xl font-bold text-slate-800">Add New Vaccine</h1>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8">
        @if($errors->any())
            <div class="mb-6 bg-rose-50 border border-rose-200 rounded-xl p-4">
                <ul class="text-sm text-rose-600 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('admin.vaccines.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.vaccines._form')
            <div class="flex gap-3 mt-6">
                <a href="{{ route('admin.vaccines.index') }}" class="flex-1 text-center py-3 border border-slate-200 text-slate-600 font-semibold rounded-xl hover:bg-slate-50 text-sm">Cancel</a>
                <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-sky-500 to-blue-600 text-white font-semibold rounded-xl hover:from-sky-600 hover:to-blue-700 text-sm">Add Vaccine</button>
            </div>
        </form>
    </div>
</div>
@endsection
