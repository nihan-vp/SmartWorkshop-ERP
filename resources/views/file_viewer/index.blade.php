@extends('layouts.app')

@section('title', 'File Viewer')
@section('page-title', 'File Viewer')
@section('page-subtitle', 'Browse and inspect database backups')

@section('content')
<div class="w-full max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6 animate-fade-in-up">

    {{-- Alert Messages --}}
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 flex items-start gap-3 shadow-sm">
        <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="text-sm font-semibold">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-xl p-4 flex items-start gap-3 shadow-sm">
        <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="text-sm font-semibold">{{ session('error') }}</span>
    </div>
    @endif

    {{-- Coming Soon Card --}}
    <div class="glass-card shadow-sm border border-slate-200/80 p-6 sm:p-10 lg:p-16 text-center flex flex-col items-center justify-center min-h-[60vh]">

        {{-- Icon --}}
        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl sm:rounded-3xl bg-blue-50 flex items-center justify-center mb-5 animate-bounce" style="animation-duration: 3s;">
            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
        </div>

        {{-- Badge --}}
        <div class="inline-flex items-center gap-2 bg-blue-100 text-blue-700 text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full mb-4">
            <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse inline-block"></span>
            Coming Soon
        </div>

        {{-- Title --}}
        <h3 class="text-xl sm:text-2xl lg:text-3xl font-bold text-slate-800 font-outfit mb-3">File Viewer</h3>

        {{-- Description --}}
        <p class="text-sm sm:text-base text-slate-500 max-w-xs sm:max-w-md leading-relaxed">
            The file viewing and syntax inspector features are currently under development. Check back soon!
        </p>

        {{-- Buttons --}}
        <div class="mt-8 flex flex-col sm:flex-row flex-wrap justify-center gap-3 w-full sm:w-auto">
            <a href="{{ route('backup.index') }}" class="btn-primary shadow-sm py-2.5 px-5 text-xs sm:text-sm flex items-center justify-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                </svg>
                Go to Backup &amp; Restore
            </a>
            <a href="{{ route('dashboard') }}" class="btn-secondary shadow-sm py-2.5 px-5 text-xs sm:text-sm flex items-center justify-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Go to Dashboard
            </a>
        </div>

    </div>

</div>
@endsection
