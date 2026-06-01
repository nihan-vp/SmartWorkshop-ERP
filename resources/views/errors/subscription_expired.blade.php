<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suhaim Soft - System Inactive</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f5f3ff',
                            100: '#ede9fe',
                            200: '#ddd6fe',
                            300: '#c4b5fd',
                            400: '#a78bfa',
                            500: '#8b5cf6',
                            600: '#7c3aed',
                            700: '#6d28d9',
                            800: '#5b21b6',
                            900: '#4c1d95',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        
        .glass-card-premium {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }
        
        .pulse-glow-amber {
            animation: pulse-glow-amber-keyframes 2s infinite alternate;
        }
        @keyframes pulse-glow-amber-keyframes {
            0% {
                box-shadow: 0 0 5px rgba(245, 158, 11, 0.2);
            }
            100% {
                box-shadow: 0 0 20px rgba(245, 158, 11, 0.5);
            }
        }
    </style>
</head>
<body class="min-h-screen relative font-sans antialiased text-slate-900 bg-slate-50 flex items-center justify-center p-4">

    <!-- 1. Simulated Workshop Dashboard Background Mockup (Blurred, select-none, pointer-events-none) -->
    <div class="fixed inset-0 w-full h-full min-h-screen overflow-hidden pointer-events-none select-none filter blur-[8px] opacity-40 z-0 flex bg-slate-50">
        <!-- Sidebar Mockup -->
        <div class="hidden lg:flex flex-col w-64 bg-slate-900 text-slate-400 border-r border-slate-800 shrink-0">
            <div class="p-6 border-b border-slate-800 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-violet-600 flex items-center justify-center text-white font-extrabold shadow-sm">
                    SS
                </div>
                <div>
                    <h1 class="text-sm font-extrabold text-white tracking-wide uppercase leading-none">Suhaim Soft</h1>
                    <span class="text-[9px] font-bold text-violet-400 uppercase tracking-widest mt-1 block">Garage Admin</span>
                </div>
            </div>
            <div class="flex-1 py-6 px-4 space-y-7">
                <div class="space-y-2">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 px-3">Main Navigation</span>
                    <div class="space-y-1">
                        <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-violet-950 text-white font-bold text-xs">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                            Overview
                        </div>
                        <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a7 7 0 00-7 7v1h12v-1a7 7 0 00-7-7z"/></svg>
                            Customers
                        </div>
                        <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"/></svg>
                            Vehicles
                        </div>
                        <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg>
                            Invoices & Bills
                        </div>
                        <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/></svg>
                            Operations
                        </div>
                    </div>
                </div>
                <div class="space-y-2">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 px-3">Management System</span>
                    <div class="space-y-1">
                        <div class="flex items-center gap-3 px-3 py-2 text-xs">Services Package list</div>
                        <div class="flex items-center gap-3 px-3 py-2 text-xs">Inventory parts / products</div>
                        <div class="flex items-center gap-3 px-3 py-2 text-xs">Shop Expenses</div>
                        <div class="flex items-center gap-3 px-3 py-2 text-xs">Staff Salary list</div>
                    </div>
                </div>
            </div>
            <div class="p-4 border-t border-slate-800 bg-slate-950/30">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-violet-600 flex items-center justify-center text-white font-bold text-xs">A</div>
                    <div class="truncate">
                        <p class="text-xs font-bold text-white leading-none">Workshop Admin</p>
                        <p class="text-[10px] text-slate-500 truncate mt-1">manager@suhaimsoft.com</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Content Mock -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Navbar -->
            <div class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 shrink-0">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-400">Overview</span>
                    <span class="text-xs font-bold text-slate-300">/</span>
                    <span class="text-xs font-bold text-slate-800">Workshop Performance & Operations</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </div>
                    <div class="w-9 h-9 rounded-xl bg-violet-600 flex items-center justify-center text-white font-extrabold text-xs">
                        WS
                    </div>
                </div>
            </div>

            <!-- Scrollable dashboard mock -->
            <div class="flex-1 p-8 space-y-8 overflow-y-auto">
                <div class="bg-white border border-slate-200 rounded-3xl p-8 flex items-center justify-between shadow-sm">
                    <div class="space-y-1">
                        <h3 class="text-xl font-bold text-slate-900">Welcome back, Workshop Manager!</h3>
                        <p class="text-sm text-slate-500 font-medium">Here is a simplified summary of your shop's operations and finances for today.</p>
                    </div>
                    <div class="bg-violet-600 text-white text-xs font-extrabold py-2.5 px-5 rounded-xl">Create Invoice</div>
                </div>

                <div class="grid grid-cols-4 gap-6">
                    <div class="bg-white border border-slate-200 border-l-4 border-l-emerald-500 rounded-2xl p-5 shadow-sm">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Total Income</p>
                        <p class="text-2xl font-black text-slate-900">₹1,48,250.00</p>
                    </div>
                    <div class="bg-white border border-slate-200 border-l-4 border-l-rose-500 rounded-2xl p-5 shadow-sm">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Outflow</p>
                        <p class="text-2xl font-black text-slate-900">₹42,180.00</p>
                    </div>
                    <div class="bg-white border border-slate-200 border-l-4 border-l-violet-500 rounded-2xl p-5 shadow-sm">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Net Profit</p>
                        <p class="text-2xl font-black text-slate-900">₹1,06,070.00</p>
                    </div>
                    <div class="bg-white border border-slate-200 border-l-4 border-l-sky-500 rounded-2xl p-5 shadow-sm">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Job Queue</p>
                        <p class="text-2xl font-black text-slate-900">24 Orders</p>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-6">
                    <div class="col-span-2 bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                            <span class="text-sm font-bold text-slate-900">Recent Invoices</span>
                        </div>
                        <div class="h-32 bg-slate-50/50"></div>
                    </div>
                    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5">
                        <span class="text-sm font-bold text-slate-900 block">Popular Packages</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Screen Glassmorphic Backdrop overlay and Centered Modal (Floating at absolute center z-50) -->
    <div class="fixed inset-0 min-h-screen flex items-center justify-center p-4 z-50 overflow-y-auto bg-slate-950/20 backdrop-blur-[2px]">
        <div class="w-full max-w-xl transition-all duration-300 transform scale-100 select-text">
            
            <!-- Glassmorphic Card Container -->
            <div class="glass-card-premium relative overflow-hidden rounded-[32px] p-8 md:p-10 shadow-[0_25px_60px_-15px_rgba(124,58,237,0.22)] border border-white/60">
                
                <!-- Decorative Top Gradient Accent -->
                <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-violet-600 via-amber-500 to-rose-500"></div>
                
                <!-- Floating decorative glow elements -->
                <div class="absolute -right-24 -top-24 w-52 h-52 rounded-full bg-violet-500/10 filter blur-3xl pointer-events-none"></div>
                <div class="absolute -left-24 -bottom-24 w-52 h-52 rounded-full bg-amber-500/10 filter blur-3xl pointer-events-none"></div>

                <div class="relative flex flex-col items-center text-center space-y-6">
                    
                    <!-- Dynamic Pulse Icon -->
                    <div class="relative">
                        <span class="absolute inline-flex h-20 w-20 rounded-[24px] bg-rose-400/10 animate-ping"></span>
                        <div class="relative w-16 h-16 rounded-[22px] bg-rose-50 border border-rose-100/60 flex items-center justify-center text-rose-500 shadow-[0_4px_12px_rgba(244,63,94,0.1)]">
                            <svg class="w-8 h-8 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Main Headers -->
                    <div class="space-y-2.5">
                        <span class="inline-flex items-center gap-1.5 text-[10px] font-extrabold uppercase tracking-widest text-rose-600 bg-rose-50 border border-rose-100 px-3 py-1 rounded-full">
                            ⚡ {{ $isSuspended ? 'Access Suspended' : 'Demo / Trial Expired' }}
                        </span>
                        <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none">
                            Suhaim Soft Inactive
                        </h1>
                        <p class="text-slate-500 max-w-md mx-auto font-medium text-[13px] leading-relaxed">
                            To activate your system or be a part of Suhaim Soft, please contact our office at 
                            <span class="font-extrabold text-slate-800">8891479505</span>. 
                            If you already have an activation key, please enter it below.
                        </p>
                    </div>

                    <!-- Workshop Detail Container -->
                    <div class="w-full bg-slate-950 border border-slate-900 rounded-3xl p-5 shadow-[inset_0_1px_3px_rgba(255,255,255,0.05),0_10px_25px_rgba(0,0,0,0.4)] relative overflow-hidden group">
                        <div class="absolute inset-0 bg-gradient-to-r from-violet-500/10 via-transparent to-primary-500/10 pointer-events-none"></div>
                        <div class="flex items-center justify-between border-b border-slate-800 pb-3 mb-3">
                            <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Registered Workshop Account</span>
                            <span class="text-[10px] font-extrabold text-violet-400 bg-violet-950/60 border border-violet-900/40 px-2 py-0.5 rounded-lg">ID: #{{ $workshop->id }}</span>
                        </div>
                        <h2 class="text-2xl font-black text-white tracking-tight truncate leading-tight">{{ $workshop->name }}</h2>
                        
                        @if($workshop->trial_ends_at)
                            <div class="mt-2.5 flex items-center justify-center gap-1.5 text-xs text-slate-400 font-semibold bg-slate-900/60 py-1.5 px-3 rounded-xl border border-slate-800/40 inline-block">
                                <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Expired on: {{ $workshop->trial_ends_at->format('M d, Y h:i A') }} ({{ $workshop->trial_ends_at->diffForHumans() }})</span>
                            </div>
                        @endif
                    </div>

                    <!-- Dynamic Call Box -->
                    <a href="tel:8891479505" class="w-full bg-amber-50 hover:bg-amber-100/60 border border-amber-200/50 rounded-2xl p-4 flex items-center justify-between gap-3 text-left transition-all duration-300 hover:scale-[1.01] hover:shadow-sm group shrink-0 pulse-glow-amber">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-600 shrink-0 group-hover:scale-105 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-amber-600 font-bold uppercase tracking-wider">Contact Suhaim Soft Support</p>
                                <p class="text-sm font-extrabold text-amber-950">+91 8891479505</p>
                            </div>
                        </div>
                        <span class="bg-amber-500 hover:bg-amber-600 text-white font-extrabold text-xs py-2 px-3.5 rounded-xl transition-all shadow-sm active:scale-95 whitespace-nowrap">
                            Call Office
                        </span>
                    </a>

                    <!-- Activation Input Form -->
                    <div class="w-full border-t border-slate-200/60 pt-5 text-left">
                        <form action="{{ route('activate_license') }}" method="POST" class="space-y-3">
                            @csrf
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1 pl-1">Have an Activation Key?</label>
                            <div class="flex gap-2">
                                <input type="text" name="product_key" required placeholder="SUHAIM-XXXX-XXXX-XXXX" 
                                       class="flex-1 px-4 py-3 bg-slate-50 border border-slate-200/80 rounded-2xl text-sm font-extrabold font-mono tracking-widest focus:outline-none focus:border-violet-500 focus:bg-white transition-colors uppercase placeholder:text-slate-300">
                                <button type="submit" class="bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 text-white font-extrabold px-6 py-3 rounded-2xl text-sm transition-all duration-150 active:scale-[0.98] shadow-sm whitespace-nowrap flex items-center gap-1.5">
                                    Register System
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Log Out Panel -->
                    <div class="flex items-center justify-center gap-2 pt-2 text-xs text-slate-400">
                        <span>Logged in as Admin?</span>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="font-extrabold text-violet-600 hover:text-violet-800 transition-colors focus:outline-none hover:underline">
                                Log Out
                            </button>
                        </form>
                    </div>

                </div>
            </div>
            
            <!-- Simple Premium Footer -->
            <p class="text-center text-[11px] text-slate-400 mt-6 font-bold tracking-wide uppercase select-none pointer-events-none">
                &copy; {{ date('Y') }} Suhaim Soft. Secure Garage Ecosystem.
            </p>
            
        </div>
    </div>

    <!-- 3. Alpine.js slide-in Toast Notifications (Top Right Corner) -->
    <div x-data="toastManager()" x-init="init()" class="fixed top-5 right-5 z-[99999] flex flex-col gap-3 max-w-sm w-full pointer-events-none" x-cloak>
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.show" 
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                 x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-2xl bg-white/95 backdrop-blur border shadow-[0_10px_30px_-5px_rgba(0,0,0,0.08)] transition-all duration-300"
                 :class="toast.type === 'success' ? 'border-emerald-100 shadow-emerald-500/5' : 'border-rose-100 shadow-rose-500/5'">
                <div class="p-4 flex items-start gap-3">
                    <!-- Icon -->
                    <div class="shrink-0">
                        <template x-if="toast.type === 'success'">
                            <div class="w-8 h-8 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-500">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                        </template>
                        <template x-if="toast.type === 'error'">
                            <div class="w-8 h-8 rounded-xl bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-500">
                                <svg class="w-4.5 h-4.5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                        </template>
                    </div>
                    <!-- Message Content -->
                    <div class="flex-1 space-y-0.5 text-left">
                        <p class="text-[10px] font-extrabold uppercase tracking-widest" :class="toast.type === 'success' ? 'text-emerald-700' : 'text-rose-700'" x-text="toast.title"></p>
                        <p class="text-xs font-bold text-slate-600" x-text="toast.message"></p>
                    </div>
                    <!-- Close button -->
                    <button type="button" @click="toast.show = false" class="text-slate-400 hover:text-slate-600 transition-colors p-0.5 rounded-lg hover:bg-slate-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        </template>
    </div>

    <!-- Script to manage dynamic Toasts -->
    <script>
        function toastManager() {
            return {
                toasts: [],
                init() {
                    // Pull standard Laravel messages dynamically
                    @if(session('success'))
                        this.addToast('success', 'Activation Successful', "{{ session('success') }}");
                    @endif
                    @if(session('error'))
                        this.addToast('error', 'Activation Failed', "{{ session('error') }}");
                    @endif
                },
                addToast(type, title, message) {
                    const id = Date.now();
                    this.toasts.push({
                        id,
                        type,
                        title,
                        message,
                        show: true
                    });
                    setTimeout(() => {
                        const toast = this.toasts.find(t => t.id === id);
                        if (toast) toast.show = false;
                    }, 6500);
                }
            }
        }
    </script>
</body>
</html>
