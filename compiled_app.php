<!DOCTYPE html>
<html lang="en" prefix="og: https://ogp.me/ns#">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    

    
    <?php
        $seoTitle      = trim(View::yieldContent('seo-title', View::yieldContent('title', 'Dashboard')));
        $siteName      = 'Suhaim Soft Work Shop';
        $fullTitle     = $seoTitle . ' — ' . $siteName;
        $seoDesc       = trim(View::yieldContent('seo-description', 'Suhaim Soft Work Shop is a complete cloud-based workshop management system — manage work orders, invoices, customers, vehicles, and employees from one powerful dashboard.'));
        $seoKeywords   = trim(View::yieldContent('seo-keywords', 'workshop management, work order system, auto repair software, garage management, invoice generator, customer vehicles, workshop dashboard, Suhaim Soft'));
        $seoImage      = trim(View::yieldContent('seo-image', asset('icons/icon.svg')));
        $canonicalUrl  = trim(View::yieldContent('seo-canonical', url()->current()));
        $seoRobots     = trim(View::yieldContent('seo-robots', 'index, follow'));
        $seoType       = trim(View::yieldContent('seo-type', 'website'));
        $seoLocale     = 'en_US';
    ?>

    
    <title><?php echo e($fullTitle); ?></title>
    <meta name="title"           content="<?php echo e($fullTitle); ?>">
    <meta name="description"     content="<?php echo e($seoDesc); ?>">
    <meta name="keywords"        content="<?php echo e($seoKeywords); ?>">
    <meta name="robots"          content="<?php echo e($seoRobots); ?>">
    <meta name="author"          content="Suhaim Soft">
    <meta name="copyright"       content="© <?php echo e(date('Y')); ?> Suhaim Soft Work Shop">
    <meta name="generator"       content="Suhaim Soft Workshop Manager">
    <meta name="language"        content="English">
    <meta name="revisit-after"   content="7 days">
    <meta name="rating"          content="general">
    <link rel="canonical"        href="<?php echo e($canonicalUrl); ?>">

    
    <meta property="og:type"         content="<?php echo e($seoType); ?>">
    <meta property="og:url"          content="<?php echo e($canonicalUrl); ?>">
    <meta property="og:title"        content="<?php echo e($fullTitle); ?>">
    <meta property="og:description"  content="<?php echo e($seoDesc); ?>">
    <meta property="og:image"        content="<?php echo e($seoImage); ?>">
    <meta property="og:image:alt"    content="<?php echo e($siteName); ?> Logo">
    <meta property="og:site_name"    content="<?php echo e($siteName); ?>">
    <meta property="og:locale"       content="<?php echo e($seoLocale); ?>">

    
    <meta name="twitter:card"        content="summary">
    <meta name="twitter:title"       content="<?php echo e($fullTitle); ?>">
    <meta name="twitter:description" content="<?php echo e($seoDesc); ?>">
    <meta name="twitter:image"       content="<?php echo e($seoImage); ?>">
    <meta name="twitter:image:alt"   content="<?php echo e($siteName); ?>">

    
    <script type="application/ld+json">
    {
        "<?php $__contextArgs = [];
if (context()->has($__contextArgs[0])) :
if (isset($value)) { $__contextPrevious[] = $value; }
$value = context()->get($__contextArgs[0]); ?>": "https://schema.org",
        "@graph": [
            {
                "@type": "Organization",
                "@id": "<?php echo e(url('/')); ?>/#organization",
                "name": "<?php echo e($siteName); ?>",
                "url": "<?php echo e(url('/')); ?>",
                "logo": {
                    "@type": "ImageObject",
                    "url": "<?php echo e(asset('icons/icon.svg')); ?>"
                },
                "description": "Cloud-based workshop management system for auto repair shops and garages.",
                "contactPoint": {
                    "@type": "ContactPoint",
                    "email": "infosuhaimsoft@gmail.com",
                    "contactType": "customer support"
                }
            },
            {
                "@type": "WebSite",
                "@id": "<?php echo e(url('/')); ?>/#website",
                "url": "<?php echo e(url('/')); ?>",
                "name": "<?php echo e($siteName); ?>",
                "publisher": { "@id": "<?php echo e(url('/')); ?>/#organization" }
            },
            {
                "@type": "WebPage",
                "@id": "<?php echo e($canonicalUrl); ?>/#webpage",
                "url": "<?php echo e($canonicalUrl); ?>",
                "name": "<?php echo e($fullTitle); ?>",
                "description": "<?php echo e($seoDesc); ?>",
                "isPartOf": { "@id": "<?php echo e(url('/')); ?>/#website" },
                "inLanguage": "en-US",
                "dateModified": "<?php echo e(now()->toIso8601String()); ?>"
            }
        ]
    }
    </script>

    
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1d4ed8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="/icons/icon.svg">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
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
            body {
                @apply bg-slate-50 text-slate-800 antialiased;
            }
        }

        @layer components {
            .sidebar-link {
                @apply flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-slate-600 
                       transition-all duration-200 hover:bg-primary-50 hover:text-primary-600;
            }
            .sidebar-link.active {
                @apply bg-primary-50/70 text-primary-600 border-l-4 border-primary-600 font-semibold shadow-sm;
            }
            .sidebar-link svg {
                @apply text-slate-400 transition-colors duration-200;
            }
            .sidebar-link:hover svg,
            .sidebar-link.active svg {
                @apply text-primary-600;
            }
            @keyframes spin-slow {
                from { transform: rotate(0deg); }
                to   { transform: rotate(360deg); }
            }
            .animate-spin-slow {
                animation: spin-slow 12s linear infinite;
            }
            .glass-card {
                @apply bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm 
                       transition-all duration-300 hover:shadow-md hover:border-slate-300;
            }
            .stat-card {
                @apply relative overflow-hidden rounded-2xl p-6 border border-slate-200/60 shadow-sm 
                       transition-all duration-300 hover:scale-[1.01] hover:shadow-md;
            }
            .btn-primary {
                @apply inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-primary-600 to-primary-500 
                       text-white text-sm font-semibold rounded-xl shadow-md shadow-primary-500/10 
                       transition-all duration-200 hover:shadow-primary-500/20 hover:scale-[1.01] active:scale-95;
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
                @apply w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 placeholder-slate-400 
                       text-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 
                       focus:border-primary-500 focus:bg-white;
            }
            .form-select {
                @apply w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 text-sm 
                       transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 
                       focus:border-primary-500 appearance-none;
            }
            .form-label {
                @apply block text-sm font-semibold text-slate-700 mb-2;
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
                    color: #64748b;
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
</head>
<body class="min-h-screen <?php echo e(Auth::check() ? 'bg-slate-50' : 'bg-gradient-to-br from-blue-50/50 via-white to-slate-50 relative overflow-x-hidden flex flex-col items-center justify-center py-8 px-4'); ?>">
    <?php if(auth()->guard()->guest()): ?>
    
    <div class="absolute top-[-20%] left-[-10%] w-[50%] h-[50%] rounded-full bg-blue-400/10 blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-[-20%] right-[-10%] w-[50%] h-[50%] rounded-full bg-indigo-400/10 blur-[120px] pointer-events-none"></div>
    <?php endif; ?>

    <?php if(auth()->guard()->check()): ?>
    
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

    
    <aside id="sidebar" class="fixed top-0 left-0 z-50 h-full w-72 bg-white border-r border-slate-200/80 
                               transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-out flex flex-col shadow-sm">
        
        <div class="p-6 border-b border-slate-200/60">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-600 to-indigo-500 flex items-center justify-center shadow-md shadow-primary-500/25">
                    <svg class="w-6 h-6 text-white animate-spin-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="animation-duration: 8s;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-slate-900 leading-tight">
                        <?php echo e(Auth::user()->isSuperAdmin() ? 'System Admin' : (Auth::user()->workshop->name ?? 'Suhaim Soft')); ?>

                    </h1>
                    <?php if(!Auth::user()->isSuperAdmin() && Auth::user()->workshop && Auth::user()->workshop->phone): ?>
                    <p class="text-xs text-slate-500 font-medium">
                        <?php echo e(Auth::user()->workshop->phone); ?>

                    </p>
                    <?php endif; ?>
                    <p class="text-xs text-primary-600 font-bold tracking-wider uppercase mt-0.5">
                        <?php echo e(Auth::user()->isSuperAdmin() ? 'Control Panel' : 'Work Shop'); ?>

                    </p>
                </div>
            </div>
        </div>

        
        <nav class="flex-1 overflow-y-auto p-4 space-y-1">

            <?php if(Auth::user()->isSuperAdmin()): ?>
            <a href="<?php echo e(route('super_admin.dashboard', ['tab' => 'workshops'])); ?>" class="sidebar-link <?php echo e(request()->routeIs('super_admin.dashboard') && request('tab', 'workshops') === 'workshops' ? 'active' : ''); ?>" id="nav-workshops">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                Garages / Workshops
            </a>
            <a href="<?php echo e(route('super_admin.dashboard', ['tab' => 'keys'])); ?>" class="sidebar-link <?php echo e(request()->routeIs('super_admin.dashboard') && request('tab') === 'keys' ? 'active' : ''); ?>" id="nav-keys">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m-9 8a2 2 0 012-2m7-3a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
                Product Keys
            </a>
            <?php endif; ?>

            <?php if(!Auth::user()->isSuperAdmin()): ?>
            <a href="<?php echo e(route('dashboard')); ?>" class="sidebar-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>" id="nav-dashboard">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
            <a href="<?php echo e(route('bills.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('bills.*') && !request()->routeIs('bills.create') ? 'active' : ''); ?>" id="nav-bills">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                Invoices
            </a>

            <a href="<?php echo e(route('bill-templates.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('bill-templates.*') ? 'active' : ''); ?>" id="nav-bill-templates">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                Bill Templates
            </a>

            <a href="<?php echo e(route('customers.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('customers.*') ? 'active' : ''); ?>" id="nav-customers">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Customers
            </a>

            <a href="<?php echo e(route('vehicles.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('vehicles.*') ? 'active' : ''); ?>" id="nav-vehicles">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 17h8M8 17v4H6v-4m2 0H4.5A1.5 1.5 0 013 15.5v-3.194a1.5 1.5 0 01.138-.632L5 8h14l1.862 3.674c.09.2.138.416.138.632V15.5a1.5 1.5 0 01-1.5 1.5H16m0 0v4h-2v-4m0 0H8M7 11h.01M17 11h.01"/></svg>
                Vehicles
            </a>

            <a href="<?php echo e(route('services.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('services.*') ? 'active' : ''); ?>" id="nav-services">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Services
            </a>

            <a href="<?php echo e(route('products.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('products.*') ? 'active' : ''); ?>" id="nav-products">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Products / Stock
            </a>

            <a href="<?php echo e(route('expenses.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('expenses.*') ? 'active' : ''); ?>" id="nav-expenses">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Expenses
            </a>

            <a href="<?php echo e(route('salaries.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('salaries.*') ? 'active' : ''); ?>" id="nav-salaries">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Salaries
            </a>

            <a href="<?php echo e(route('employees.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('employees.*') ? 'active' : ''); ?>" id="nav-employees">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Employees
            </a>

            <a href="<?php echo e(route('work-orders.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('work-orders.*') ? 'active' : ''); ?>" id="nav-work-orders">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                Work Orders
            </a>

            <a href="<?php echo e(route('warranties.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('warranties.*') ? 'active' : ''); ?>" id="nav-warranties">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                Warranty
            </a>
            <?php endif; ?>
        </nav>

        
        <div class="p-4 border-t border-slate-200/60 no-print">
            <div class="glass-card !p-3 !rounded-xl !bg-slate-50 !border-slate-100 shadow-sm">
                <p class="text-xs text-slate-800 font-semibold">
                    <?php echo e(Auth::user()->isSuperAdmin() ? 'System Super Admin' : (Auth::user()->workshop->name ?? 'Suhaim Soft')); ?>

                </p>
                <p class="text-[10px] text-slate-500 mt-0.5">© <?php echo e(date('Y')); ?> All Rights Reserved</p>
            </div>
        </div>
    </aside>
    <?php endif; ?>

    
    <div class="<?php echo e(Auth::check() ? 'lg:ml-72 justify-start' : 'w-full max-w-md mx-auto'); ?> <?php echo e(Auth::check() ? 'min-h-screen' : ''); ?> flex flex-col">
        <?php if(auth()->guard()->check()): ?>
        
        <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-slate-200/60 no-print">
            <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 h-16">
                
                <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-xl hover:bg-slate-50 text-slate-500 hover:text-slate-950 transition-colors" id="menu-toggle">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>

                
                <div class="flex flex-col justify-center">
                    <h2 class="text-sm sm:text-lg font-bold text-slate-900 leading-tight"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h2>
                    <p class="hidden sm:block text-[10px] sm:text-xs text-slate-500 mt-0.5">
                        Welcome to <?php echo e(Auth::user()->isSuperAdmin() ? 'Suhaim Soft Control Panel' : (Auth::user()->workshop->name ?? 'Suhaim Soft Work Shop')); ?>

                    </p>
                </div>

                
                <div class="flex items-center gap-3">
                    <div class="hidden sm:flex items-center gap-2 px-4 py-2 bg-slate-50 rounded-xl border border-slate-200">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span class="text-xs text-slate-600 font-semibold"><?php echo e(now()->format('d M Y')); ?></span>
                    </div>
                    <form action="<?php echo e(route('logout')); ?>" method="POST" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-rose-600 bg-rose-50 border border-rose-100 hover:bg-rose-100/60 rounded-xl transition-all active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </header>
        <?php endif; ?>

        
        <div class="fixed bottom-6 right-6 z-[9999] flex flex-col gap-3 max-w-sm w-full p-4 pointer-events-none">
            
            <?php if(session('success')): ?>
            <div x-data="{ show: true }" 
                 x-show="show" 
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="translate-y-4 opacity-0 translate-x-4"
                 x-transition:enter-end="translate-y-0 opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="translate-y-0 opacity-100 translate-x-0"
                 x-transition:leave-end="translate-y-2 opacity-0 translate-x-4"
                 x-init="setTimeout(() => show = false, 4000)"
                 class="pointer-events-auto w-full bg-white/90 backdrop-blur-md border border-slate-200 rounded-2xl p-4 shadow-[0_10px_30px_rgba(0,0,0,0.08)] flex items-start gap-3 animate-pulse-glow">
                <div class="w-8 h-8 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-600 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-slate-800 uppercase tracking-wider">Success</p>
                    <p class="text-xs text-slate-600 font-semibold mt-0.5 leading-relaxed"><?php echo e(session('success')); ?></p>
                </div>
                <button @click="show = false" class="text-slate-400 hover:text-slate-600 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
            <div x-data="{ show: true }" 
                 x-show="show" 
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="translate-y-4 opacity-0 translate-x-4"
                 x-transition:enter-end="translate-y-0 opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="translate-y-0 opacity-100 translate-x-0"
                 x-transition:leave-end="translate-y-2 opacity-0 translate-x-4"
                 x-init="setTimeout(() => show = false, 5000)"
                 class="pointer-events-auto w-full bg-white/90 backdrop-blur-md border border-slate-200 rounded-2xl p-4 shadow-[0_10px_30px_rgba(239,68,68,0.08)] flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-rose-500/10 flex items-center justify-center text-rose-600 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-slate-800 uppercase tracking-wider">Error</p>
                    <p class="text-xs text-slate-600 font-semibold mt-0.5 leading-relaxed"><?php echo e(session('error')); ?></p>
                </div>
                <button @click="show = false" class="text-slate-400 hover:text-slate-600 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
            <div x-data="{ show: true }" 
                 x-show="show" 
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="translate-y-4 opacity-0 translate-x-4"
                 x-transition:enter-end="translate-y-0 opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="translate-y-0 opacity-100 translate-x-0"
                 x-transition:leave-end="translate-y-2 opacity-0 translate-x-4"
                 x-init="setTimeout(() => show = false, 6000)"
                 class="pointer-events-auto w-full bg-white/90 backdrop-blur-md border border-slate-200 rounded-2xl p-4 shadow-[0_10px_30px_rgba(239,68,68,0.08)] flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-red-500/10 flex items-center justify-center text-red-600 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="flex-1 min-w-0 text-left">
                    <p class="text-xs font-bold text-slate-800 uppercase tracking-wider">Alert</p>
                    <ul class="list-disc list-inside text-[11px] text-slate-600 font-semibold mt-1 space-y-0.5 leading-relaxed">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
                <button @click="show = false" class="text-slate-400 hover:text-slate-600 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <?php endif; ?>
        </div>

        
        <main class="<?php echo e(Auth::check() ? 'p-4 sm:p-6 lg:p-8' : ''); ?>">
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>

    
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>
    
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('Service Worker registered with scope:', reg.scope))
                    .catch(err => console.error('Service Worker registration failed:', err));
            });
        }
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH E:\Suhaim Soft Work Shop\suhaimsoftworkshop\resources\views\layouts\app.blade.php ENDPATH**/ ?>