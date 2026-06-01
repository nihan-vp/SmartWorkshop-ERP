<!DOCTYPE html>
<html lang="en" prefix="og: https://ogp.me/ns#" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- ══════════════════════════════════════════════
         ADVANCED SEO SYSTEM — Suhaim Soft Work Shop Landing Page
    ══════════════════════════════════════════════ --}}
    @php
        $siteName      = 'Suhaim Soft Work Shop';
        $fullTitle     = 'Suhaim Soft Workshop Manager — Next-Gen Workshop Automation';
        $seoDesc       = 'Suhaim Soft Work Shop is a next-generation multi-tenant subscription system for workshops. Manage work orders, billing, and staff easily.';
        $seoKeywords   = 'workshop automation, multi-tenant workshop software, garage management system, auto repair shop software, vehicle service software, Suhaim Soft';
        $seoImage      = asset('icons/icon.svg');
        $canonicalUrl  = url()->current();
        $seoRobots     = 'index, follow';
        $seoType       = 'website';
        $seoLocale     = 'en_US';
    @endphp

    {{-- ── Primary Meta Tags ── --}}
    <title>{{ $fullTitle }}</title>
    <meta name="title"           content="{{ $fullTitle }}">
    <meta name="description"     content="{{ $seoDesc }}">
    <meta name="keywords"        content="{{ $seoKeywords }}">
    <meta name="robots"          content="{{ $seoRobots }}">
    <meta name="author"          content="Suhaim Soft">
    <meta name="copyright"       content="© {{ date('Y') }} Suhaim Soft Work Shop">
    <meta name="generator"       content="Suhaim Soft Workshop Manager">
    <meta name="language"        content="English">
    <meta name="revisit-after"   content="7 days">
    <meta name="rating"          content="general">
    <link rel="canonical"        href="{{ $canonicalUrl }}">

    {{-- ── Open Graph / Facebook / LinkedIn ── --}}
    <meta property="og:type"         content="{{ $seoType }}">
    <meta property="og:url"          content="{{ $canonicalUrl }}">
    <meta property="og:title"        content="{{ $fullTitle }}">
    <meta property="og:description"  content="{{ $seoDesc }}">
    <meta property="og:image"        content="{{ $seoImage }}">
    <meta property="og:image:alt"    content="{{ $siteName }} Logo">
    <meta property="og:site_name"    content="{{ $siteName }}">
    <meta property="og:locale"       content="{{ $seoLocale }}">

    {{-- ── Twitter Card ── --}}
    <meta name="twitter:card"        content="summary">
    <meta name="twitter:title"       content="{{ $fullTitle }}">
    <meta name="twitter:description" content="{{ $seoDesc }}">
    <meta name="twitter:image"       content="{{ $seoImage }}">
    <meta name="twitter:image:alt"   content="{{ $siteName }}">

    {{-- ── JSON-LD Structured Data ── --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@graph": [
            {
                "@@type": "SoftwareApplication",
                "@@id": "{{ url('/') }}/#software",
                "name": "{{ $siteName }}",
                "url": "{{ url('/') }}",
                "applicationCategory": "BusinessApplication",
                "operatingSystem": "Web",
                "description": "{{ $seoDesc }}",
                "offers": {
                    "@@type": "Offer",
                    "price": "0",
                    "priceCurrency": "USD"
                },
                "publisher": {
                    "@@type": "Organization",
                    "name": "Suhaim Soft",
                    "url": "{{ url('/') }}"
                }
            }
        ]
    }
    </script>

    <!-- PWA Config -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1d4ed8">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="/icons/icon.svg">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            350: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        }
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-glow': 'pulseGlow 2.5s ease-in-out infinite',
                        'spin-slow': 'spin 12s linear infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px) rotate(0deg)' },
                            '50%': { transform: 'translateY(-20px) rotate(2deg)' }
                        },
                        pulseGlow: {
                            '0%, 100%': { opacity: 0.6, transform: 'scale(1)' },
                            '50%': { opacity: 0.9, transform: 'scale(1.05)' }
                        }
                    }
                }
            }
        }
    </script>

    <style type="text/tailwindcss">
        @layer base {
            body {
                @apply bg-slate-950 text-slate-100 overflow-x-hidden antialiased;
            }
        }
        .glass-header {
            @apply bg-slate-950/40 backdrop-blur-xl border-b border-white/5;
        }
        .glass-card-dark {
            @apply bg-slate-900/40 backdrop-blur-xl border border-white/5 shadow-2xl rounded-3xl p-6 transition-all duration-300;
        }
        .glass-card-dark:hover {
            @apply border-brand-500/30 bg-slate-900/60 shadow-brand-500/5 -translate-y-1.5;
        }
        .text-glow-purple {
            text-shadow: 0 0 40px rgba(139, 92, 246, 0.4);
        }
        .text-glow-emerald {
            text-shadow: 0 0 40px rgba(16, 185, 129, 0.4);
        }
    </style>
