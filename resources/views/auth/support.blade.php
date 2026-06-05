@extends('layouts.app')

@section('title', 'Developer Support')
@section('seo-title', 'Developer Support - Suhaim Soft')
@section('seo-description', 'Contact developer support for Suhaim Soft Workshop Manager.')

@section('content')
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.15), 0 0 0 1px rgba(255,255,255,0.5) inset;
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

    <div class="relative w-full max-w-lg mx-auto z-10 fade-in-up pt-4 pb-8">
        
        {{-- Brand Header --}}
        <div class="flex flex-col items-center mb-8">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-600 to-blue-500 flex items-center justify-center shadow-lg shadow-blue-500/30 mb-4">
                <svg width="32" height="32" viewBox="0 0 512 512" fill="currentColor" class="text-white">
                    <path d="M 334 165 C 334 165 298 140 256 140 C 214 140 178 165 178 210 C 178 260 230 275 270 285 C 300 292 342 308 342 355 C 342 410 290 432 256 432 C 210 432 170 410 170 410 L 182 355 C 182 355 220 380 256 380 C 300 380 342 360 342 315 C 342 265 285 245 242 235 C 208 227 170 205 170 160 C 170 100 226 80 256 80 C 306 80 342 105 342 105 Z" />
                </svg>
            </div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-1">Suhaim Soft</h1>
            <p class="text-sm text-blue-600 font-bold tracking-widest uppercase bg-blue-50 px-3 py-1 rounded-full">Developer Support</p>
        </div>

        {{-- Support Card --}}
        <div class="glass-card rounded-[2rem] p-8 sm:p-10 relative overflow-hidden">
            
            {{-- Card Highlight --}}
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 via-indigo-500 to-blue-500"></div>

            {{-- Back Button --}}
            <a href="{{ route('login') }}" class="absolute top-6 left-6 p-2.5 rounded-full bg-slate-50 border border-slate-100 text-slate-400 hover:text-blue-600 hover:bg-blue-50 hover:border-blue-100 transition-all duration-300" title="Back to Login">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>

            <div class="mb-8 text-center pt-2">
                <h2 class="text-2xl font-bold text-slate-800">Support Contacts</h2>
                <p class="text-sm text-slate-500 mt-2 font-medium">Please contact our developer support team to retrieve or reset credentials.</p>
            </div>

            <div class="space-y-6">
                
                {{-- Developer 1: Shahil T --}}
                <div class="p-5 bg-white/50 border border-slate-200/60 rounded-2xl flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <span class="inline-block text-[10px] font-bold text-blue-600 bg-blue-50 border border-blue-100 px-2 py-0.5 rounded-full mb-1">Developer</span>
                        <h4 class="text-sm font-bold text-slate-800">Shahil T</h4>
                        <p class="text-xs text-slate-500 mt-1 font-medium">Phone: <span class="text-slate-700 font-bold select-all">+91 92074 01977</span></p>
                        <p class="text-xs text-slate-500 mt-0.5 font-medium">Email: <span class="text-slate-700 font-bold select-all">shahilt@suhaimsoft.com</span></p>
                    </div>
                </div>

                {{-- Developer 2: Suhaim Soft Support --}}
                <div class="p-5 bg-white/50 border border-slate-200/60 rounded-2xl flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <span class="inline-block text-[10px] font-bold text-indigo-600 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-full mb-1">System Support</span>
                        <h4 class="text-sm font-bold text-slate-800">Suhaim Soft Developer</h4>
                        <p class="text-xs text-slate-500 mt-1 font-medium">Phone: <span class="text-slate-700 font-bold select-all">+91 88914 79505</span></p>
                        <p class="text-xs text-slate-500 mt-0.5 font-medium">Email: <span class="text-slate-700 font-bold select-all">infosuhaimsoft@gmail.com</span></p>
                    </div>
                </div>

            </div>

            {{-- Back to Login Link --}}
            <div class="mt-8 pt-6 border-t border-slate-200/60 text-center">
                <a href="{{ route('login') }}" class="text-sm font-bold text-blue-600 hover:text-blue-800 hover:underline transition-colors">
                    ← Back to Login
                </a>
            </div>

        </div>

        {{-- Footer --}}
        <div class="text-center mt-10">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">© {{ date('Y') }} Suhaim Soft. All Rights Reserved.</p>
        </div>
    </div>
@endsection
