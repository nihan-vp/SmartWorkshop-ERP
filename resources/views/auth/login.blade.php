@extends('layouts.app')

@section('title', 'Login')
@section('seo-title', 'Login to Workshop Manager')
@section('seo-description', 'Login to your Suhaim Soft Workshop Manager account.')

@section('content')
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.15), 0 0 0 1px rgba(255,255,255,0.5) inset;
        }
        .input-glowing:focus-within {
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
            border-color: #3b82f6;
        }
        .floating-shape {
            animation: float 6s ease-in-out infinite;
        }
        .floating-shape-2 {
            animation: float 8s ease-in-out infinite reverse;
        }
        @keyframes float {
            0% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
            100% { transform: translateY(0) rotate(0deg); }
        }
        .fade-in-up {
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(20px);
        }
        @keyframes fadeInUp {
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <div class="relative w-full max-w-5xl mx-auto flex items-center justify-center min-h-[80vh]">
        
        {{-- Background Decorative Elements --}}
        <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none flex items-center justify-center">
            <div class="absolute top-[10%] left-[15%] w-72 h-72 bg-blue-400/30 rounded-full mix-blend-multiply filter blur-[80px] animate-pulse"></div>
            <div class="absolute bottom-[10%] right-[15%] w-72 h-72 bg-indigo-400/30 rounded-full mix-blend-multiply filter blur-[80px] animate-pulse" style="animation-delay: 2s;"></div>
            
            {{-- Floating Geometric Shapes --}}
            <div class="absolute top-20 left-10 w-24 h-24 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-3xl opacity-20 floating-shape blur-[2px] rotate-12"></div>
            <div class="absolute bottom-20 right-10 w-32 h-32 bg-gradient-to-tr from-purple-400 to-blue-500 rounded-full opacity-20 floating-shape-2 blur-[3px]"></div>
        </div>

        {{-- Main Login Container --}}
        <div class="relative z-10 w-full max-w-md mx-auto fade-in-up">
            
            {{-- Brand Header --}}
            <div class="flex flex-col items-center mb-8">
                <div class="relative group cursor-pointer">
                    <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-3xl blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
                    <div class="relative w-24 h-24 rounded-3xl bg-white flex items-center justify-center shadow-2xl shadow-blue-900/10 border border-slate-100 overflow-hidden">
                        <img src="{{ asset('images/logo.png') }}" alt="Suhaim Soft Logo" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    </div>
                </div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight mt-6 mb-1">Suhaim Soft</h1>
                <p class="text-sm text-blue-600 font-bold tracking-widest uppercase bg-blue-50 px-3 py-1 rounded-full">Workshop Manager</p>
            </div>

            {{-- Login Card --}}
            <div class="glass-card rounded-[2rem] p-8 sm:p-10 relative overflow-hidden">
                
                {{-- Card Highlight --}}
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500"></div>

                <div class="mb-8 text-center">
                    <h2 class="text-2xl font-bold text-slate-800">Welcome Back</h2>
                    <p class="text-sm text-slate-500 mt-2 font-medium">Enter your credentials to access the dashboard</p>
                </div>

                <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Email Input --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 ml-1">Email Address</label>
                        <div class="relative input-glowing rounded-xl transition-all duration-300 bg-white/60 backdrop-blur-sm border border-slate-200/80">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-blue-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2H5v10a2 2 0 002 2z"/></svg>
                            </div>
                            <input type="email" name="email" required placeholder="admin@example.com" value="{{ old('email') }}"
                                   class="w-full bg-transparent pl-12 pr-4 py-3.5 text-slate-800 placeholder-slate-400 text-sm font-medium focus:outline-none rounded-xl">
                        </div>
                        @error('email')<p class="text-xs text-red-500 mt-1.5 font-bold ml-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Password Input --}}
                    <div x-data="{ show: false }">
                        <div class="flex items-center justify-between mb-2 ml-1">
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Password</label>
                            <a href="#" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition-colors">Forgot?</a>
                        </div>
                        <div class="relative input-glowing rounded-xl transition-all duration-300 bg-white/60 backdrop-blur-sm border border-slate-200/80">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-blue-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <input :type="show ? 'text' : 'password'" name="password" required placeholder="••••••••"
                                   class="w-full bg-transparent pl-12 pr-12 py-3.5 text-slate-800 placeholder-slate-400 text-sm font-medium focus:outline-none rounded-xl">
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-blue-500 transition-colors focus:outline-none">
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88L3 3m12 12l6 6"/></svg>
                            </button>
                        </div>
                        @error('password')<p class="text-xs text-red-500 mt-1.5 font-bold ml-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center pt-2 pb-2">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative flex items-center justify-center">
                                <input type="checkbox" name="remember" class="peer sr-only">
                                <div class="w-5 h-5 border-2 border-slate-300 rounded peer-checked:bg-blue-600 peer-checked:border-blue-600 transition-colors"></div>
                                <svg class="absolute w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100 pointer-events-none" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-sm text-slate-600 font-medium group-hover:text-slate-900 transition-colors">Remember my device</span>
                        </label>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="relative w-full inline-flex items-center justify-center gap-2 px-6 py-4 bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-300 hover:-translate-y-0.5 active:translate-y-0 overflow-hidden group">
                        <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:animate-[shimmer_1.5s_infinite]"></div>
                        <span>Secure Login</span>
                        <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </form>
                
                {{-- Register Link --}}
                <div class="mt-8 pt-6 border-t border-slate-200/60 text-center">
                    <p class="text-sm text-slate-500 font-medium">New to Suhaim Soft? 
                        <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:text-indigo-700 transition-colors ml-1 hover:underline underline-offset-4">Register Workshop</a>
                    </p>
                </div>
            </div>

            {{-- Footer --}}
            <div class="text-center mt-10 fade-in-up" style="animation-delay: 0.2s;">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">© {{ date('Y') }} Suhaim Soft. All Rights Reserved.</p>
            </div>
        </div>
    </div>

    <style>
        @keyframes shimmer {
            100% { transform: translateX(100%); }
        }
    </style>
@endsection