</head>
<body class="font-sans">

    <!-- 3D Interactive WebGL/Particle Canvas Background -->
    <div class="fixed inset-0 z-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-indigo-950/60 via-slate-950 to-slate-950">
        <canvas id="webgl-particles" class="absolute inset-0 block"></canvas>
    </div>

    <!-- Glowing Vector Spheres (Pure CSS 3D Depth) -->
    <div class="fixed top-[-10%] right-[-10%] w-[50vw] h-[50vw] bg-brand-500/10 rounded-full blur-[140px] pointer-events-none z-0"></div>
    <div class="fixed bottom-[-10%] left-[-10%] w-[50vw] h-[50vw] bg-emerald-500/5 rounded-full blur-[140px] pointer-events-none z-0"></div>
    <div class="fixed top-[40%] left-[30%] w-[35vw] h-[35vw] bg-violet-600/5 rounded-full blur-[120px] pointer-events-none z-0"></div>

    <div class="relative z-10 min-h-screen flex flex-col justify-between">
        
        <!-- Navigation Header -->
        <header class="glass-header sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                <!-- Logo -->
                <a href="#" class="flex items-center gap-2.5 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-500 to-emerald-400 flex items-center justify-center shadow-lg shadow-brand-500/20 group-hover:scale-105 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <span class="font-outfit text-lg sm:text-xl font-black tracking-tight text-white group-hover:text-emerald-300 transition-colors">
                        SUHAIM<span class="text-brand-400">SOFT</span>
                    </span>
                </a>

                <!-- Nav Links -->
                <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-300">
                    <a href="#" class="hover:text-white transition-colors">Home</a>
                    <a href="#about" class="hover:text-white transition-colors">Welcome</a>
                    <a href="#onboarding" class="hover:text-white transition-colors">Process</a>
                    <a href="#features" class="hover:text-white transition-colors">Features</a>
                    <a href="#results" class="hover:text-white transition-colors">Benefits</a>
                </nav>

                <!-- Actions -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('register') }}" class="px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-brand-600 to-brand-500 rounded-xl shadow-lg shadow-brand-500/20 hover:shadow-brand-500/35 hover:-translate-y-0.5 active:scale-95 transition-all">
                        Enroll Now
                    </a>
                    <a href="{{ route('login') }}" class="px-5 py-2.5 text-sm font-bold text-slate-200 hover:text-white transition-colors rounded-xl hover:bg-white/5">
                        Login
                    </a>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <main class="flex-grow">
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-24 lg:pt-20">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                    
                    <!-- Centered Hero Content -->
                    <div class="lg:col-span-12 max-w-4xl mx-auto space-y-10 text-center relative z-10">
                        
                        <!-- Animated background glow behind text -->
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full max-w-3xl bg-gradient-to-r from-brand-600/20 via-emerald-500/10 to-indigo-500/20 blur-[100px] rounded-full pointer-events-none z-[-1] animate-pulse-glow"></div>

                        <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-slate-900/50 border border-brand-500/30 text-xs sm:text-sm font-bold text-brand-350 tracking-wide uppercase select-none backdrop-blur-sm animate-float">
                            <span class="flex h-2 w-2 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-500"></span>
                            </span>
                            Next-Generation Workshop SaaS
                        </div>
                        
                        <h1 class="font-outfit text-5xl sm:text-6xl lg:text-8xl font-black tracking-tighter leading-[1.05] text-white">
                            Command Your <br>
                            <span class="bg-clip-text text-transparent bg-gradient-to-r from-brand-400 via-emerald-300 to-amber-300 text-glow-purple inline-block animate-float" style="animation-duration: 8s;">
                                Workshop Empire
                            </span>
                        </h1>
                        
                        <p class="text-lg sm:text-2xl text-slate-400 font-medium max-w-3xl mx-auto leading-relaxed">
                            <span class="text-white font-bold">SUHAIM SOFT</span> delivers a smart, secure, and fully automated <span class="text-brand-350 font-bold">Workshop Management Platform</span>. From rapid invoicing to intelligent inventory tracking, we empower workshop professionals to dominate their market.
                        </p>

                        <!-- Call to Actions -->
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-5 pt-4">
                            <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 text-base font-extrabold text-white bg-gradient-to-r from-brand-600 to-emerald-500 rounded-2xl shadow-xl shadow-brand-500/20 hover:shadow-brand-500/40 hover:-translate-y-1 active:scale-95 transition-all flex items-center justify-center gap-2 group">
                                Start Your Journey
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                            <a href="#features" class="w-full sm:w-auto px-8 py-4 text-base font-extrabold text-slate-200 bg-slate-900/50 border border-white/10 hover:bg-white/10 hover:border-white/20 rounded-2xl transition-all flex items-center justify-center gap-2 group backdrop-blur-sm">
                                <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-400 transition-colors animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Explore Features
                            </a>
                        </div>

                        <!-- Mini Stats Grid -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto pt-12 border-t border-white/10">
                            <div class="p-4 rounded-2xl bg-white/5 border border-white/5 hover:bg-white/10 hover:-translate-y-1 transition-all">
                                <span class="block font-outfit text-3xl font-extrabold text-white mb-1">99.9%</span>
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">System Uptime</span>
                            </div>
                            <div class="p-4 rounded-2xl bg-white/5 border border-white/5 hover:bg-white/10 hover:-translate-y-1 transition-all">
                                <span class="block font-outfit text-3xl font-extrabold text-emerald-400 mb-1">100+</span>
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Workshops</span>
                            </div>
                            <div class="p-4 rounded-2xl bg-white/5 border border-white/5 hover:bg-white/10 hover:-translate-y-1 transition-all">
                                <span class="block font-outfit text-3xl font-extrabold text-brand-400 mb-1">1-Sec</span>
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Invoicing</span>
                            </div>
                            <div class="p-4 rounded-2xl bg-white/5 border border-white/5 hover:bg-white/10 hover:-translate-y-1 transition-all">
                                <span class="block font-outfit text-3xl font-extrabold text-amber-400 mb-1">24/7</span>
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Support</span>
                            </div>
                        </div>
                    </div>


                </div>
            </section>

            <!-- About Us / Our Mission Section -->
            <section id="about" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 relative">
                <div class="glass-card-dark bg-gradient-to-br from-slate-900/40 via-indigo-950/20 to-slate-950/40 border-white/10 !p-8 sm:!p-12 max-w-4xl mx-auto relative overflow-hidden">
                    
                    <!-- Glow mesh effect -->
                    <div class="absolute -top-[30%] -right-[30%] w-[60%] h-[60%] bg-brand-500/10 rounded-full blur-[100px] pointer-events-none"></div>
                    <div class="absolute -bottom-[30%] -left-[30%] w-[60%] h-[60%] bg-emerald-500/5 rounded-full blur-[100px] pointer-events-none"></div>

                    <div class="relative z-10 space-y-8">
                        <div class="space-y-3 text-center sm:text-left">
                            <span class="px-3.5 py-1.5 rounded-xl bg-brand-500/10 border border-brand-500/25 text-xs font-bold text-brand-350 tracking-wider uppercase inline-block">
                                OUR VISION
                            </span>
                            <h2 class="font-outfit text-3xl sm:text-4xl font-black text-white leading-tight">
                                WELCOME TO SUHAIM SOFT
                                <span class="block text-sm sm:text-base font-semibold text-slate-400 mt-2 font-sans tracking-wide">
                                    Your Partner in Digital Workshop Transformation.
                                </span>
                            </h2>
                        </div>

                        <div class="space-y-6 text-slate-300 text-sm sm:text-base leading-relaxed font-medium">
                            <p>
                                In today's fast-paced automotive environment, the most valuable resource is <span class="text-white font-bold">time</span>. Administrative tasks, manual inventory tracking, and cumbersome paperwork can divert focus from what truly matters: <span class="text-emerald-400 font-bold">superior vehicle care</span>. <span class="text-white font-bold">SUHAIM SOFT</span> was founded on a simple principle: to give that time back to automotive professionals.
                            </p>
                            <p>
                                Our intelligent <span class="text-brand-350 font-bold">Workshop Management System</span> is more than just a digital filing cabinet. It is a powerful, integrated platform designed to streamline your entire workflow, from customer vehicle check-in to 1-click preset package invoicing.
                            </p>
                            <p>
                                By automating repetitive inventory tasks, providing actionable insights from workshop analytics, and ensuring rock-solid subscription security, we empower you to operate your garage more efficiently and effectively. Join us in building a smarter, more connected future for automotive workshop management.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Onboarding Process Section -->
            <section id="onboarding" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 border-t border-white/5 relative">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="px-3.5 py-1.5 rounded-xl bg-brand-500/10 border border-brand-500/25 text-xs font-bold text-brand-350 tracking-wider uppercase inline-block mb-3">
                        HOW IT WORKS
                    </span>
                    <h2 class="font-outfit text-3xl sm:text-5xl font-black tracking-tight text-white mb-4">
                        Our Simple Onboarding Process
                    </h2>
                    <p class="text-slate-400 font-semibold text-base sm:text-lg">
                        Get started with <span class="text-white font-bold">SUHAIM SOFT</span> in three easy steps. Streamline your workshop with our intuitive platform.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Step 1 -->
                    <div class="glass-card-dark relative group overflow-hidden">
                        <!-- Step Number Overlay Background -->
                        <div class="absolute -top-6 -right-6 font-outfit text-8xl font-black text-white/5 select-none transition-all group-hover:text-white/10 group-hover:-translate-y-2">1</div>
                        
                        <div class="w-12 h-12 rounded-2xl bg-brand-500/10 border border-brand-500/20 flex items-center justify-center mb-6 text-brand-400 font-outfit text-xl font-black">
                            01
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">Consult & Demo</h3>
                        <p class="text-slate-400 text-sm leading-relaxed font-semibold">
                            Request a demo, and our specialists will showcase the platform's power, tailored to your workshop's unique operating needs.
                        </p>
                    </div>

                    <!-- Step 2 -->
                    <div class="glass-card-dark relative group overflow-hidden">
                        <!-- Step Number Overlay Background -->
                        <div class="absolute -top-6 -right-6 font-outfit text-8xl font-black text-white/5 select-none transition-all group-hover:text-white/10 group-hover:-translate-y-2">2</div>
                        
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center mb-6 text-emerald-400 font-outfit text-xl font-black">
                            02
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">Seamless Integration</h3>
                        <p class="text-slate-400 text-sm leading-relaxed font-semibold">
                            Our team handles the heavy lifting, migrating your existing customer/inventory records and integrating our system seamlessly into your daily workflow.
                        </p>
                    </div>

                    <!-- Step 3 -->
                    <div class="glass-card-dark relative group overflow-hidden">
                        <!-- Step Number Overlay Background -->
                        <div class="absolute -top-6 -right-6 font-outfit text-8xl font-black text-white/5 select-none transition-all group-hover:text-white/10 group-hover:-translate-y-2">3</div>
                        
                        <div class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center mb-6 text-amber-400 font-outfit text-xl font-black">
                            03
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">Training & Support</h3>
                        <p class="text-slate-400 text-sm leading-relaxed font-semibold">
                            We provide comprehensive training and dedicated helpline support to ensure your workshop team is fully confident and successful.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Features Grid Section -->
            <section id="features" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 border-t border-white/5 relative">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="font-outfit text-3xl sm:text-5xl font-black tracking-tight text-white mb-4">
                        Everything You Need to Scale
                    </h2>
                    <p class="text-slate-400 font-semibold text-base sm:text-lg">
                        We equip you with comprehensive automated tools to optimize, trace, and expand garage workflow logic.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Feature 1: Invoices -->
                    <div class="glass-card-dark">
                        <div class="w-12 h-12 rounded-2xl bg-brand-500/10 border border-brand-500/20 flex items-center justify-center mb-6 text-brand-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Smart Invoicing</h3>
                        <p class="text-slate-400 text-sm leading-relaxed">
                            Generate beautiful PDF bills and instantly share custom WhatsApp messages with customers, featuring auto-calculations of tax, discounts, and payment methods (Cash/UPI).
                        </p>
                    </div>

                    <!-- Feature 2: Bill Templates -->
                    <div class="glass-card-dark">
                        <div class="w-12 h-12 rounded-2xl bg-violet-500/10 border border-violet-500/20 flex items-center justify-center mb-6 text-violet-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Preset Invoice Packages</h3>
                        <p class="text-slate-400 text-sm leading-relaxed">
                            Bundle common services and fluid/parts replacements into reusable presets. Create standard templates to generate complete bills in a single click.
                        </p>
                    </div>

                    <!-- Feature 3: Customers -->
                    <div class="glass-card-dark">
                        <div class="w-12 h-12 rounded-2xl bg-sky-500/10 border border-sky-500/20 flex items-center justify-center mb-6 text-sky-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Customer Management</h3>
                        <p class="text-slate-400 text-sm leading-relaxed">
                            Store profiles with full contact detail history, vehicle logs, and total billing statistics to provide a personalized service experience.
                        </p>
                    </div>

                    <!-- Feature 4: Vehicles -->
                    <div class="glass-card-dark">
                        <div class="w-12 h-12 rounded-2xl bg-teal-500/10 border border-teal-500/20 flex items-center justify-center mb-6 text-teal-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7m-7-2v3m0 0v3m0-3h3m-3 0H9"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Vehicle Logs</h3>
                        <p class="text-slate-400 text-sm leading-relaxed">
                            Register cars with brand, model, year, color, and plate numbers, mapping them directly to owners for instant invoice association.
                        </p>
                    </div>

                    <!-- Feature 5: Services -->
                    <div class="glass-card-dark">
                        <div class="w-12 h-12 rounded-2xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center mb-6 text-rose-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Workshop Services</h3>
                        <p class="text-slate-400 text-sm leading-relaxed">
                            Define standard workshop repair jobs, tune-ups, AC servicing, and wheel alignments with custom pricing and duration metrics.
                        </p>
                    </div>

                    <!-- Feature 6: Products & Stock -->
                    <div class="glass-card-dark">
                        <div class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center mb-6 text-amber-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Automated Inventory Stock</h3>
                        <p class="text-slate-400 text-sm leading-relaxed">
                            Complete tracing of spare parts and fluids. Auto-deduct products on bill creation, trigger warnings for low stock levels, and protect margins.
                        </p>
                    </div>

                    <!-- Feature 7: Expenses -->
                    <div class="glass-card-dark">
                        <div class="w-12 h-12 rounded-2xl bg-fuchsia-500/10 border border-fuchsia-500/20 flex items-center justify-center mb-6 text-fuchsia-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Expense Management</h3>
                        <p class="text-slate-400 text-sm leading-relaxed">
                            Log overheads, utilities, spare tools purchase, and workshop rent to maintain an accurate, healthy real-time cash flow ledger.
                        </p>
                    </div>

                    <!-- Feature 8: Salaries -->
                    <div class="glass-card-dark">
                        <div class="w-12 h-12 rounded-2xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center mb-6 text-cyan-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Salaries and Payroll</h3>
                        <p class="text-slate-400 text-sm leading-relaxed">
                            Manage employee payroll releases. Track monthly compensation disbursements with payment status, period, and payment method logs.
                        </p>
                    </div>

                    <!-- Feature 9: Employees -->
                    <div class="glass-card-dark">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center mb-6 text-emerald-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Team Management</h3>
                        <p class="text-slate-400 text-sm leading-relaxed">
                            Maintain your team roster, assign senior mechanics or helpers to specific jobs, and view total active completed work order allocations.
                        </p>
                    </div>

                    <!-- Feature 10: Work Orders -->
                    <div class="glass-card-dark">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center mb-6 text-indigo-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Work Orders</h3>
                        <p class="text-slate-400 text-sm leading-relaxed">
                            Assign ongoing repair jobs, set priority levels (Urgent/High/Normal), estimated costs, and track actual mechanic completion times.
                        </p>
                    </div>

                    <!-- Feature 11: Warranties -->
                    <div class="glass-card-dark">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center mb-6 text-emerald-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Warranty Tracking</h3>
                        <p class="text-slate-400 text-sm leading-relaxed">
                            Issue valid warranties on products and service packages with automatic expiration tracking, status indicators, and claim logs.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Tangible Results Section -->
            <section id="results" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 border-t border-white/5 relative">
                <!-- Glowing Accent behind Stats -->
                <div class="absolute top-[20%] left-[10%] w-[30vw] h-[30vw] bg-emerald-500/5 rounded-full blur-[120px] pointer-events-none z-0"></div>
                <div class="absolute bottom-[20%] right-[10%] w-[30vw] h-[30vw] bg-brand-500/5 rounded-full blur-[120px] pointer-events-none z-0"></div>

                <div class="relative z-10">
                    <div class="text-center max-w-3xl mx-auto mb-16">
                        <span class="px-3.5 py-1.5 rounded-xl bg-emerald-500/10 border border-emerald-500/25 text-xs font-bold text-emerald-400 tracking-wider uppercase inline-block mb-3">
                            TANGIBLE RESULTS
                        </span>
                        <h2 class="font-outfit text-3xl sm:text-5xl font-black tracking-tight text-white mb-4">
                            Tangible Results
                        </h2>
                        <p class="text-slate-400 font-semibold text-base sm:text-lg">
                            Our platform isn't just about features; it's about delivering real-world benefits that impact your bottom line and customer satisfaction.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        <!-- Result 1 -->
                        <div class="glass-card-dark relative group overflow-hidden border border-brand-500/10 hover:border-brand-500/30 transition-all duration-300">
                            <div class="absolute -bottom-[30%] -left-[30%] w-[60%] h-[60%] bg-brand-500/5 rounded-full blur-[50px] pointer-events-none transition-all group-hover:bg-brand-500/10"></div>
                            <div class="text-4xl sm:text-5xl font-black font-outfit text-brand-350 mb-3 flex items-baseline gap-0.5">
                                <span class="stat-counter font-outfit font-black" data-target="100">0</span>%
                            </div>
                            <h3 class="text-lg font-bold text-white mb-2 font-outfit">Time Savings</h3>
                            <p class="text-slate-400 text-xs sm:text-sm leading-relaxed font-semibold">
                                Reduce administrative overhead, allowing more time for what matters most: vehicle repairs and customer service.
                            </p>
                        </div>

                        <!-- Result 2 -->
                        <div class="glass-card-dark relative group overflow-hidden border border-emerald-500/10 hover:border-emerald-500/30 transition-all duration-300">
                            <div class="absolute -bottom-[30%] -left-[30%] w-[60%] h-[60%] bg-emerald-500/5 rounded-full blur-[50px] pointer-events-none transition-all group-hover:bg-emerald-500/10"></div>
                            <div class="text-4xl sm:text-5xl font-black font-outfit text-emerald-400 mb-3 flex items-baseline gap-0.5">
                                <span class="stat-counter font-outfit font-black" data-target="100">0</span>%
                            </div>
                            <h3 class="text-lg font-bold text-white mb-2 font-outfit">Data Accuracy</h3>
                            <p class="text-slate-400 text-xs sm:text-sm leading-relaxed font-semibold">
                                Our system minimizes manual data entry errors, ensuring highly accurate and reliable vehicle and customer records.
                            </p>
                        </div>

                        <!-- Result 3 -->
                        <div class="glass-card-dark relative group overflow-hidden border border-amber-500/10 hover:border-amber-500/30 transition-all duration-300">
                            <div class="absolute -bottom-[30%] -left-[30%] w-[60%] h-[60%] bg-amber-500/5 rounded-full blur-[50px] pointer-events-none transition-all group-hover:bg-amber-500/10"></div>
                            <div class="text-4xl sm:text-5xl font-black font-outfit text-amber-400 mb-3 flex items-baseline gap-0.5">
                                <span class="stat-counter font-outfit font-black" data-target="100">0</span>%
                            </div>
                            <h3 class="text-lg font-bold text-white mb-2 font-outfit">Revenue Boost</h3>
                            <p class="text-slate-400 text-xs sm:text-sm leading-relaxed font-semibold">
                                Streamline invoicing and preset packages to increase revenue collection by an average of 100%.
                            </p>
                        </div>

                        <!-- Result 4 -->
                        <div class="glass-card-dark relative group overflow-hidden border border-purple-500/10 hover:border-purple-500/30 transition-all duration-300">
                            <div class="absolute -bottom-[30%] -left-[30%] w-[60%] h-[60%] bg-purple-500/5 rounded-full blur-[50px] pointer-events-none transition-all group-hover:bg-purple-500/10"></div>
                            <div class="text-4xl sm:text-5xl font-black font-outfit text-purple-400 mb-3 flex items-baseline gap-0.5">
                                <span class="stat-counter font-outfit font-black" data-target="100">0</span>%
                            </div>
                            <h3 class="text-lg font-bold text-white mb-2 font-outfit">Customer Satisfaction</h3>
                            <p class="text-slate-400 text-xs sm:text-sm leading-relaxed font-semibold">
                                Faster service check-ins and better records access lead to a significant increase in customer satisfaction.
                            </p>
                        </div>
                    </div>
                </div>
            </section>


            <!-- Contact / Enquiry Form -->
            <section id="contact" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 border-t border-white/5">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

                    <!-- Left: Info -->
                    <div>
                        <span class="px-3 py-1 rounded-lg bg-brand-500/10 border border-brand-500/25 text-xs font-bold text-brand-350 uppercase tracking-widest inline-block mb-5">Get in Touch</span>
                        <h2 class="font-outfit text-3xl sm:text-4xl font-black tracking-tight text-white mb-4">Contact &amp; Enquiries</h2>
                        <p class="text-slate-400 font-semibold text-sm sm:text-base leading-relaxed mb-8">
                            Have questions about the platform, need a custom demo, or want to discuss licensing? Our team is ready to help.
                        </p>
                        <div class="space-y-5">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-brand-500/10 border border-brand-500/20 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-brand-350" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-0.5">Support Helpline</p>
                                    <a href="tel:+918891479505" class="text-white font-bold hover:text-brand-350 transition-colors">+91 88914 79505</a>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-0.5">Email</p>
                                    <a href="mailto:info@suhaimsoft.com" class="text-white font-bold hover:text-brand-350 transition-colors">info@suhaimsoft.com</a>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Right: Form -->
                    <div class="glass-card-dark border border-white/10">
                        <form id="contact-form" class="space-y-5" onsubmit="handleContactSubmit(event)">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2" for="contact-name">Full Name</label>
                                    <input id="contact-name" type="text" required placeholder="Your name"
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-brand-500/50 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2" for="contact-phone">Phone</label>
                                    <input id="contact-phone" type="tel" placeholder="+91 XXXXX XXXXX"
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-brand-500/50 transition-all">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2" for="contact-email">Email Address</label>
                                <input id="contact-email" type="email" required placeholder="you@example.com"
                                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-brand-500/50 transition-all">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2" for="contact-message">Message</label>
                                <textarea id="contact-message" rows="4" placeholder="Tell us about your workshop&hellip;"
                                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-brand-500/50 transition-all resize-none"></textarea>
                            </div>
                            <button type="submit"
                                class="w-full py-3.5 text-sm font-bold bg-gradient-to-r from-brand-500 to-indigo-500 hover:opacity-90 text-white rounded-2xl transition-all shadow-lg shadow-brand-500/20 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                Send Enquiry
                            </button>
                            <p id="contact-success" class="hidden text-center text-sm font-bold text-emerald-400">
                                &check; Message sent! We&rsquo;ll get back to you shortly.
                            </p>
                        </form>
                    </div>

                </div>
            </section>
        </main>



        <!-- Footer — Responsive Multi-column -->
        <footer class="border-t border-white/5 bg-slate-950/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">

                    <!-- Brand -->
                    <div class="sm:col-span-2 lg:col-span-1">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-500 to-indigo-600 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <span class="font-outfit font-black text-white text-base">Suhaim Soft</span>
                        </div>
                        <p class="text-slate-400 text-xs font-semibold leading-relaxed mb-4">
                            Next-generation workshop management platform. Smart billing, inventory, and customer management for auto workshops.
                        </p>
                        <a href="tel:+918891479505" class="text-brand-350 text-xs font-bold hover:text-white transition-colors block mb-6">+91 88914 79505</a>
                        
                        <!-- Social Icons -->
                        <div class="flex items-center gap-3">
                            <a href="#" class="w-8 h-8 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/10 transition-all">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                            </a>
                            <a href="#" class="w-8 h-8 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/10 transition-all">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                            </a>
                            <a href="#" class="w-8 h-8 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/10 transition-all">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/></svg>
                            </a>
                            <a href="#" class="w-8 h-8 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/10 transition-all">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h4 class="text-xs font-black text-slate-300 uppercase tracking-widest mb-4">Quick Links</h4>
                        <ul class="space-y-2.5">
                            <li><a href="#features" class="text-slate-400 text-sm font-semibold hover:text-white transition-colors">Features</a></li>
                            <li><a href="#enroll-now" class="text-slate-400 text-sm font-semibold hover:text-white transition-colors">Enroll Now</a></li>
                            <li><a href="#contact" class="text-slate-400 text-sm font-semibold hover:text-white transition-colors">Contact</a></li>
                            <li><a href="{{ route('login') }}" class="text-slate-400 text-sm font-semibold hover:text-white transition-colors">Login</a></li>
                            <li><a href="{{ route('register') }}" class="text-slate-400 text-sm font-semibold hover:text-white transition-colors">Register</a></li>
                        </ul>
                    </div>

                    <!-- Platform Features -->
                    <div>
                        <h4 class="text-xs font-black text-slate-300 uppercase tracking-widest mb-4">Platform</h4>
                        <ul class="space-y-2.5">
                            <li class="text-slate-400 text-sm font-semibold">Invoice &amp; Billing</li>
                            <li class="text-slate-400 text-sm font-semibold">Work Orders</li>
                            <li class="text-slate-400 text-sm font-semibold">Vehicle Tracking</li>
                            <li class="text-slate-400 text-sm font-semibold">Spare Parts</li>
                            <li class="text-slate-400 text-sm font-semibold">Employee Salaries</li>
                            <li class="text-slate-400 text-sm font-semibold">Warranty Manager</li>
                        </ul>
                    </div>

                    <!-- Support -->
                    <div>
                        <h4 class="text-xs font-black text-slate-300 uppercase tracking-widest mb-4">Support</h4>
                        <ul class="space-y-3">
                            <li class="flex items-start gap-2 text-sm font-semibold text-slate-400">
                                <svg class="w-4 h-4 text-brand-350 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                <a href="tel:+918891479505" class="hover:text-white transition-colors">+91 88914 79505</a>
                            </li>
                            <li class="flex items-start gap-2 text-sm font-semibold text-slate-400">
                                <svg class="w-4 h-4 text-emerald-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <a href="mailto:info@suhaimsoft.com" class="hover:text-white transition-colors">info@suhaimsoft.com</a>
                            </li>

                        </ul>
                    </div>

                </div>

                <!-- Bottom bar -->
                <div class="mt-10 pt-6 border-t border-white/5 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <p class="text-xs text-slate-500 font-semibold text-center sm:text-left">
                        &copy; {{ date('Y') }} Suhaim Soft Workshop Manager. All rights reserved.
                </div>
            </div>
        </footer>

    </div>

    <!-- Three.js (Lightweight interactive particle rendering) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script>
        // Custom 3D Interactive WebGL Particles Wave animation
        (function() {
            const canvas = document.getElementById('webgl-particles');
            if(!canvas) return;

            let scene, camera, renderer, particles, particleSystem;
            const particleCount = 1200;
            const positions = new Float32Array(particleCount * 3);
            const initialY = new Float32Array(particleCount);
            const speeds = new Float32Array(particleCount);

            function init() {
                scene = new THREE.Scene();
                
                // Camera setup
                camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 1, 1000);
                camera.position.z = 180;
                camera.position.y = 80;
                camera.rotation.x = -0.4;

                // Create Particle Geometry
                const geometry = new THREE.BufferGeometry();
                
                for(let i = 0; i < particleCount; i++) {
                    // Spread points in 3D Space (particle grid/wave)
                    const x = (Math.random() - 0.5) * 800;
                    const y = (Math.random() - 0.5) * 200 - 40;
                    const z = (Math.random() - 0.5) * 800;

                    positions[i * 3] = x;
                    positions[i * 3 + 1] = y;
                    positions[i * 3 + 2] = z;

                    initialY[i] = y;
                    speeds[i] = 0.5 + Math.random() * 1.5;
                }

                geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));

                // Circle texture for smooth particles
                const particleTexture = createCircleTexture();

                // Material styling
                const material = new THREE.PointsMaterial({
                    size: 2.2,
                    map: particleTexture,
                    transparent: true,
                    opacity: 0.7,
                    depthWrite: false,
                    blending: THREE.AdditiveBlending,
                    color: 0x818cf8 // indigo-400 glow
                });

                particleSystem = new THREE.Points(geometry, material);
                scene.add(particleSystem);

                // Renderer setup
                renderer = new THREE.WebGLRenderer({ canvas: canvas, alpha: true, antialias: true });
                renderer.setSize(window.innerWidth, window.innerHeight);
                renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

                // Interaction
                window.addEventListener('resize', onWindowResize);
                document.addEventListener('mousemove', onMouseMove);
            }

            function createCircleTexture() {
                const canvas = document.createElement('canvas');
                canvas.width = 16;
                canvas.height = 16;
                const ctx = canvas.getContext('2d');
                const gradient = ctx.createRadialGradient(8, 8, 0, 8, 8, 8);
                gradient.addColorStop(0, 'rgba(255, 255, 255, 1)');
                gradient.addColorStop(1, 'rgba(255, 255, 255, 0)');
                ctx.fillStyle = gradient;
                ctx.fillRect(0, 0, 16, 16);
                return new THREE.CanvasTexture(canvas);
            }

            let mouseX = 0, mouseY = 0;
            function onMouseMove(event) {
                mouseX = (event.clientX - window.innerWidth / 2) * 0.05;
                mouseY = (event.clientY - window.innerHeight / 2) * 0.05;
            }

            function onWindowResize() {
                camera.aspect = window.innerWidth / window.innerHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(window.innerWidth, window.innerHeight);
            }

            let clock = 0;
            function animate() {
                requestAnimationFrame(animate);
                clock += 0.008;

                // Move camera gently with mouse interaction
                camera.position.x += (mouseX - camera.position.x) * 0.05;
                camera.position.y += (-mouseY + 80 - camera.position.y) * 0.05;
                camera.lookAt(new THREE.Vector3(0, -40, 0));

                const positionsAttr = particleSystem.geometry.attributes.position;
                
                for(let i = 0; i < particleCount; i++) {
                    const x = positionsAttr.array[i * 3];
                    const z = positionsAttr.array[i * 3 + 2];
                    
                    // Wave motion function
                    positionsAttr.array[i * 3 + 1] = initialY[i] + Math.sin(x * 0.01 + clock + speeds[i]) * 15 + Math.cos(z * 0.01 + clock) * 15;
                }
                
                particleSystem.geometry.attributes.position.needsUpdate = true;
                
                // Slow rotation
                particleSystem.rotation.y = clock * 0.05;

                renderer.render(scene, camera);
            }

            init();
            animate();
        })();

        // Counter Animation for Tangible Results
        document.addEventListener('DOMContentLoaded', () => {
            const statsSection = document.getElementById('results');
            const counters = document.querySelectorAll('.stat-counter');
            
            const countTo = (counter) => {
                const target = parseInt(counter.getAttribute('data-target') || '100');
                const duration = 2000; // 2 seconds
                const startTime = performance.now();
                
                const animateCount = (currentTime) => {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    
                    // Ease out quad
                    const easeProgress = progress * (2 - progress);
                    const currentValue = Math.floor(easeProgress * target);
                    
                    counter.textContent = currentValue;
                    
                    if (progress < 1) {
                        requestAnimationFrame(animateCount);
                    } else {
                        counter.textContent = target;
                    }
                };
                
                requestAnimationFrame(animateCount);
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        counters.forEach(counter => countTo(counter));
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.15 });

            if (statsSection) {
                observer.observe(statsSection);
            }
        });


        // Contact Form Logic
        function handleContactSubmit(event) {
            event.preventDefault();
            const btn = document.querySelector('#contact-form button[type="submit"]');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Sending...';
            btn.disabled = true;

            // Simulate form submission delay
            setTimeout(() => {
                document.getElementById('contact-form').reset();
                btn.innerHTML = originalText;
                btn.disabled = false;
                
                const successMsg = document.getElementById('contact-success');
                successMsg.classList.remove('hidden');
                
                setTimeout(() => {
                    successMsg.classList.add('hidden');
                }, 4000);
            }, 1000);
        }
    </script>
    
    <!-- PWA Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('SW registered:', reg.scope))
                    .catch(err => console.error('SW registration failed:', err));
            });
        }
    </script>
</body>
</html>
