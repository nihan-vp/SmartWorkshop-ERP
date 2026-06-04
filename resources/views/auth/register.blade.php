@extends('layouts.app')

@section('title', 'Register Workshop')
@section('seo-title', 'Register Your Workshop')
@section('seo-description', 'Contact Suhaim Soft to register your auto repair shop or garage.')

@section('content')
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.15), 0 0 0 1px rgba(255,255,255,0.5) inset;
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
        .pulse-glow {
            animation: pulseGlow 2s infinite;
        }
        @keyframes pulseGlow {
            0% { box-shadow: 0 0 0 0 rgba(124, 58, 237, 0.4); }
            70% { box-shadow: 0 0 0 15px rgba(124, 58, 237, 0); }
            100% { box-shadow: 0 0 0 0 rgba(124, 58, 237, 0); }
        }
    </style>

    <div class="relative w-full max-w-md mx-auto z-10 fade-in-up pt-4 pb-8">
        
        {{-- Brand Header --}}
        <div class="flex flex-col items-center mb-8">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-600 to-blue-500 flex items-center justify-center shadow-lg shadow-blue-500/25 mb-4">
                <svg class="w-8 h-8 text-white animate-spin-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="animation-duration: 8s;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight mt-6 mb-1">Join Suhaim Soft</h1>
            <p class="text-sm text-blue-600 font-bold tracking-widest uppercase bg-blue-50 px-3 py-1 rounded-full">Premium Workshop Manager</p>
        </div>

        {{-- Register Card --}}
        <div class="glass-card rounded-[2rem] p-8 sm:p-10 relative overflow-hidden text-center">
            
            {{-- Card Highlight --}}
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 via-blue-500 to-blue-500"></div>

            {{-- Back Button --}}
            <a href="{{ route('login') }}" class="absolute top-6 left-6 p-2.5 rounded-full bg-slate-50 border border-slate-100 text-slate-400 hover:text-blue-600 hover:bg-blue-50 hover:border-blue-100 transition-all duration-300" title="Back to Login">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>

            <div class="w-20 h-20 mx-auto bg-gradient-to-br from-blue-50 to-blue-50 border border-blue-100 rounded-2xl flex items-center justify-center text-blue-600 shadow-inner mb-6 transform -rotate-3 hover:rotate-0 transition-transform duration-300">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6M9 11h6M9 15h4"/>
                </svg>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Register Your Garage</h2>
                <p class="text-sm text-slate-500 mt-3 font-medium leading-relaxed px-4">
                    To maintain a highly secure ecosystem, new workshop registrations and product keys are provided directly by our Super Admin.
                </p>
            </div>

            {{-- Premium Call Box --}}
            <a href="tel:8891479505" class="block relative w-full p-1 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 group hover:shadow-xl hover:shadow-blue-500/30 transition-all duration-300 hover:-translate-y-1">
                <div class="absolute inset-0 bg-white/20 rounded-2xl blur-sm group-hover:bg-white/30 transition-colors"></div>
                <div class="relative bg-white rounded-xl p-5 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 shrink-0 pulse-glow">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <div class="text-left">
                            <p class="text-[10px] text-blue-500 font-extrabold uppercase tracking-wider mb-0.5">Direct Setup Line</p>
                            <p class="text-lg font-black text-slate-800">+91 8891479505</p>
                        </div>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <svg class="w-5 h-5 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                </div>
            </a>
            
            {{-- Return to Login Link --}}
            <div class="mt-8 pt-6 border-t border-slate-200/60 text-center">
                <p class="text-sm text-slate-500 font-medium">Already registered? 
                    <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:text-indigo-600 transition-colors ml-1 hover:underline underline-offset-4">Return to Login</a>
                </p>
            </div>
        </div>

        {{-- Footer --}}
        <div class="text-center mt-10 fade-in-up" style="animation-delay: 0.2s;">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">© {{ date('Y') }} Suhaim Soft. Premium Ecosystem.</p>
        </div>
    </div>
@endsection
