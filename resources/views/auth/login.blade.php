@extends('layouts.app')

@section('title', 'Login')
@section('seo-title', 'Login to Workshop Manager')
@section('seo-description', 'Login to your Suhaim Soft Workshop Manager account to access your dashboard, manage work orders, and handle customers.')
@section('seo-keywords', 'workshop login, garage manager login, auto repair software login, Suhaim Soft login')

@section('content')
    {{-- Brand Header --}}
    <div class="flex flex-col items-center mb-8">
        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary-600 to-indigo-500 flex items-center justify-center shadow-lg shadow-primary-500/25 mb-4">
            <svg class="w-8 h-8 text-white animate-spin-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="animation-duration: 8s;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight leading-none">Suhaim Soft</h1>
        <p class="text-xs text-primary-600 font-bold tracking-wider uppercase mt-1">Workshop Manager</p>
    </div>

    {{-- Login Card --}}
    <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-xl relative backdrop-blur-md max-w-md w-full mx-auto">

        <!-- Back Arrow -->
        <a href="{{ route('landing') }}" class="absolute top-4 left-4 p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-all group" title="Back to Home">
            <svg class="w-5 h-5 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>

        <div class="mb-6 pt-8">
            <h2 class="text-xl font-bold text-slate-900">Welcome Back</h2>
            <p class="text-xs text-slate-400 mt-1">Login to access your workshop dashboard</p>
        </div>



        {{-- Form --}}
        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2H5v10a2 2 0 002 2z"/></svg>
                    </div>
                    <input type="email" name="email" required placeholder="infosuhaimsoft@gmail.com" value="{{ old('email', 'infosuhaimsoft@gmail.com') }}"
                           class="w-full bg-white border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-slate-900 placeholder-slate-400 text-sm transition-all focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                </div>
                @error('email')<p class="text-[10px] text-red-500 mt-1 font-bold">{{ $message }}</p>@enderror
            </div>

            <div x-data="{ show: false }">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <input :type="show ? 'text' : 'password'" name="password" required placeholder="••••••••" value="12345678"
                           class="w-full bg-white border border-slate-200 rounded-xl pl-10 pr-12 py-3 text-slate-900 placeholder-slate-400 text-sm transition-all focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-655 transition-colors">
                        <!-- Eye Icon (when hidden) -->
                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <!-- Eye-Off Icon (when shown) -->
                        <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88L3 3m12 12l6 6"/></svg>
                    </button>
                </div>
                @error('password')<p class="text-[10px] text-red-500 mt-1 font-bold">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center justify-between pt-1">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" name="remember" class="w-4 h-4 text-primary-600 border-slate-200 rounded focus:ring-primary-500/20 cursor-pointer">
                    <span class="text-xs text-slate-500 font-medium">Remember me</span>
                </label>
            </div>

            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 bg-gradient-to-r from-primary-600 to-primary-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-primary-500/10 hover:shadow-primary-500/20 transition-all hover:scale-[1.01] active:scale-95 mt-4">
                Sign In
            </button>
        </form>
        
        <div class="mt-6 pt-4 border-t border-slate-100 text-center">
            <a href="{{ route('register') }}" class="text-xs font-semibold text-primary-600 hover:text-primary-700 transition-colors">
                Don't have a workshop? Register here
            </a>
        </div>
    </div>

    {{-- Footer --}}
    <p class="text-center text-xs text-slate-400 mt-8">© {{ date('Y') }} Suhaim Soft Work Shop. All rights reserved.</p>
@endsection
