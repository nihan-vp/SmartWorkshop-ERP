<!DOCTYPE html>
<html lang="en" prefix="og: https://ogp.me/ns#">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">

    {{-- ══════════════════════════════════════════════
         ADVANCED SEO SYSTEM — Suhaim Soft Work Shop
         Each page can override via @section('seo-*')
    ══════════════════════════════════════════════ --}}

    {{-- Core Title (Pages override with @section('title')) --}}
    @php
        $seoTitle      = trim(View::yieldContent('seo-title', View::yieldContent('title', 'Dashboard')));
        $siteName      = 'Suhaim Soft Work Shop';
        $fullTitle     = $seoTitle . ' — ' . $siteName;
        $seoDesc       = trim(View::yieldContent('seo-description', 'Suhaim Soft Work Shop is a complete cloud-based workshop management system — manage work orders, invoices, customers, vehicles, and employees from one powerful dashboard.'));
        $seoKeywords   = trim(View::yieldContent('seo-keywords', 'workshop management, work order system, auto repair software, garage management, invoice generator, customer vehicles, workshop dashboard, Suhaim Soft'));
        $seoImage      = trim(View::yieldContent('seo-image', ''));
        $canonicalUrl  = trim(View::yieldContent('seo-canonical', url()->current()));
        $seoRobots     = trim(View::yieldContent('seo-robots', 'index, follow'));
        $seoType       = trim(View::yieldContent('seo-type', 'website'));
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
                "@@type": "Organization",
                "@@id": "{{ url('/') }}/#organization",
                "name": "{{ $siteName }}",
                "url": "{{ url('/') }}",
                "logo": {
                    "@@type": "ImageObject",
                    "url": ""
                },
                "description": "Cloud-based workshop management system for auto repair shops and garages.",
                "contactPoint": {
                    "@@type": "ContactPoint",
                    "email": "infosuhaimsoft@gmail.com",
                    "contactType": "customer support"
                }
            },
            {
                "@@type": "WebSite",
                "@@id": "{{ url('/') }}/#website",
                "url": "{{ url('/') }}",
                "name": "{{ $siteName }}",
                "publisher": { "@@id": "{{ url('/') }}/#organization" }
            },
            {
                "@@type": "WebPage",
                "@@id": "{{ $canonicalUrl }}/#webpage",
                "url": "{{ $canonicalUrl }}",
                "name": "{{ $fullTitle }}",
                "description": "{{ $seoDesc }}",
                "isPartOf": { "@@id": "{{ url('/') }}/#website" },
                "inLanguage": "en-US",
                "dateModified": "{{ now()->toIso8601String() }}"
            }
        ]
    }
    </script>

    {{-- ── PWA Config ── --}}
    <link rel="manifest" href="/manifest.json?v=2">
    <meta name="theme-color" content="#1d4ed8">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <link rel="icon" href="/favicon.png" type="image/png">
    <link rel="apple-touch-icon" href="/favicon.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        // Suppress Tailwind CSS production warning
        (function() {
            const originalWarn = console.warn;
            console.warn = function(...args) {
                if (args[0] && typeof args[0] === 'string' && (args[0].includes('cdn.tailwindcss.com') || args[0].includes('Tailwind CSS'))) {
                    return;
                }
                originalWarn.apply(console, args);
            };
        })();
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#3b82f6',
                            500: '#2563eb',
                            600: '#1d4ed8',
                            700: '#1e40af',
                            800: '#1e3a8a',
                            900: '#172554',
                        }
                    }
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer base {
            * {
                scrollbar-width: thin;
                scrollbar-color: rgba(37, 99, 235, 0.3) transparent;
            }
            html {
                @apply bg-white text-blue-950 antialiased;
                overflow-x: hidden;
                overflow-y: auto;
            }
            body {
                @apply bg-white text-blue-950 antialiased;
            }
        }

        @layer components {
            .sidebar-link {
                @apply flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-blue-700/70 
                       transition-all duration-200 hover:bg-blue-50 hover:text-blue-700;
            }
            .sidebar-link.active {
                @apply bg-blue-600 text-white font-bold shadow-md shadow-blue-600/15;
            }
            .sidebar-link svg {
                @apply text-blue-400/80 transition-colors duration-200;
            }
            .sidebar-link:hover svg {
                @apply text-blue-600;
            }
            .sidebar-link.active svg {
                @apply text-white;
            }
            @keyframes spin-slow {
                from { transform: rotate(0deg); }
                to   { transform: rotate(360deg); }
            }
            .animate-spin-slow {
                animation: spin-slow 12s linear infinite;
            }
            .glass-card {
                @apply bg-white border border-blue-100 rounded-2xl p-6 shadow-sm 
                       transition-all duration-300 hover:shadow-md hover:border-blue-200;
            }
            .stat-card {
                @apply relative overflow-hidden rounded-2xl p-6 border border-blue-100 shadow-sm 
                       transition-all duration-300 hover:scale-[1.01] hover:shadow-md;
            }
            .btn-primary {
                @apply inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 
                       text-white text-sm font-bold rounded-xl shadow-sm 
                       transition-all duration-200 hover:bg-blue-700 hover:shadow-md active:scale-95;
            }
            .btn-secondary {
                @apply inline-flex items-center gap-2 px-5 py-2.5 bg-slate-100 text-slate-800 text-sm font-semibold 
                       rounded-xl border border-slate-200 transition-all duration-200 hover:bg-slate-200/60 active:scale-95;
            }
            .btn-danger {
                @apply inline-flex items-center gap-2 px-4 py-2 bg-red-500/10 text-red-600 text-sm font-semibold 
                       rounded-xl border border-red-500/20 transition-all duration-200 hover:bg-red-500/20 active:scale-95;
            }
            .form-input {
                @apply w-full bg-white border border-blue-100 rounded-xl px-4 py-3 text-slate-900 placeholder-slate-400 
                       text-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 
                       focus:border-blue-500 focus:bg-white;
            }
            .form-select {
                @apply w-full bg-white border border-blue-100 rounded-xl px-4 py-3 text-blue-900 text-sm 
                       transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 
                       focus:border-blue-500 appearance-none;
            }
            .form-label {
                @apply block text-sm font-bold text-blue-900 mb-2;
            }
            .data-table {
                @apply w-full text-left;
            }
            .overflow-x-auto {
                -webkit-overflow-scrolling: touch;
            }
            .overflow-x-auto::-webkit-scrollbar {
                height: 5px;
            }
            .overflow-x-auto::-webkit-scrollbar-track {
                background: transparent;
            }
            .overflow-x-auto::-webkit-scrollbar-thumb {
                background: rgba(156, 163, 175, 0.25);
                border-radius: 99px;
            }
            .overflow-x-auto::-webkit-scrollbar-thumb:hover {
                background: rgba(156, 163, 175, 0.45);
            }
            .data-table thead th {
                @apply px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 
                       border-b border-slate-200/80 bg-slate-50/50 whitespace-nowrap;
            }
            .data-table tbody td {
                @apply px-5 py-4 text-sm text-slate-600 border-b border-slate-100 whitespace-nowrap;
            }
            .data-table tbody td.text-white,
            .data-table tbody td .text-white {
                @apply text-slate-800 font-semibold !important;
            }
            .glass-card p.text-white,
            .glass-card span.text-white:not(.badge):not(.btn-primary),
            .glass-card h1.text-white,
            .glass-card h2.text-white,
            .glass-card h3.text-white,
            .glass-card h4.text-white,
            .glass-card font-mono.text-white,
            .glass-card span.font-mono.text-white {
                @apply text-slate-800 !important;
            }
            .data-table tbody tr {
                @apply transition-all duration-150 hover:bg-slate-50/50;
            }

            /* ── Responsive Card Layout for Mobile ── */
            @media (max-width: 767px) {
                .data-table thead {
                    display: none;
                }
                .data-table tbody tr {
                    display: block;
                    background: white;
                    border: 1px solid #e2e8f0;
                    border-radius: 1rem;
                    padding: 1rem 1.125rem;
                    margin-bottom: 0.75rem;
                    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
                }
                .data-table tbody tr:hover {
                    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
                }
                .data-table tbody td {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 0.4rem 0;
                    border-bottom: 1px solid #f1f5f9;
                    white-space: normal;
                    word-break: break-word;
                    font-size: 0.8125rem;
                }
                .data-table tbody td:last-child {
                    border-bottom: none;
                    padding-top: 0.75rem;
                    justify-content: flex-end;
                }
                .data-table tbody td::before {
                    content: attr(data-label);
                    font-weight: 700;
                    font-size: 0.6875rem;
                    color: #2563eb;
                    text-transform: uppercase;
                    letter-spacing: 0.05em;
                    flex-shrink: 0;
                    margin-right: 1rem;
                    min-width: 6rem;
                }
                .data-table tbody td:last-child::before {
                    display: none;
                }
                .data-table tbody td[data-label=""]::before,
                .data-table tbody td:not([data-label])::before {
                    display: none;
                }
                /* Empty state full width */
                .data-table tbody td[colspan] {
                    display: block;
                    text-align: center;
                }
                .data-table tbody td[colspan]::before {
                    display: none;
                }
            }

            /* ── Tablet: Compact table ── */
            @media (min-width: 768px) and (max-width: 1023px) {
                .data-table thead th {
                    padding-left: 0.75rem;
                    padding-right: 0.75rem;
                    font-size: 0.625rem;
                }
                .data-table tbody td {
                    padding-left: 0.75rem;
                    padding-right: 0.75rem;
                    font-size: 0.8125rem;
                }
            }

            /* Scroll hint gradient */
            .table-scroll-wrapper {
                position: relative;
            }
            .table-scroll-wrapper::after {
                content: '';
                position: absolute;
                top: 0;
                right: 0;
                bottom: 0;
                width: 2rem;
                background: linear-gradient(to right, transparent, rgba(255,255,255,0.85));
                pointer-events: none;
                opacity: 0;
                transition: opacity 0.2s;
            }
            @media (min-width: 768px) {
                .table-scroll-wrapper.has-scroll::after {
                    opacity: 1;
                }
            }
            .badge {
                @apply inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold;
            }
            .badge-success { @apply bg-emerald-500/10 text-emerald-600; }
            .badge-warning { @apply bg-amber-500/10 text-amber-600; }
            .badge-danger  { @apply bg-red-500/10 text-red-600; }
            .badge-info    { @apply bg-sky-500/10 text-sky-600; }
            .badge-purple  { @apply bg-violet-500/10 text-violet-600; }

            /* ── Responsive Modals ── */
            @media (max-width: 640px) {
                .fixed.inset-0.z-50 > div,
                .fixed.inset-0.z-\[100\] > div:not(.absolute) {
                    max-width: 95% !important;
                    padding: 1.25rem !important;
                    margin: auto 0.5rem !important;
                    border-radius: 1.5rem !important;
                    max-height: 88vh !important;
                    overflow-y: auto !important;
                }
                .fixed.inset-0.z-50 form,
                .fixed.inset-0.z-\[100\] form {
                    margin-top: 0.75rem !important;
                }
                .fixed.inset-0.z-50 form .grid,
                .fixed.inset-0.z-\[100\] form .grid {
                    grid-template-columns: 1fr !important;
                    gap: 1rem !important;
                }
                .fixed.inset-0.z-50 form .col-span-2,
                .fixed.inset-0.z-\[100\] form .col-span-2 {
                    grid-column: span 1 / span 1 !important;
                }
            }
        }

        @layer utilities {
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(20px); }
                to   { opacity: 1; transform: translateY(0); }
            }
            @keyframes slideInLeft {
                from { opacity: 0; transform: translateX(-20px); }
                to   { opacity: 1; transform: translateX(0); }
            }
            @keyframes countUp {
                from { opacity: 0; transform: scale(0.5); }
                to   { opacity: 1; transform: scale(1); }
            }
            @keyframes pulse-glow {
                0%, 100% { box-shadow: 0 0 15px rgba(37, 99, 235, 0.1); }
                50%      { box-shadow: 0 0 30px rgba(37, 99, 235, 0.2); }
            }
            .animate-fade-in-up {
                animation: fadeInUp 0.5s ease-out forwards;
            }
            .animate-slide-in-left {
                animation: slideInLeft 0.4s ease-out forwards;
            }
            .animate-count-up {
                animation: countUp 0.6s ease-out forwards;
            }
            .animate-pulse-glow {
                animation: pulse-glow 3s ease-in-out infinite;
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.8/dist/axios.min.js"></script>
    <script>
        window.axios = axios;
        window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        
        // Add CSRF token to Axios requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken.content;
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>[x-cloak] { display: none !important; }</style>

    {{-- ── Page Loading Overlay Styles ── --}}
    <style>
        #page-loader {
            position: fixed;
            inset: 0;
            z-index: 99999;
            background: linear-gradient(135deg, #eff6ff 0%, #ffffff 50%, #f0f9ff 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1.5rem;
            transition: opacity 0.4s ease, visibility 0.4s ease;
        }
        #page-loader.hidden-loader {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }
        .loader-ring {
            position: relative;
            width: 72px;
            height: 72px;
        }
        .loader-ring svg {
            animation: rotate-loader 1.6s linear infinite;
            transform-origin: center;
        }
        .loader-ring svg circle.track {
            stroke: #dbeafe;
            stroke-width: 6;
            fill: none;
        }
        .loader-ring svg circle.fill {
            stroke: url(#loaderGrad);
            stroke-width: 6;
            fill: none;
            stroke-linecap: round;
            stroke-dasharray: 120 200;
            animation: dash-loader 1.6s ease-in-out infinite, rotate-loader 1.6s linear infinite;
            transform-origin: center;
        }
        @keyframes rotate-loader {
            100% { transform: rotate(360deg); }
        }
        @keyframes dash-loader {
            0%   { stroke-dasharray: 10 200; stroke-dashoffset: 0; }
            50%  { stroke-dasharray: 140 200; stroke-dashoffset: -60; }
            100% { stroke-dasharray: 10 200; stroke-dashoffset: -200; }
        }
        .loader-logo {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .loader-dots {
            display: flex;
            gap: 6px;
        }
        .loader-dots span {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #2563eb;
            animation: dot-bounce 1.4s ease-in-out infinite;
        }
        .loader-dots span:nth-child(1) { animation-delay: 0s; }
        .loader-dots span:nth-child(2) { animation-delay: 0.2s; background: #4f46e5; }
        .loader-dots span:nth-child(3) { animation-delay: 0.4s; background: #7c3aed; }
        @keyframes dot-bounce {
            0%, 80%, 100% { transform: translateY(0); opacity: 0.4; }
            40%            { transform: translateY(-10px); opacity: 1; }
        }
        .loader-text {
            font-family: 'Inter', sans-serif;
            font-size: 0.8rem;
            font-weight: 700;
            color: #64748b;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            animation: pulse-text 1.6s ease-in-out infinite;
        }
        @keyframes pulse-text {
            0%, 100% { opacity: 0.5; }
            50%       { opacity: 1; }
        }
    </style>
</head>
<body class="min-h-screen {{ Auth::check() ? 'bg-slate-50' : 'bg-gradient-to-br from-blue-50/50 via-white to-slate-50 relative flex flex-col items-center justify-center py-8 px-4' }}">

    {{-- ══ FULL PAGE LOADING OVERLAY ══ --}}
    <div id="page-loader" role="status" aria-label="Loading page...">
        <div class="loader-ring">
            <svg viewBox="0 0 72 72" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="loaderGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#2563eb"/>
                        <stop offset="50%" stop-color="#4f46e5"/>
                        <stop offset="100%" stop-color="#7c3aed"/>
                    </linearGradient>
                </defs>
                <circle class="track" cx="36" cy="36" r="30"/>
                <circle class="fill" cx="36" cy="36" r="30"/>
            </svg>
            <div class="loader-logo">
                <svg width="24" height="24" viewBox="0 0 512 512" fill="#2563eb">
                    <path d="M 334 165 C 334 165 298 140 256 140 C 214 140 178 165 178 210 C 178 260 230 275 270 285 C 300 292 342 308 342 355 C 342 410 290 432 256 432 C 210 432 170 410 170 410 L 182 355 C 182 355 220 380 256 380 C 300 380 342 360 342 315 C 342 265 285 245 242 235 C 208 227 170 205 170 160 C 170 100 226 80 256 80 C 306 80 342 105 342 105 Z" />
                </svg>
            </div>
        </div>
        <div class="loader-dots">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <p class="loader-text">Please wait...</p>
    </div>
    <script>
        // Hide loader when DOM + resources ready
        (function() {
            function hideLoader() {
                var el = document.getElementById('page-loader');
                if (el) el.classList.add('hidden-loader');
            }
            if (document.readyState === 'complete') {
                setTimeout(hideLoader, 200);
            } else {
                window.addEventListener('load', function() {
                    setTimeout(hideLoader, 200);
                });
            }
            // Show loader on navigation (links, forms)
            document.addEventListener('click', function(e) {
                var a = e.target.closest('a');
                if (a && a.href && !a.href.startsWith('javascript') && !a.href.startsWith('#') && !a.href.startsWith('tel:') && !a.href.startsWith('mailto:') && !a.target && a.hostname === location.hostname) {
                    var el = document.getElementById('page-loader');
                    if (el) el.classList.remove('hidden-loader');
                }
            });
            document.addEventListener('submit', function() {
                var el = document.getElementById('page-loader');
                if (el) el.classList.remove('hidden-loader');
            });
        })();
    </script>
    @guest
    {{-- Decorative Background Gradients for login --}}
    <div class="absolute top-[-20%] left-[-10%] w-[50%] h-[50%] rounded-full bg-blue-400/10 blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-[-20%] right-[-10%] w-[50%] h-[50%] rounded-full bg-indigo-400/10 blur-[120px] pointer-events-none"></div>
    @endguest

    @auth
    @php
        $wsUserId = Auth::id();
        $wsTime = time();
        $wsKey = config('app.key');
        $wsCleanKey = str_replace('base64:', '', $wsKey);
        $wsSignature = hash_hmac('sha256', $wsUserId . ':' . $wsTime, $wsCleanKey);
    @endphp
    <script>
        window.wsAuth = {
            userId: '{{ $wsUserId }}',
            time: '{{ $wsTime }}',
            signature: '{{ $wsSignature }}'
        };

        window.wsClient = {
            getVehicles: function(customerId) {
                return axios.get('/api/customers/' + customerId + '/vehicles')
                    .then(response => response.data)
                    .catch(error => {
                        console.error('Error fetching vehicles:', error);
                        throw error;
                    });
            },
            
            getBillTemplate: function(templateId) {
                return axios.get('/api/bill-templates/' + templateId)
                    .then(response => response.data)
                    .catch(error => {
                        console.error('Error fetching bill template:', error);
                        throw error;
                    });
            }
        };
    </script>

    {{-- Mobile Overlay --}}
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

    {{-- Sidebar --}}
    <aside id="sidebar" class="fixed top-0 left-0 z-50 h-full w-72 bg-white border-r border-blue-100 
                               transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-out flex flex-col shadow-sm">
        {{-- Logo --}}
        <div class="p-6 border-b border-blue-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-600 to-blue-500 flex items-center justify-center shadow-lg shadow-blue-500/30 shrink-0">
                    <svg width="24" height="24" viewBox="0 0 512 512" fill="currentColor" class="text-white">
                        <path d="M 334 165 C 334 165 298 140 256 140 C 214 140 178 165 178 210 C 178 260 230 275 270 285 C 300 292 342 308 342 355 C 342 410 290 432 256 432 C 210 432 170 410 170 410 L 182 355 C 182 355 220 380 256 380 C 300 380 342 360 342 315 C 342 265 285 245 242 235 C 208 227 170 205 170 160 C 170 100 226 80 256 80 C 306 80 342 105 342 105 Z" />
                    </svg>
                </div>

                <div>
                    <h1 class="text-lg font-bold text-blue-900 leading-tight">
                        {{ (Auth::user()->isSuperAdmin() && session()->has('active_workshop_id')) ? session('active_workshop_name') : (Auth::user()->isSuperAdmin() ? 'System Admin' : (Auth::user()->workshop->name ?? 'Suhaim Soft')) }}
                    </h1>
                    @if((!Auth::user()->isSuperAdmin() || session()->has('active_workshop_id')) && Auth::user()->workshop && Auth::user()->workshop->phone)
                    <p class="text-xs text-blue-500 font-medium">
                        {{ Auth::user()->workshop->phone }}
                    </p>
                    @endif
                    <p class="text-xs text-blue-600 font-bold tracking-wider uppercase mt-0.5">
              {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto p-4 space-y-1">
            @if(Auth::user()->isSuperAdmin() && session()->has('active_workshop_id'))
            <a href="{{ route('super_admin.dashboard') }}" class="sidebar-link !bg-indigo-50 !text-indigo-700 hover:!bg-indigo-100 hover:!text-indigo-900 font-bold border border-indigo-200 rounded-xl mb-4" id="nav-back-to-admin">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 0118 0z"/></svg>
                Exit Inspection
            </a>
            @endif

            @if(Auth::user()->isSuperAdmin() && !session()->has('active_workshop_id'))
            <a href="{{ route('super_admin.dashboard', ['tab' => 'dashboard']) }}" class="sidebar-link {{ request()->routeIs('super_admin.dashboard') && request('tab', 'dashboard') === 'dashboard' ? 'active' : '' }}" id="nav-super-dashboard">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
            <a href="{{ route('super_admin.dashboard', ['tab' => 'workshops']) }}" class="sidebar-link {{ request()->routeIs('super_admin.dashboard') && request('tab') === 'workshops' ? 'active' : '' }}" id="nav-workshops">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                Garages / Workshops
            </a>
            <a href="{{ route('super_admin.dashboard', ['tab' => 'keys']) }}" class="sidebar-link {{ request()->routeIs('super_admin.dashboard') && request('tab') === 'keys' ? 'active' : '' }}" id="nav-keys">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m-9 8a2 2 0 012-2m7-3a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
                Product Keys
            </a>
            <a href="{{ route('super_admin.dashboard', ['tab' => 'settings']) }}" class="sidebar-link {{ request()->routeIs('super_admin.dashboard') && request('tab') === 'settings' ? 'active' : '' }}" id="nav-settings">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                System Settings
            </a>
            <a href="{{ route('super_admin.dashboard', ['tab' => 'logs']) }}" class="sidebar-link {{ request()->routeIs('super_admin.dashboard') && request('tab') === 'logs' ? 'active' : '' }}" id="nav-logs">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 12h6m-6 4h6"/></svg>
                Activity Logs
            </a>
            <a href="{{ route('backup.index') }}" class="sidebar-link {{ request()->routeIs('backup.*') ? 'active' : '' }}" id="nav-backup">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>
                Backup & Restore
            </a>
            @endif

            @if(!Auth::user()->isSuperAdmin() || session()->has('active_workshop_id'))
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" id="nav-dashboard">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
            <a href="{{ route('bills.index') }}" class="sidebar-link {{ request()->routeIs('bills.*') && !request()->routeIs('bills.create') ? 'active' : '' }}" id="nav-bills">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                Invoices
            </a>

            <a href="{{ route('bill-templates.index') }}" class="sidebar-link {{ request()->routeIs('bill-templates.*') ? 'active' : '' }}" id="nav-bill-templates">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                Bill Templates
            </a>

            <a href="{{ route('customers.index') }}" class="sidebar-link {{ request()->routeIs('customers.*') ? 'active' : '' }}" id="nav-customers">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Customers
            </a>

            <a href="{{ route('vehicles.index') }}" class="sidebar-link {{ request()->routeIs('vehicles.*') ? 'active' : '' }}" id="nav-vehicles">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 17h8M8 17v4H6v-4m2 0H4.5A1.5 1.5 0 013 15.5v-3.194a1.5 1.5 0 01.138-.632L5 8h14l1.862 3.674c.09.2.138.416.138.632V15.5a1.5 1.5 0 01-1.5 1.5H16m0 0v4h-2v-4m0 0H8M7 11h.01M17 11h.01"/></svg>
                Vehicles
            </a>

            <a href="{{ route('services.index') }}" class="sidebar-link {{ request()->routeIs('services.*') ? 'active' : '' }}" id="nav-services">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Services
            </a>

            <a href="{{ route('products.index') }}" class="sidebar-link {{ request()->routeIs('products.*') ? 'active' : '' }}" id="nav-products">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Products / Stock
            </a>

            <a href="{{ route('expenses.index') }}" class="sidebar-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}" id="nav-expenses">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Expenses
            </a>

            <a href="{{ route('purchases.index') }}" class="sidebar-link {{ request()->routeIs('purchases.*') ? 'active' : '' }}" id="nav-purchases">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Purchases
            </a>

            <a href="{{ route('salaries.index') }}" class="sidebar-link {{ request()->routeIs('salaries.*') ? 'active' : '' }}" id="nav-salaries">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Salaries
            </a>

            <a href="{{ route('employees.index') }}" class="sidebar-link {{ request()->routeIs('employees.*') ? 'active' : '' }}" id="nav-employees">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Employees
            </a>

            <a href="{{ route('work-orders.index') }}" class="sidebar-link {{ request()->routeIs('work-orders.*') ? 'active' : '' }}" id="nav-work-orders">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                Work Orders
            </a>

            <a href="{{ route('warranties.index') }}" class="sidebar-link {{ request()->routeIs('warranties.*') ? 'active' : '' }}" id="nav-warranties">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                Warranty
            </a>
            @if(Auth::user()->role === 'admin' || session()->has('active_workshop_id'))
            <a href="{{ route('offline-viewer.index') }}" class="sidebar-link {{ request()->routeIs('offline-viewer.*') ? 'active' : '' }}" id="nav-offline-viewer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Offline Viewer
            </a>
            @endif

            <a href="{{ route('backup.index') }}" class="sidebar-link {{ request()->routeIs('backup.*') ? 'active' : '' }}" id="nav-backup-tenant">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>
                Backup & Restore
            </a>

            @if(Auth::user()->role === 'admin' || session()->has('active_workshop_id'))
            <a href="{{ route('system.index') }}" class="sidebar-link {{ request()->routeIs('system.*') ? 'active' : '' }}" id="nav-system-tenant">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                System Settings
            </a>
            @endif
            @endif
        </nav>

        {{-- Sidebar Footer --}}
        <div class="p-4 border-t border-blue-100 no-print">
            <div id="pwa-install-container" class="hidden mb-3">
                <button id="pwa-install-button" class="w-full py-2.5 px-3.5 bg-gradient-to-br from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-bold text-xs rounded-xl shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 active:scale-95 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Install App</span>
                </button>
            </div>
            <div class="glass-card !p-3 !rounded-xl !bg-blue-50/50 !border-blue-100 shadow-sm text-center">
                <p class="text-xs text-blue-900 font-bold">
                    {{ (Auth::user()->isSuperAdmin() && session()->has('active_workshop_id')) ? 'Inspecting: ' . session('active_workshop_name') : (Auth::user()->isSuperAdmin() ? 'System Super Admin' : (Auth::user()->workshop->name ?? 'Suhaim Soft')) }}
                </p>
                <p class="text-[10px] text-blue-400 mt-0.5">© {{ date('Y') }} All Rights Reserved</p>
            </div>
        </div>
    </aside>
    @endauth

    {{-- Main Content --}}
    <div class="{{ Auth::check() ? 'lg:ml-72 justify-start' : 'w-full max-w-md mx-auto' }} {{ Auth::check() ? 'min-h-screen' : '' }} flex flex-col">
        @auth
        {{-- Top Bar --}}
        <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-slate-200/60 no-print">
            <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 h-16 gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    {{-- Mobile Menu Toggle --}}
                    <button onclick="toggleSidebar()" class="lg:hidden p-2 -ml-2 rounded-xl hover:bg-slate-50 text-slate-500 hover:text-slate-950 transition-colors shrink-0" id="menu-toggle">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>

                    {{-- Page Title --}}
                    <div class="flex flex-col justify-center min-w-0">
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 leading-tight truncate">@yield('page-title', 'Dashboard')</h2>
                        <p class="hidden sm:block text-[10px] sm:text-xs text-slate-500 mt-0.5 truncate">
                            Welcome to {{ (Auth::user()->isSuperAdmin() && session()->has('active_workshop_id')) ? 'Garage Inspection' : (Auth::user()->isSuperAdmin() ? 'Suhaim Soft Control Panel' : (Auth::user()->workshop->name ?? 'Suhaim Soft Work Shop')) }}
                        </p>
                    </div>
                </div>

                {{-- Right side --}}
                <div class="flex items-center gap-2 sm:gap-4 shrink-0">
                    <div class="flex items-center gap-1 sm:gap-2 px-2 py-1.5 sm:px-4 sm:py-2 bg-slate-50 rounded-lg sm:rounded-xl border border-slate-200 overflow-hidden text-ellipsis whitespace-nowrap min-w-0">
                        <svg class="hidden sm:block w-3.5 h-3.5 sm:w-4 sm:h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span id="live-clock" class="text-[9px] sm:text-xs text-slate-600 font-semibold truncate tracking-tighter sm:tracking-normal">{{ now()->format('d M Y h:i A') }}</span>
                    </div>
                    
                    <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
                        @if(Auth::user()->isSuperAdmin() || Auth::user()->workshop)
                        <button onclick="window.location.reload()" class="hidden sm:flex items-center gap-1.5 px-2.5 py-1.5 sm:px-4 sm:py-2 bg-slate-50 rounded-lg sm:rounded-xl border border-slate-200 hover:bg-slate-100 transition-all active:scale-95" title="Reload Page">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            <span class="hidden sm:inline text-xs text-slate-600 font-semibold">Reload</span>
                        </button>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" class="inline m-0">
                            @csrf
                            <button type="submit" class="inline-flex items-center justify-center gap-1.5 p-2 sm:px-4 sm:py-2 text-[10px] sm:text-xs font-semibold text-rose-600 bg-rose-50 border border-rose-100 hover:bg-rose-100/60 rounded-lg sm:rounded-xl transition-all active:scale-95" title="Sign Out">
                                <svg class="w-5 h-5 sm:w-4 sm:h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                <span class="hidden sm:inline">Sign Out</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>
        @endauth

        @auth
        @if(auth()->user()->workshop && auth()->user()->workshop->subscription_status === 'trial')
        @php
            $workshop = auth()->user()->workshop;
            $trialEnds = \Carbon\Carbon::parse($workshop->trial_ends_at);
            // Calculate days left, making sure it shows 0 if it expires today
            $daysLeftDisplay = max(0, floor(now()->startOfDay()->diffInDays($trialEnds->startOfDay(), false)));
        @endphp
        <div class="bg-amber-50 border-b border-amber-200 px-4 py-3 sm:px-6 lg:px-8 no-print shadow-sm">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <p class="text-sm font-semibold text-amber-800">
                        Trial Status: <span class="font-bold">{{ $daysLeftDisplay == 0 ? 'Expires Today' : $daysLeftDisplay . ' ' . ($daysLeftDisplay === 1 ? 'day' : 'days') . ' remaining' }}</span>
                        @if($workshop->isTrialExpired() && $workshop->restrict_features_on_expiry)
                            <span class="ml-2 text-rose-700 bg-rose-100/80 px-2 py-0.5 rounded border border-rose-200 text-xs font-bold whitespace-nowrap">Write actions restricted</span>
                        @endif
                    </p>
                </div>
                <a href="{{ route('license.activate') }}" class="inline-flex items-center justify-center px-4 py-1.5 text-xs font-bold text-amber-900 bg-amber-200/50 hover:bg-amber-300/60 rounded-lg transition-colors border border-amber-300/80 shrink-0">
                    Activate Subscription
                </a>
            </div>
        </div>
        @endif
        
        @if(auth()->user()->workshop && auth()->user()->workshop->alert_message)
            @php
                $alertWorkshop = auth()->user()->workshop;
            @endphp
            @if(!$alertWorkshop->alert_expires_at || now()->lessThan($alertWorkshop->alert_expires_at))
            <div class="bg-indigo-50 border-b border-indigo-200 px-4 py-3 sm:px-6 lg:px-8 no-print">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 w-full">
                    <div class="flex items-start gap-3 flex-1 min-w-0">
                        <div class="mt-0.5 text-indigo-600 shrink-0">
                            <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-sm font-bold text-indigo-800 truncate">Message from System Admin</h3>
                            <p class="text-xs font-medium text-indigo-700 mt-1 whitespace-pre-wrap break-words">{{ $alertWorkshop->alert_message }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const alertKey = 'admin_alert_' + {{ $alertWorkshop->id }} + '_' + "{{ md5($alertWorkshop->alert_message) }}";
                    if (!sessionStorage.getItem(alertKey)) {
                        try {
                            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                            function playBeep(freq, time, type) {
                                const osc = audioCtx.createOscillator();
                                const gain = audioCtx.createGain();
                                osc.type = type;
                                osc.frequency.setValueAtTime(freq, audioCtx.currentTime);
                                osc.connect(gain);
                                gain.connect(audioCtx.destination);
                                osc.start(audioCtx.currentTime + time);
                                gain.gain.exponentialRampToValueAtTime(0.00001, audioCtx.currentTime + time + 0.3);
                                osc.stop(audioCtx.currentTime + time + 0.4);
                            }
                            // Play gentle chime
                            playBeep(523.25, 0.1, 'sine'); // C5
                            playBeep(659.25, 0.25, 'sine'); // E5
                            playBeep(783.99, 0.4, 'sine'); // G5
                            sessionStorage.setItem(alertKey, 'true');
                        } catch(e) {
                            console.log('Audio blocked', e);
                        }
                    }
                });
            </script>
            @endif
        @endif
        @endauth





        {{-- Page Content --}}
        <main class="{{ Auth::check() ? 'p-4 sm:p-6 lg:p-8' : '' }}">
            @yield('content')
        </main>
    </div>

    {{-- Sidebar Toggle Script --}}
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        function updateClock() {
            const clockElement = document.getElementById('live-clock');
            if (clockElement) {
                const now = new Date();
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                const d = now.getDate().toString().padStart(2, '0');
                const m = months[now.getMonth()];
                const y = now.getFullYear();
                let hr = now.getHours();
                const ampm = hr >= 12 ? 'PM' : 'AM';
                hr = hr % 12;
                hr = hr ? hr : 12; // the hour '0' should be '12'
                hr = hr.toString().padStart(2, '0');
                const min = now.getMinutes().toString().padStart(2, '0');
                
                clockElement.innerText = `${d} ${m} ${y} ${hr}:${min} ${ampm}`;
            }
        }
        setInterval(updateClock, 1000);
    </script>
    {{-- PWA Service Worker Registration & Custom Install Banner --}}
    <script>
        let deferredAppPrompt;
        const installAppContainer = document.getElementById('pwa-install-container');
        const installAppButton = document.getElementById('pwa-install-button');

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('Service Worker registered with scope:', reg.scope))
                    .catch(err => console.error('Service Worker registration failed:', err));
            });
        }

        window.addEventListener('beforeinstallprompt', (e) => {
            // Prevent standard install banner from appearing
            e.preventDefault();
            // Stash install prompt event
            deferredAppPrompt = e;
            // Show the install button container
            if (installAppContainer) {
                installAppContainer.classList.remove('hidden');
            }
        });

        if (installAppButton) {
            installAppButton.addEventListener('click', async () => {
                if (!deferredAppPrompt) return;
                // Show prompt
                deferredAppPrompt.prompt();
                const { outcome } = await deferredAppPrompt.userChoice;
                console.log(`PWA installation outcome: ${outcome}`);
                // Discard prompt
                deferredAppPrompt = null;
                // Hide button
                if (installAppContainer) {
                    installAppContainer.classList.add('hidden');
                }
            });
        }

        window.addEventListener('appinstalled', (evt) => {
            console.log('PWA app installed successfully');
            deferredAppPrompt = null;
            if (installAppContainer) {
                installAppContainer.classList.add('hidden');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
