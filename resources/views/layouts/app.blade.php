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
    @if($seoImage)<meta property="og:image"        content="{{ $seoImage }}">@endif
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
    <link rel="manifest" href="/manifest.json?v=3">
    <meta name="theme-color" content="#1d4ed8">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <link rel="icon" href="/images/logo.png" type="image/png">
    <link rel="apple-touch-icon" href="/images/logo.png">

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
        if (typeof tailwind !== 'undefined') {
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
                color: #1e293b !important;
                font-weight: 600 !important;
            }
            .glass-card p.text-white,
            .glass-card span.text-white:not(.badge):not(.btn-primary),
            .glass-card h1.text-white,
            .glass-card h2.text-white,
            .glass-card h3.text-white,
            .glass-card h4.text-white,
            .glass-card .font-mono.text-white,
            .glass-card span.font-mono.text-white {
                color: #1e293b !important;
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
            
            // Safety timeout: always hide loader after 3 seconds to prevent getting stuck
            setTimeout(hideLoader, 3000);

            // Show loader on navigation (links, forms)
            document.addEventListener('click', function(e) {
                var a = e.target.closest('a');
                if (a && a.href && 
                    !a.href.startsWith('javascript') && 
                    !a.href.startsWith('#') && 
                    !a.href.startsWith('tel:') && 
                    !a.href.startsWith('mailto:') && 
                    !a.target && 
                    !a.hasAttribute('download') &&
                    !a.classList.contains('no-loader') &&
                    !a.href.includes('/pdf') &&
                    !a.href.includes('/download') &&
                    a.hostname === location.hostname) {
                    var el = document.getElementById('page-loader');
                    if (el) el.classList.remove('hidden-loader');
                }
            });
            document.addEventListener('submit', function(e) {
                if (e.defaultPrevented) return;
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
        $jwtToken = session('jwt_token') ?? \App\Helpers\JwtHelper::generateToken(Auth::user());
    @endphp
    <script>
        // Inject JWT Token for API requests
        window.jwtToken = '{{ $jwtToken }}';
        if (window.axios) {
            window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + window.jwtToken;
        }

        window.wsClient = {
            getVehicles: function(customerId) {
                return axios.get('/api/customers/' + customerId + '/vehicles')
                    .then(function(response) { return response.data; })
                    .catch(function(error) {
                        console.error('Error fetching vehicles:', error);
                        if (error.response && error.response.status === 401) {
                            alert('Session expired. Please refresh the page to continue.');
                        } else {
                            alert('Network error while fetching vehicles. Please check your connection and try again.');
                        }
                        throw error;
                    });
            },
            getBillTemplate: function(templateId) {
                return axios.get('/api/bill-templates/' + templateId)
                    .then(function(response) { return response.data; })
                    .catch(function(error) {
                        console.error('Error fetching bill template:', error);
                        if (error.response && error.response.status === 401) {
                            alert('Session expired. Please refresh the page to continue.');
                        } else {
                            alert('Network error while fetching template. Please check your connection and try again.');
                        }
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
                        {{ (Auth::user()->isSuperAdmin() && session()->has('active_workshop_id')) ? session('active_workshop_name') : (Auth::user()->isSuperAdmin() ? Auth::user()->name : (Auth::user()->workshop->name ?? 'Suhaim Soft')) }}
                    </h1>
                    @if(!Auth::user()->isSuperAdmin() || session()->has('active_workshop_id'))
                    <p class="text-xs text-slate-500 font-semibold mt-0.5">
                        {{ session()->has('active_workshop_id') ? session('active_workshop_admin_name', \App\Models\User::where('workshop_id', session('active_workshop_id'))->where('role', 'admin')->first()?->name ?? 'Workshop Admin') : Auth::user()->name }}
                    </p>
                    @endif
                    @if((!Auth::user()->isSuperAdmin() || session()->has('active_workshop_id')) && Auth::user()->workshop && Auth::user()->workshop->phone)
                    <p class="text-xs text-blue-500 font-medium mt-0.5">
                        {{ Auth::user()->workshop->phone }}
                    </p>
                    @endif
                    <p class="text-xs text-blue-600 font-bold tracking-wider uppercase mt-1">
                        {{ (Auth::user()->isSuperAdmin() && session()->has('active_workshop_id')) ? 'Workshop Owner' : (Auth::user()->isSuperAdmin() ? 'Suhaim Soft Super Admin' : (Auth::user()->role === 'admin' ? 'Workshop Owner' : 'Staff')) }}
                    </p>
                </div>
            </div>
        </div>

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
                Staff
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
            <a href="{{ route('system.index') }}" class="sidebar-link {{ request()->routeIs('system.*') ? 'active' : '' }}" id="nav-system-tenant">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                System Settings
            </a>
            @endif
            @endif
        </nav>

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
        @if(auth()->user()->workshop && in_array(auth()->user()->workshop->subscription_status, ['trial', 'training']))
        @php
            $workshop = auth()->user()->workshop;
            $trialEnds = \Carbon\Carbon::parse($workshop->trial_ends_at);
            // Calculate days left, making sure it shows 0 if it expires today
            $daysLeftDisplay = max(0, floor(now()->startOfDay()->diffInDays($trialEnds->startOfDay(), false)));
            $isTraining = $workshop->subscription_status === 'training';
        @endphp
        <div x-data="{ openLicenseActivationModal: false }">
            <div class="bg-amber-50 border-b border-amber-200 px-4 py-3 sm:px-6 lg:px-8 no-print shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <p class="text-sm font-semibold text-amber-800">
                            {{ $isTraining ? 'Training' : 'Trial' }} Status:
                            <span id="trial-countdown" class="font-bold">Loading...</span>
                            <span class="text-xs text-amber-700 font-medium ml-1">(Expires: <span id="trial-expiry-display"></span>)</span>
                            @if($workshop->isTrialExpired() && $workshop->restrict_features_on_expiry)
                                <span class="ml-2 text-rose-700 bg-rose-100/80 px-2 py-0.5 rounded border border-rose-200 text-xs font-bold whitespace-nowrap">Write actions restricted</span>
                            @endif
                        </p>
                    </div>
                    <button type="button" @click="openLicenseActivationModal = true" class="inline-flex items-center justify-center px-4 py-1.5 text-xs font-bold text-amber-900 bg-amber-200/50 hover:bg-amber-300/60 rounded-lg transition-colors border border-amber-300/80 shrink-0">
                        Activate Subscription
                    </button>
                </div>
            </div>

            <script>
            (function() {
                // Expiry from server — ISO string in UTC
                var expiryISO = '{{ $trialEnds->toIso8601String() }}';
                var expiryDate = new Date(expiryISO);
                var isTraining = {{ $isTraining ? 'true' : 'false' }};
                var label = isTraining ? 'Training' : 'Trial';

                function pad(n) { return String(n).padStart(2, '0'); }

                // Format expiry as local time
                function formatExpiry(d) {
                    var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                    var h = d.getHours();
                    var ampm = h >= 12 ? 'PM' : 'AM';
                    h = h % 12 || 12;
                    return pad(d.getDate()) + ' ' + months[d.getMonth()] + ' ' + d.getFullYear() +
                           ', ' + h + ':' + pad(d.getMinutes()) + ' ' + ampm;
                }

                var expiryEl   = document.getElementById('trial-expiry-display');
                var countdownEl = document.getElementById('trial-countdown');

                if (expiryEl) expiryEl.textContent = formatExpiry(expiryDate);

                function updateCountdown() {
                    var now  = new Date();
                    var diff = expiryDate - now; // ms

                    if (!countdownEl) return;

                    if (diff <= 0) {
                        countdownEl.textContent = 'Expired';
                        countdownEl.style.color = '#be123c';
                        return;
                    }

                    var totalSecs = Math.floor(diff / 1000);
                    var days  = Math.floor(totalSecs / 86400);
                    var hours = Math.floor((totalSecs % 86400) / 3600);
                    var mins  = Math.floor((totalSecs % 3600) / 60);
                    var secs  = totalSecs % 60;

                    var text;
                    if (days > 1) {
                        text = days + ' day' + (days !== 1 ? 's' : '') + ' ' + pad(hours) + 'h ' + pad(mins) + 'm remaining';
                    } else if (days === 1) {
                        text = '1 day ' + pad(hours) + 'h ' + pad(mins) + 'm remaining';
                    } else if (hours > 0) {
                        text = pad(hours) + 'h ' + pad(mins) + 'm ' + pad(secs) + 's remaining';
                    } else {
                        text = pad(mins) + 'm ' + pad(secs) + 's remaining';
                        countdownEl.style.color = '#dc2626'; // urgent red
                    }

                    countdownEl.textContent = text;
                }

                updateCountdown();
                setInterval(updateCountdown, 1000);
            })();
            </script>

            {{-- ══ Activation Modal (Admin Side) — New UI ══ --}}
            <div x-show="openLicenseActivationModal" x-cloak
                 class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center p-0 sm:p-4"
                 role="dialog" aria-modal="true"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">

                {{-- Backdrop --}}
                <div class="absolute inset-0" style="background:rgba(2,6,23,0.55);backdrop-filter:blur(6px);"
                     @click="openLicenseActivationModal = false"></div>

                {{-- Card --}}
                <div class="relative w-full sm:max-w-sm z-10 rounded-t-3xl sm:rounded-3xl overflow-hidden shadow-2xl"
                     style="background:#fff;"
                     x-transition:enter="transition ease-out duration-250 transform"
                     x-transition:enter-start="translate-y-8 sm:translate-y-0 sm:scale-95 opacity-0"
                     x-transition:enter-end="translate-y-0 sm:scale-100 opacity-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="translate-y-0 sm:scale-100 opacity-100"
                     x-transition:leave-end="translate-y-8 sm:translate-y-0 sm:scale-95 opacity-0">

                    {{-- Top gradient bar --}}
                    <div style="height:4px;background:linear-gradient(90deg,#6366f1,#2563eb,#0ea5e9);"></div>

                    <div class="px-6 pt-6 pb-5">

                        {{-- Header --}}
                        <div class="flex items-start justify-between mb-5">
                            <div class="flex items-center gap-3">
                                <div style="width:42px;height:42px;border-radius:14px;background:linear-gradient(135deg,#ede9fe,#dbeafe);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <svg width="20" height="20" fill="none" stroke="#4f46e5" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 style="font-size:1rem;font-weight:800;color:#0f172a;line-height:1.2;">Activate License</h3>
                                    <p style="font-size:0.72rem;color:#64748b;margin-top:2px;">Enter your key to unlock full access</p>
                                </div>
                            </div>
                            <button type="button" @click="openLicenseActivationModal = false"
                                    style="width:32px;height:32px;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#94a3b8;border:1px solid #e2e8f0;background:#fff;cursor:pointer;transition:all .15s;"
                                    onmouseover="this.style.background='#f1f5f9';this.style.color='#0f172a';"
                                    onmouseout="this.style.background='#fff';this.style.color='#94a3b8';">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        {{-- Form --}}
                        <form id="licenseActivateForm" action="{{ route('activate_license') }}" method="POST" novalidate>
                            @csrf

                            {{-- Session error --}}
                            @if(session('error'))
                            <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:10px 14px;display:flex;align-items:flex-start;gap:8px;margin-bottom:14px;">
                                <svg width="15" height="15" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span style="font-size:0.78rem;font-weight:600;color:#dc2626;">{{ session('error') }}</span>
                            </div>
                            @endif

                            {{-- Key Input --}}
                            <div style="margin-bottom:6px;">
                                <label style="display:block;font-size:0.68rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.08em;margin-bottom:8px;">
                                    License Key
                                </label>
                                <input id="pop_product_key" type="text" name="product_key"
                                       required autocomplete="off" spellcheck="false" maxlength="19"
                                       placeholder="XXXX-XXXX-XXXX-XXXX"
                                       style="width:100%;box-sizing:border-box;padding:14px 16px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:14px;font-family:'Courier New',monospace;font-size:1.05rem;font-weight:700;letter-spacing:.15em;text-align:center;text-transform:uppercase;color:#0f172a;outline:none;transition:all .2s;"
                                       onfocus="this.style.borderColor='#6366f1';this.style.background='#fff';this.style.boxShadow='0 0 0 4px rgba(99,102,241,0.12)';"
                                       onblur="this.style.borderColor='#e2e8f0';this.style.background='#f8fafc';this.style.boxShadow='none';">
                            </div>

                            {{-- Segment progress dots --}}
                            <div style="display:flex;gap:6px;margin-bottom:18px;" id="popKeySegs">
                                <div id="popSeg0" style="flex:1;height:3px;border-radius:999px;background:#e2e8f0;transition:background .25s;"></div>
                                <div id="popSeg1" style="flex:1;height:3px;border-radius:999px;background:#e2e8f0;transition:background .25s;"></div>
                                <div id="popSeg2" style="flex:1;height:3px;border-radius:999px;background:#e2e8f0;transition:background .25s;"></div>
                                <div id="popSeg3" style="flex:1;height:3px;border-radius:999px;background:#e2e8f0;transition:background .25s;"></div>
                            </div>

                            {{-- Inline validation msg --}}
                            <div id="popKeyError" style="display:none;background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:8px 12px;font-size:0.75rem;font-weight:600;color:#dc2626;margin-bottom:14px;"></div>

                            {{-- Buttons --}}
                            <div style="display:flex;gap:10px;">
                                <button type="button" @click="openLicenseActivationModal = false"
                                        style="flex:1;padding:12px;border-radius:14px;border:1.5px solid #e2e8f0;background:#fff;font-size:0.875rem;font-weight:700;color:#475569;cursor:pointer;transition:all .15s;"
                                        onmouseover="this.style.background='#f8fafc';" onmouseout="this.style.background='#fff';">
                                    Cancel
                                </button>
                                <button type="submit" id="popSubmitBtn"
                                        style="flex:2;padding:12px;border-radius:14px;border:none;background:linear-gradient(135deg,#4f46e5,#2563eb);color:#fff;font-size:0.875rem;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;transition:all .2s;box-shadow:0 4px 14px rgba(79,70,229,0.35);"
                                        onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 6px 20px rgba(79,70,229,0.45)';"
                                        onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 14px rgba(79,70,229,0.35)';">
                                    <svg id="popBtnIcon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                    </svg>
                                    <div id="popBtnSpinner" style="display:none;width:16px;height:16px;border:2.5px solid rgba(255,255,255,0.3);border-top-color:#fff;border-radius:50%;animation:popSpin .7s linear infinite;"></div>
                                    <span id="popBtnText">Activate Now</span>
                                </button>
                            </div>

                            {{-- Help text --}}
                            <p style="text-align:center;font-size:0.7rem;color:#94a3b8;margin-top:14px;">
                                Need a key? Call
                                <a href="tel:8891479505" style="color:#6366f1;font-weight:700;text-decoration:none;">8891479505</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>

            <style>
            @keyframes popSpin { to { transform: rotate(360deg); } }
            </style>

            <script>
            (function() {
                var inp  = document.getElementById('pop_product_key');
                var form = document.getElementById('licenseActivateForm');
                var segs = [
                    document.getElementById('popSeg0'),
                    document.getElementById('popSeg1'),
                    document.getElementById('popSeg2'),
                    document.getElementById('popSeg3'),
                ];
                var errEl   = document.getElementById('popKeyError');
                var spinner = document.getElementById('popBtnSpinner');
                var icon    = document.getElementById('popBtnIcon');
                var btnText = document.getElementById('popBtnText');
                var btn     = document.getElementById('popSubmitBtn');

                if (!inp) return;

                // Auto-format with dashes
                inp.addEventListener('input', function() {
                    var raw = this.value.replace(/[^A-Za-z0-9]/g, '').toUpperCase().slice(0, 16);
                    var fmt = '';
                    for (var i = 0; i < raw.length; i++) {
                        if (i > 0 && i % 4 === 0) fmt += '-';
                        fmt += raw[i];
                    }
                    this.value = fmt;
                    // Update segments
                    segs.forEach(function(s, i) {
                        var filled = raw.length >= (i + 1) * 4;
                        s.style.background = filled ? 'linear-gradient(90deg,#6366f1,#2563eb)' : '#e2e8f0';
                    });
                    errEl.style.display = 'none';
                    inp.style.borderColor = '#e2e8f0';
                });

                // Backspace over dashes
                inp.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && this.value.slice(-1) === '-') {
                        e.preventDefault();
                        this.value = this.value.slice(0, -2);
                        this.dispatchEvent(new Event('input'));
                    }
                });

                // Paste fix
                inp.addEventListener('paste', function(e) {
                    e.preventDefault();
                    var pasted = (e.clipboardData || window.clipboardData).getData('text');
                    var raw = pasted.replace(/[^A-Za-z0-9]/g, '').toUpperCase().slice(0, 16);
                    var fmt = '';
                    for (var i = 0; i < raw.length; i++) {
                        if (i > 0 && i % 4 === 0) fmt += '-';
                        fmt += raw[i];
                    }
                    this.value = fmt;
                    this.dispatchEvent(new Event('input'));
                });

                // Submit validation
                if (form) {
                    form.addEventListener('submit', function(e) {
                        var raw = inp.value.replace(/-/g, '');
                        if (raw.length < 16) {
                            e.preventDefault();
                            inp.style.borderColor = '#ef4444';
                            inp.style.boxShadow = '0 0 0 4px rgba(239,68,68,0.1)';
                            errEl.textContent = 'Please enter a complete 16-character license key.';
                            errEl.style.display = 'block';
                            inp.focus();
                            return;
                        }
                        // Loading state
                        btn.disabled = true;
                        spinner.style.display = 'block';
                        icon.style.display = 'none';
                        btnText.textContent = 'Activating…';
                    });
                }
            })();
            </script>
        </div>

        @endif
        
        @php
            $alertUser = auth()->user();
            $alertWorkshop = null;
            if ($alertUser) {
                if ($alertUser->isSuperAdmin() && session()->has('active_workshop_id')) {
                    $alertWorkshop = \App\Models\Workshop::find(session('active_workshop_id'));
                } else {
                    $alertWorkshop = $alertUser->workshop;
                }
            }
        @endphp
        
        @if($alertWorkshop && $alertWorkshop->alert_message)
            @if(!$alertWorkshop->alert_expires_at || now()->lessThan($alertWorkshop->alert_expires_at))
            <div x-data="{ openSystemAlert: false }" x-init="
                    const alertKey = 'admin_alert_' + {{ $alertWorkshop->id }} + '_' + '{{ md5($alertWorkshop->alert_message) }}';
                    if (!sessionStorage.getItem(alertKey)) {
                        openSystemAlert = true;
                        sessionStorage.setItem(alertKey, 'true');
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
                            playBeep(523.25, 0.1, 'sine');
                            playBeep(659.25, 0.25, 'sine');
                            playBeep(783.99, 0.4, 'sine');
                        } catch(e) {}
                    }
                ">
                
                {{-- Modal Overlay --}}
                <div x-show="openSystemAlert" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all"
                         @click.away="openSystemAlert = false"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                        
                        <div class="bg-amber-50 p-6 border-b border-amber-100 text-center">
                            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-amber-100 mb-4">
                                <svg class="h-8 w-8 text-amber-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <h3 class="text-xl font-extrabold text-amber-900">Message from System Admin</h3>
                        </div>
                        <div class="p-6 text-center">
                            <p class="text-base font-medium text-slate-700 whitespace-pre-wrap break-words">{{ $alertWorkshop->alert_message }}</p>
                        </div>
                        <div class="p-4 bg-slate-50 border-t border-slate-100 flex justify-center">
                            <button @click="openSystemAlert = false" type="button" class="px-6 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl shadow-sm transition-colors w-full sm:w-auto">
                                I Understand
                            </button>
                        </div>
                    </div>
                </div>
            </div>
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
                    .then(reg => {})
                    .catch(err => {});
            });
        }

        window.addEventListener('beforeinstallprompt', (e) => {
            if (installAppButton) {
                // Prevent standard install banner from appearing only if we have a custom button
                e.preventDefault();
                // Stash install prompt event
                deferredAppPrompt = e;
                // Show the install button container
                if (installAppContainer) {
                    installAppContainer.classList.remove('hidden');
                }
            }
            // If no custom button exists, we do NOT call preventDefault(),
            // allowing the browser to show its default install banner automatically
            // and preventing the console warning.
        });

        if (installAppButton) {
            installAppButton.addEventListener('click', async () => {
                if (!deferredAppPrompt) return;
                // Show prompt
                deferredAppPrompt.prompt();
                await deferredAppPrompt.userChoice;
                // Discard prompt
                deferredAppPrompt = null;
                // Hide button
                if (installAppContainer) {
                    installAppContainer.classList.add('hidden');
                }
            });
        }

        window.addEventListener('appinstalled', () => {
            deferredAppPrompt = null;
            if (installAppContainer) {
                installAppContainer.classList.add('hidden');
            }
        });
    </script>

    {{-- Global Toast Notifications --}}
    <div x-data="globalToastManager()" x-init="init()" class="fixed top-4 right-4 z-[9999] flex flex-col gap-2 max-w-sm w-full pointer-events-none" x-cloak>
        <template x-for="t in toasts" :key="t.id">
            <div x-show="t.show"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="pointer-events-auto bg-white rounded-xl p-3.5 flex items-start gap-3 shadow-lg border-l-4"
                 :class="t.type === 'success' ? 'border-emerald-500' : 'border-rose-500'">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0"
                     :class="t.type === 'success' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600'">
                    <template x-if="t.type === 'success'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </template>
                    <template x-if="t.type === 'error'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                    </template>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-[10px] font-extrabold uppercase tracking-widest"
                         :class="t.type === 'success' ? 'text-emerald-700' : 'text-rose-700'" x-text="t.title"></div>
                    <div class="text-xs font-semibold text-slate-600 mt-0.5" x-text="t.message"></div>
                </div>
                <button type="button" class="text-slate-400 hover:text-slate-700" @click="t.show = false">✕</button>
            </div>
        </template>
    </div>

    <script>
        function globalToastManager() {
            return {
                toasts: [],
                init() {
                    @if(session('success'))
                        this.add('success', 'Success', "{{ session('success') }}");
                    @endif
                    @if(session('error'))
                        this.add('error', 'Error', "{{ session('error') }}");
                    @endif
                    
                    window.addEventListener('toast', (e) => {
                        this.add(e.detail.type, e.detail.title, e.detail.message);
                    });
                },
                add(type, title, message) {
                    const id = Date.now();
                    this.toasts.push({ id, type, title, message, show: true });
                    setTimeout(() => {
                        const t = this.toasts.find(t => t.id === id);
                        if (t) t.show = false;
                    }, 5000);
                }
            }
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');

            if (mobileMenuBtn && sidebar && sidebarOverlay) {
                mobileMenuBtn.addEventListener('click', function() {
                    sidebar.classList.remove('-translate-x-full');
                    sidebarOverlay.classList.remove('hidden');
                });

                sidebarOverlay.addEventListener('click', function() {
                    sidebar.classList.add('-translate-x-full');
                    sidebarOverlay.classList.add('hidden');
                });
            }
        });

        window.toggleSidebar = function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            if (sidebar && sidebarOverlay) {
                sidebar.classList.toggle('-translate-x-full');
                sidebarOverlay.classList.toggle('hidden');
            }
        };
    </script>
    @stack('modals')
    @stack('scripts')
</body>
</html>
