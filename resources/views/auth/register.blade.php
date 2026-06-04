@extends('layouts.app')

@section('title', 'Register Workshop')
@section('seo-title', 'Register Your Workshop')
@section('seo-description', 'Contact Suhaim Soft to register your auto repair shop or garage and get your administrator login.')
@section('seo-keywords', 'workshop registration, sign up garage, register auto repair shop, Suhaim Soft registration, workshop software signup')

@section('content')
    {{-- Brand Header --}}
    <div class="flex flex-col items-center mb-8 animate-fade-in-up">
        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary-600 to-indigo-500 flex items-center justify-center shadow-lg shadow-primary-500/25 mb-4">
            <svg class="w-8 h-8 text-white animate-spin-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="animation-duration: 8s;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight leading-none">Suhaim Soft</h1>
        <p class="text-xs text-primary-600 font-bold tracking-wider uppercase mt-1">Workshop Manager</p>
    </div>

    {{-- Contact Super Admin Card --}}
    <div class="bg-white border border-slate-200/80 rounded-[32px] p-8 sm:p-10 shadow-2xl relative backdrop-blur-md max-w-lg w-full mx-auto animate-fade-in-up" style="animation-delay: 0.1s;">
        <!-- Back to Login -->
        <a href="{{ route('login') }}" class="absolute top-5 left-5 p-2 rounded-xl text-slate-400 hover:text-slate-900 hover:bg-slate-50 transition-all flex items-center justify-center group" title="Back to Login">
            <svg class="w-5 h-5 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        
        <div class="text-center space-y-6 pt-6">
            <!-- Icon -->
            <div class="w-16 h-16 rounded-[22px] bg-violet-50 border border-violet-100 flex items-center justify-center text-violet-600 mx-auto shadow-sm">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                </svg>
            </div>

            <!-- Headers -->
            <div class="space-y-2">
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Register Your Workshop</h2>
                <p class="text-sm text-slate-500 font-semibold leading-relaxed">
                    To register your workshop profile and administrator login, please connect to Super Admin Suhaim Soft.
                </p>
            </div>

            <!-- Premium Call Box -->
            <a href="tel:8891479505" class="bg-violet-50/80 hover:bg-violet-100/60 border border-violet-100/50 rounded-2xl p-4 flex items-center justify-between gap-3 text-left transition-all duration-300 hover:scale-[1.01] group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-violet-600 flex items-center justify-center text-white shrink-0 shadow-sm animate-pulse">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-violet-500 font-bold uppercase tracking-wider">Super Admin Support</p>
                        <p class="text-sm font-extrabold text-violet-950">+91 8891479505</p>
                    </div>
                </div>
                <span class="bg-violet-600 hover:bg-violet-700 text-white font-extrabold text-xs py-2.5 px-4 rounded-xl transition-all shadow-sm active:scale-95 whitespace-nowrap">
                    Call 8891479505
                </span>
            </a>

            <!-- Footer Return Link -->
            <div class="pt-4 border-t border-slate-100 flex justify-center">
                <a href="{{ route('login') }}" class="text-xs font-bold text-slate-400 hover:text-primary-600 transition-colors">
                    Return to Sign In
                </a>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <p class="text-center text-xs text-slate-400 mt-8">© {{ date('Y') }} Suhaim Soft. Secure Garage Ecosystem.</p>
@endsection
