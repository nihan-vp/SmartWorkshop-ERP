<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Suhaim Soft Work Shop — Workshop Management System</title>
    <meta name="description" content="Suhaim Soft Work Shop is a smart workshop management platform. Manage work orders, billing, inventory, and staff with ease.">
    <meta name="keywords" content="workshop management, garage software, auto repair, invoicing, inventory, Suhaim Soft">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url('/') }}">

    <!-- Open Graph -->
    <meta property="og:title" content="Suhaim Soft Work Shop">
    <meta property="og:description" content="Smart workshop management system for modern garages.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">

    <!-- PWA -->
    <link rel="manifest" href="/manifest.json?v=3">
    <meta name="theme-color" content="#1d4ed8">
    <link rel="shortcut icon" href="/images/logo.png" type="image/png">
    <link rel="icon" href="/images/logo.png" type="image/png">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        /* ── Reset & Base ── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f0f7ff;
            color: #1e293b;
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* ── Variables ── */
        :root {
            --blue-50:  #eff6ff;
            --blue-100: #dbeafe;
            --blue-200: #bfdbfe;
            --blue-300: #93c5fd;
            --blue-400: #60a5fa;
            --blue-500: #3b82f6;
            --blue-600: #2563eb;
            --blue-700: #1d4ed8;
            --blue-800: #1e40af;
            --blue-900: #1e3a8a;
            --slate-50:  #f8fafc;
            --slate-100: #f1f5f9;
            --slate-200: #e2e8f0;
            --slate-400: #94a3b8;
            --slate-500: #64748b;
            --slate-600: #475569;
            --slate-700: #334155;
            --slate-800: #1e293b;
            --slate-900: #0f172a;
        }

        /* ── Utility ── */
        .container {
            max-width: 1140px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--blue-600);
            color: #fff;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 0.75rem 1.75rem;
            border-radius: 0.625rem;
            text-decoration: none;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 4px 14px rgba(37,99,235,0.25);
        }
        .btn-primary:hover {
            background: var(--blue-700);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37,99,235,0.35);
        }
        .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #fff;
            color: var(--blue-700);
            font-weight: 700;
            font-size: 0.95rem;
            padding: 0.75rem 1.75rem;
            border-radius: 0.625rem;
            text-decoration: none;
            border: 2px solid var(--blue-200);
            transition: border-color 0.2s, background 0.2s, transform 0.15s;
        }
        .btn-outline:hover {
            border-color: var(--blue-500);
            background: var(--blue-50);
            transform: translateY(-2px);
        }
        .section-label {
            display: inline-block;
            background: var(--blue-100);
            color: var(--blue-700);
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 0.35rem 1rem;
            border-radius: 999px;
            margin-bottom: 1rem;
        }

        /* ── Header ── */
        header {
            background: #fff;
            border-bottom: 1px solid var(--slate-200);
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 6px rgba(0,0,0,0.06);
        }
        .header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 68px;
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            text-decoration: none;
        }
        .logo-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--blue-600), var(--blue-400));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .logo-icon svg { color: #fff; }
        .logo-text {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--slate-900);
            letter-spacing: -0.03em;
        }
        .logo-text span { color: var(--blue-600); }
        nav { display: flex; align-items: center; gap: 2rem; }
        nav a {
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--slate-600);
            transition: color 0.2s;
        }
        nav a:hover { color: var(--blue-600); }
        .nav-desktop { display: flex; align-items: center; gap: 1.5rem; }
        .header-actions { display: flex; align-items: center; gap: 0.75rem; }
        .login-link {
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--slate-600);
            transition: color 0.2s;
        }
        .login-link:hover { color: var(--blue-600); }

        /* Mobile nav toggle */
        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px;
        }
        .hamburger span {
            display: block;
            width: 24px;
            height: 2px;
            background: var(--slate-700);
            border-radius: 2px;
            transition: all 0.3s;
        }
        .mobile-nav {
            display: none;
            background: #fff;
            border-top: 1px solid var(--slate-200);
            padding: 1rem 1.5rem 1.25rem;
            flex-direction: column;
            gap: 0.75rem;
        }
        .mobile-nav.open { display: flex; }
        .mobile-nav a {
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--slate-700);
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--slate-100);
        }
        .mobile-nav a:last-child { border-bottom: none; }

        /* ── Hero ── */
        .hero {
            background: linear-gradient(160deg, #eff6ff 0%, #dbeafe 35%, #eff6ff 70%, #f8fafc 100%);
            padding: 5rem 0 4.5rem;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: -120px;
            right: -120px;
            width: 520px;
            height: 520px;
            background: radial-gradient(circle, rgba(59,130,246,0.12) 0%, transparent 70%);
            border-radius: 50%;
        }
        .hero::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -80px;
            width: 380px;
            height: 380px;
            background: radial-gradient(circle, rgba(96,165,250,0.10) 0%, transparent 70%);
            border-radius: 50%;
        }
        .hero-inner {
            position: relative;
            z-index: 1;
            text-align: center;
            max-width: 780px;
            margin: 0 auto;
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #fff;
            border: 1px solid var(--blue-200);
            color: var(--blue-700);
            font-size: 0.8rem;
            font-weight: 700;
            padding: 0.4rem 1rem;
            border-radius: 999px;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(37,99,235,0.1);
        }
        .hero-badge .dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--blue-500);
            animation: pulse-dot 2s ease-in-out infinite;
        }
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(1.3); }
        }
        h1 {
            font-size: clamp(2.2rem, 5.5vw, 3.6rem);
            font-weight: 900;
            line-height: 1.12;
            color: var(--slate-900);
            letter-spacing: -0.03em;
            margin-bottom: 1.25rem;
        }
        h1 .highlight {
            color: var(--blue-600);
        }
        .hero-desc {
            font-size: clamp(1rem, 2vw, 1.15rem);
            color: var(--slate-500);
            max-width: 580px;
            margin: 0 auto 2.25rem;
            font-weight: 500;
        }
        .hero-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .hero-stats {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 2.5rem;
            margin-top: 3.5rem;
            padding-top: 2.5rem;
            border-top: 1px solid var(--blue-200);
            flex-wrap: wrap;
        }
        .hero-stat { text-align: center; }
        .hero-stat strong {
            display: block;
            font-size: 1.75rem;
            font-weight: 900;
            color: var(--blue-700);
            letter-spacing: -0.03em;
        }
        .hero-stat span {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--slate-500);
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        /* ── Section Common ── */
        section { padding: 5rem 0; }
        .section-header { text-align: center; margin-bottom: 3rem; }
        h2 {
            font-size: clamp(1.6rem, 3.5vw, 2.5rem);
            font-weight: 800;
            color: var(--slate-900);
            letter-spacing: -0.025em;
            line-height: 1.2;
            margin-bottom: 0.75rem;
        }
        .section-sub {
            font-size: 1rem;
            color: var(--slate-500);
            font-weight: 500;
            max-width: 560px;
            margin: 0 auto;
        }

        /* ── About ── */
        .about { background: #fff; }
        .about-inner {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3.5rem;
            align-items: center;
        }
        .about-content h2 { text-align: left; }
        .about-content p {
            color: var(--slate-500);
            font-size: 0.975rem;
            font-weight: 500;
            line-height: 1.8;
            margin-bottom: 1rem;
        }
        .about-highlights {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }
        .about-highlights li {
            list-style: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--slate-700);
        }
        .about-highlights li::before {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--blue-500);
            flex-shrink: 0;
        }
        .about-visual {
            background: linear-gradient(135deg, var(--blue-600), var(--blue-400));
            border-radius: 1.25rem;
            padding: 3rem 2.5rem;
            color: #fff;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }
        .about-visual h3 {
            font-size: 1.4rem;
            font-weight: 800;
            margin-bottom: 0.25rem;
        }
        .about-visual p { font-size: 0.9rem; opacity: 0.85; font-weight: 500; }
        .visual-stat {
            background: rgba(255,255,255,0.15);
            border-radius: 0.75rem;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .visual-stat .val {
            font-size: 1.6rem;
            font-weight: 900;
        }
        .visual-stat .lbl {
            font-size: 0.8rem;
            opacity: 0.8;
            font-weight: 600;
        }

        /* ── How it Works ── */
        .how { background: var(--blue-50); }
        .steps-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.75rem;
        }
        .step-card {
            background: #fff;
            border: 1px solid var(--blue-100);
            border-radius: 1rem;
            padding: 2rem 1.75rem;
            transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
            position: relative;
        }
        .step-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(37,99,235,0.1);
            border-color: var(--blue-300);
        }
        .step-num {
            width: 42px;
            height: 42px;
            border-radius: 0.625rem;
            background: var(--blue-100);
            color: var(--blue-700);
            font-weight: 900;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
        }
        .step-card h3 {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--slate-800);
            margin-bottom: 0.5rem;
        }
        .step-card p {
            font-size: 0.875rem;
            color: var(--slate-500);
            line-height: 1.7;
            font-weight: 500;
        }

        /* ── Features ── */
        .features { background: #fff; }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }
        @media (min-width: 1200px) {
            .features-grid { grid-template-columns: repeat(4, 1fr); }
        }
        .feature-card {
            background: var(--blue-50);
            border: 1px solid var(--blue-100);
            border-radius: 1rem;
            padding: 1.75rem;
            transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s, background 0.2s;
        }
        .feature-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(37,99,235,0.1);
            border-color: var(--blue-300);
            background: #fff;
        }
        .feature-icon {
            width: 44px;
            height: 44px;
            border-radius: 0.75rem;
            background: var(--blue-600);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }
        .feature-icon svg { color: #fff; width: 22px; height: 22px; }
        .feature-card h3 {
            font-size: 0.975rem;
            font-weight: 700;
            color: var(--slate-800);
            margin-bottom: 0.4rem;
        }
        .feature-card p {
            font-size: 0.85rem;
            color: var(--slate-500);
            line-height: 1.65;
            font-weight: 500;
        }

        /* ── Results ── */
        .results { background: var(--blue-700); }
        .results .section-label { background: rgba(255,255,255,0.15); color: #fff; }
        .results h2 { color: #fff; }
        .results .section-sub { color: rgba(255,255,255,0.75); }
        .results-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
        }
        .result-card {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 1rem;
            padding: 1.75rem;
            text-align: center;
            transition: background 0.2s, transform 0.2s;
        }
        .result-card:hover {
            background: rgba(255,255,255,0.18);
            transform: translateY(-3px);
        }
        .result-card .num {
            font-size: 2.5rem;
            font-weight: 900;
            color: #fff;
            letter-spacing: -0.03em;
            line-height: 1;
            margin-bottom: 0.5rem;
        }
        .result-card h3 {
            font-size: 0.95rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 0.4rem;
        }
        .result-card p {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.7);
            font-weight: 500;
            line-height: 1.6;
        }

        /* ── Contact ── */
        .contact { background: #fff; }
        .contact-inner {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3.5rem;
            align-items: start;
        }
        .contact-info h2 { text-align: left; }
        .contact-info p {
            color: var(--slate-500);
            font-size: 0.975rem;
            font-weight: 500;
            margin-bottom: 2rem;
        }
        .contact-items { display: flex; flex-direction: column; gap: 1.25rem; }
        .contact-item {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .contact-item-icon {
            width: 42px;
            height: 42px;
            border-radius: 0.625rem;
            background: var(--blue-100);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .contact-item-icon svg { color: var(--blue-600); width: 20px; height: 20px; }
        .contact-item-text span {
            display: block;
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--slate-400);
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }
        .contact-item-text a, .contact-item-text p {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--slate-800);
            text-decoration: none;
            margin: 0;
        }
        .contact-item-text a:hover { color: var(--blue-600); }
        .contact-form {
            background: var(--blue-50);
            border: 1px solid var(--blue-100);
            border-radius: 1.25rem;
            padding: 2rem;
        }
        .form-group { margin-bottom: 1.1rem; }
        .form-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--slate-600);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 0.4rem;
        }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            background: #fff;
            border: 1.5px solid var(--blue-200);
            border-radius: 0.625rem;
            padding: 0.7rem 0.9rem;
            font-size: 0.9rem;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            color: var(--slate-800);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus {
            border-color: var(--blue-500);
            box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
        }
        .form-group textarea { resize: vertical; min-height: 100px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .form-submit {
            width: 100%;
            padding: 0.85rem;
            background: var(--blue-600);
            color: #fff;
            font-weight: 700;
            font-size: 0.95rem;
            border: none;
            border-radius: 0.625rem;
            cursor: pointer;
            transition: background 0.2s, transform 0.15s;
            font-family: 'Inter', sans-serif;
        }
        .form-submit:hover { background: var(--blue-700); transform: translateY(-1px); }
        .form-success {
            display: none;
            background: #dcfce7;
            border: 1px solid #86efac;
            color: #166534;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            margin-top: 1rem;
        }

        /* ── Footer ── */
        footer {
            background: var(--slate-900);
            color: rgba(255,255,255,0.7);
            padding: 4rem 0 2rem;
            position: relative;
        }
        footer .footer-top {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr 1fr;
            gap: 3rem;
            margin-bottom: 3rem;
        }
        footer .brand {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            text-decoration: none;
            margin-bottom: 1rem;
        }
        footer .brand svg { color: var(--blue-500); }
        footer .brand-text {
            font-size: 1.4rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.03em;
        }
        footer .brand-text span { color: var(--blue-500); }
        footer .brand-desc {
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            max-width: 300px;
        }
        footer .socials {
            display: flex;
            gap: 0.75rem;
        }
        footer .socials a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 0.5rem;
            background: rgba(255,255,255,0.05);
            color: #fff;
            transition: background 0.2s;
        }
        footer .socials a:hover { background: var(--blue-600); }
        footer h4 {
            color: #fff;
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: 1.25rem;
        }
        footer ul { list-style: none; display: flex; flex-direction: column; gap: 0.75rem; }
        footer ul a {
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.2s;
        }
        footer ul a:hover { color: #fff; }
        footer .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.85rem;
        }
        footer .footer-bottom p { font-weight: 500; }
        footer .footer-bottom-links { display: flex; gap: 1.5rem; }
        footer .footer-bottom-links a {
            color: rgba(255,255,255,0.6);
            text-decoration: none;
        }
        footer .footer-bottom-links a:hover { color: #fff; }

        /* ── Responsive ── */
        @media (max-width: 1024px) {
            .results-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .nav-desktop { display: none; }
            .header-actions .login-link, .header-actions .btn-primary { display: none; }
            .hamburger { display: flex; }
            .about-inner,
            .contact-inner { grid-template-columns: 1fr; gap: 2rem; }
            .steps-grid,
            .features-grid { grid-template-columns: 1fr 1fr; }
            .results-grid { grid-template-columns: repeat(2, 1fr); }
            .hero { padding: 3.5rem 0 3rem; }
            section { padding: 3.5rem 0; }
            .form-row { grid-template-columns: 1fr; }
            footer .footer-top { grid-template-columns: 1fr 1fr; gap: 2.5rem; }
            footer .brand-desc { max-width: 100%; }
            footer .footer-bottom { flex-direction: column; gap: 1rem; text-align: center; }
        }
        @media (max-width: 540px) {
            .steps-grid,
            .features-grid,
            .results-grid { grid-template-columns: 1fr; }
            footer .footer-top { grid-template-columns: 1fr; gap: 2rem; }
            .hero-stats { gap: 1.5rem; }
            .hero-actions { flex-direction: column; width: 100%; }
            .btn-primary, .btn-outline { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>

<!-- ══════════════════════════════ HEADER ══════════════════════════════ -->
<header>
    <div class="container header-inner">
        <!-- Logo -->
        <a href="#" class="logo">
            <div class="logo-icon">
                <svg width="20" height="20" viewBox="0 0 512 512" fill="currentColor">
                    <path d="M 334 165 C 334 165 298 140 256 140 C 214 140 178 165 178 210 C 178 260 230 275 270 285 C 300 292 342 308 342 355 C 342 410 290 432 256 432 C 210 432 170 410 170 410 L 182 355 C 182 355 220 380 256 380 C 300 380 342 360 342 315 C 342 265 285 245 242 235 C 208 227 170 205 170 160 C 170 100 226 80 256 80 C 306 80 342 105 342 105 Z" />
                </svg>
            </div>
            <span class="logo-text">SUHAIM<span>SOFT</span></span>
        </a>

        <!-- Desktop Nav -->
        <div class="nav-desktop">
            <nav>
                <a href="#">Home</a>
                <a href="#about">About</a>
                <a href="#how">How It Works</a>
                <a href="#features">Features</a>
                <a href="#contact">Contact</a>
            </nav>
        </div>

        <!-- Actions -->
        <div class="header-actions">
            <a href="{{ route('login') }}" class="login-link">Login</a>
            <a href="{{ route('register') }}" class="btn-primary" style="padding: 0.55rem 1.25rem; font-size: 0.85rem;">
                Get Started
            </a>
            <button class="hamburger" id="hamburger" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>

    <!-- Mobile Nav -->
    <div class="mobile-nav" id="mobile-nav">
        <a href="#">Home</a>
        <a href="#about">About</a>
        <a href="#how">How It Works</a>
        <a href="#features">Features</a>
        <a href="#contact">Contact</a>
        <a href="{{ route('login') }}">Login</a>
        <a href="{{ route('register') }}" style="color: #2563eb;">Get Started →</a>
    </div>
</header>

<!-- ══════════════════════════════ HERO ══════════════════════════════ -->
<section class="hero">
    <div class="container">
        <div class="hero-inner">
            <div class="hero-badge">
                <span class="dot"></span>
                Smart Workshop Management Platform
            </div>

            <h1>
                The Modern Way to<br>
                Run Your <span class="highlight">Workshop</span>
            </h1>

            <p class="hero-desc">
                Suhaim Soft simplifies billing, inventory, work orders, and team management — so you can focus on what matters: quality service.
            </p>

            <div class="hero-actions">
                <a href="{{ route('register') }}" class="btn-primary">
                    Start Free Trial
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
                <a href="#features" class="btn-outline">Explore Features</a>
            </div>

            <div class="hero-stats">
                <div class="hero-stat">
                    <strong>100+</strong>
                    <span>Workshops</span>
                </div>
                <div class="hero-stat">
                    <strong>1-Click</strong>
                    <span>Invoicing</span>
                </div>
                <div class="hero-stat">
                    <strong>Zero</strong>
                    <span>Downtime</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════ ABOUT ══════════════════════════════ -->
<section class="about" id="about" style="padding: 6rem 0; background: #f8fafc;">
    <div class="container">
        <div class="section-header text-center" style="text-align: center;">
            <span class="section-label">Our Vision</span>
            <h2>Built for Workshop Professionals</h2>
            <p class="section-sub" style="max-width: 700px; margin: 0 auto;">In today's fast-paced automotive environment, your most valuable resource is time. Suhaim Soft was built on a simple principle — give that time back through intelligent automation and a seamless digital workflow.</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-top: 3rem;">
            <div style="background: #fff; padding: 2.5rem 2rem; border-radius: 1rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); text-align: center; border: 1px solid #e2e8f0; transition: transform 0.2s;">
                <div style="width: 56px; height: 56px; border-radius: 1rem; background: #eff6ff; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: #3b82f6;">
                    <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <h3 style="font-size: 1.25rem; font-weight: 700; color: #0f172a; margin-bottom: 0.75rem;">Smart Inventory</h3>
                <p style="color: #64748b; line-height: 1.6; font-size: 0.95rem;">Auto-deduct parts on billing. Get low-stock alerts before you run out.</p>
            </div>

            <div style="background: #fff; padding: 2.5rem 2rem; border-radius: 1rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); text-align: center; border: 1px solid #e2e8f0; transition: transform 0.2s;">
                <div style="width: 56px; height: 56px; border-radius: 1rem; background: #eff6ff; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: #3b82f6;">
                    <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 style="font-size: 1.25rem; font-weight: 700; color: #0f172a; margin-bottom: 0.75rem;">Instant Invoicing</h3>
                <p style="color: #64748b; line-height: 1.6; font-size: 0.95rem;">Generate PDF bills in one click and share via WhatsApp instantly.</p>
            </div>

            <div style="background: #fff; padding: 2.5rem 2rem; border-radius: 1rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); text-align: center; border: 1px solid #e2e8f0; transition: transform 0.2s;">
                <div style="width: 56px; height: 56px; border-radius: 1rem; background: #eff6ff; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: #3b82f6;">
                    <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <h3 style="font-size: 1.25rem; font-weight: 700; color: #0f172a; margin-bottom: 0.75rem;">Live Analytics</h3>
                <p style="color: #64748b; line-height: 1.6; font-size: 0.95rem;">Real-time dashboard with revenue metrics and performance tracking.</p>
            </div>

            <div style="background: #fff; padding: 2.5rem 2rem; border-radius: 1rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); text-align: center; border: 1px solid #e2e8f0; transition: transform 0.2s;">
                <div style="width: 56px; height: 56px; border-radius: 1rem; background: #eff6ff; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: #3b82f6;">
                    <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 style="font-size: 1.25rem; font-weight: 700; color: #0f172a; margin-bottom: 0.75rem;">Team Access</h3>
                <p style="color: #64748b; line-height: 1.6; font-size: 0.95rem;">Multi-user roles for mechanics, managers, and administrators.</p>
            </div>

            <div style="background: #fff; padding: 2.5rem 2rem; border-radius: 1rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); text-align: center; border: 1px solid #e2e8f0; transition: transform 0.2s;">
                <div style="width: 56px; height: 56px; border-radius: 1rem; background: #eff6ff; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: #3b82f6;">
                    <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 style="font-size: 1.25rem; font-weight: 700; color: #0f172a; margin-bottom: 0.75rem;">Warranty Tracking</h3>
                <p style="color: #64748b; line-height: 1.6; font-size: 0.95rem;">Auto-expiry alerts and full claim history for parts and services.</p>
            </div>

            <div style="background: #fff; padding: 2.5rem 2rem; border-radius: 1rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); text-align: center; border: 1px solid #e2e8f0; transition: transform 0.2s;">
                <div style="width: 56px; height: 56px; border-radius: 1rem; background: #eff6ff; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: #3b82f6;">
                    <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/></svg>
                </div>
                <h3 style="font-size: 1.25rem; font-weight: 700; color: #0f172a; margin-bottom: 0.75rem;">Cloud Secure</h3>
                <p style="color: #64748b; line-height: 1.6; font-size: 0.95rem;">All your data encrypted and backed up securely in the cloud.</p>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════ HOW IT WORKS ══════════════════════════════ -->
<section class="how" id="how">
    <div class="container">
        <div class="section-header">
            <span class="section-label">How It Works</span>
            <h2>Simple Onboarding, Instant Results</h2>
            <p class="section-sub">Get started with Suhaim Soft in three easy steps and transform your workshop operations.</p>
        </div>
        <div class="steps-grid">
            <div class="step-card">
                <div class="step-num">01</div>
                <h3>Consult & Demo</h3>
                <p>Request a demo and our specialists will showcase the platform's full power, tailored specifically to your workshop's unique needs and workflow.</p>
            </div>
            <div class="step-card">
                <div class="step-num">02</div>
                <h3>Seamless Setup</h3>
                <p>Our team handles the complete setup — migrating your customer and inventory records and integrating the system seamlessly into your daily operations.</p>
            </div>
            <div class="step-card">
                <div class="step-num">03</div>
                <h3>Training & Go Live</h3>
                <p>We provide comprehensive staff training and dedicated helpline support to ensure your team is fully confident and your workshop runs at full efficiency.</p>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════ FEATURES ══════════════════════════════ -->
<section class="features" id="features">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Features</span>
            <h2>Everything You Need to Scale</h2>
            <p class="section-sub">A comprehensive set of tools to manage every aspect of your workshop — from billing to team payroll.</p>
        </div>
        <div class="features-grid">

            <!-- Dashboard -->
            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </div>
                <h3>Dashboard</h3>
                <p>A central hub providing real-time insights, quick actions, revenue metrics, and at-a-glance performance tracking.</p>
            </div>

            <!-- Invoices -->
            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3>Invoices</h3>
                <p>Generate beautiful PDF bills instantly. Share invoices via WhatsApp with auto-calculated tax, discounts, and multiple payment methods.</p>
            </div>

            <!-- Bill Templates -->
            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <h3>Bill Templates</h3>
                <p>Bundle common services and parts into reusable templates. Generate a complete bill in a single click for repeat jobs.</p>
            </div>

            <!-- Customers -->
            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3>Customers</h3>
                <p>Store full customer profiles with contact history, vehicle logs, and total billing statistics for personalized service.</p>
            </div>

            <!-- Vehicles -->
            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17h8M8 17v4H6v-4m2 0H4.5A1.5 1.5 0 013 15.5v-3.194a1.5 1.5 0 01.138-.632L5 8h14l1.862 3.674c.09.2.138.416.138.632V15.5a1.5 1.5 0 01-1.5 1.5H16m0 0v4h-2v-4m0 0H8M7 11h.01M17 11h.01"/></svg>
                </div>
                <h3>Vehicles</h3>
                <p>Register vehicles with brand, model, year, color, and plate number — mapped directly to owners for instant bill association.</p>
            </div>

            <!-- Services -->
            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3>Services</h3>
                <p>Catalog all repair, diagnostic, and maintenance services. Standardize pricing and labor costs across all your operations.</p>
            </div>

            <!-- Products / Stock -->
            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <h3>Products / Stock</h3>
                <p>Auto-deduct parts on bill creation, track fluids and spares, and receive low-stock alerts to protect your margins.</p>
            </div>

            <!-- Expenses -->
            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3>Expenses</h3>
                <p>Log overheads, utilities, and tool purchases to maintain an accurate, real-time cash flow ledger for your workshop.</p>
            </div>

            <!-- Purchases -->
            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <h3>Purchases</h3>
                <p>Record inbound part purchases from suppliers. Keep detailed track of wholesale costs versus retail pricing for accurate P&L.</p>
            </div>

            <!-- Salaries -->
            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <h3>Salaries</h3>
                <p>Manage employee payroll, track compensation disbursements by period, and log payment method and status effortlessly.</p>
            </div>

            <!-- Employees -->
            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <h3>Employees</h3>
                <p>Maintain your mechanics and staff database, mapping roles and permissions to streamline assignments and accountability.</p>
            </div>

            <!-- Work Orders -->
            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <h3>Work Orders</h3>
                <p>Assign repair jobs with priority levels (Urgent/High/Normal), track mechanic progress, and monitor estimated vs. actual times.</p>
            </div>

            <!-- Warranty -->
            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3>Warranty</h3>
                <p>Issue warranties on parts and services with automatic expiration tracking, status indicators, and full claim history.</p>
            </div>

        </div>
    </div>
</section>

<!-- ══════════════════════════════ RESULTS ══════════════════════════════ -->
<section class="results" id="results">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Tangible Results</span>
            <h2>Real-World Impact</h2>
            <p class="section-sub">Our platform delivers measurable results that impact your bottom line and customer satisfaction.</p>
        </div>
        <div class="results-grid">
            <div class="result-card">
                <div class="num">100%</div>
                <h3>Time Savings</h3>
                <p>Eliminate administrative overhead and redirect time to vehicle repairs and customer care.</p>
            </div>
            <div class="result-card">
                <div class="num">100%</div>
                <h3>Data Accuracy</h3>
                <p>Minimize manual entry errors with highly accurate customer, vehicle, and inventory records.</p>
            </div>
            <div class="result-card">
                <div class="num">2×</div>
                <h3>Revenue Boost</h3>
                <p>Streamlined invoicing and preset packages significantly increase revenue collection speed.</p>
            </div>
            <div class="result-card">
                <div class="num">↑</div>
                <h3>Customer Satisfaction</h3>
                <p>Faster check-ins and better record access lead to a significantly improved customer experience.</p>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════ CONTACT ══════════════════════════════ -->
<section class="contact" id="contact">
    <div class="container">
        <div class="contact-inner">
            <!-- Info -->
            <div class="contact-info">
                <span class="section-label">Get in Touch</span>
                <h2>Contact & Enquiries</h2>
                <p>Have questions, need a custom demo, or want to discuss pricing? Our team is ready to help you get started.</p>
                <div class="contact-items">
                    <div class="contact-item">
                        <div class="contact-item-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div class="contact-item-text">
                            <span>Support Helpline</span>
                            <a href="tel:+918891479505">+91 88914 79505</a>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-item-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="contact-item-text">
                            <span>Email</span>
                            <a href="mailto:info@suhaimsoft.com">info@suhaimsoft.com</a>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-item-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="contact-item-text">
                            <span>Location</span>
                            <p>Kerala, India</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="contact-form">
                <form id="contact-form" onsubmit="handleContactSubmit(event)">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Your Name</label>
                            <input type="text" id="contact-name" placeholder="John Doe" required>
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="tel" id="contact-phone" placeholder="+91 00000 00000">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="contact-email" placeholder="you@example.com" required>
                    </div>
                    <div class="form-group">
                        <label>Workshop Name</label>
                        <input type="text" id="contact-workshop" placeholder="Your workshop name">
                    </div>
                    <div class="form-group">
                        <label>Message</label>
                        <textarea id="contact-message" placeholder="Tell us about your workshop and what you need…" required></textarea>
                    </div>
                    <button type="submit" class="form-submit" id="contact-submit">
                        Send Message →
                    </button>
                    <div class="form-success" id="contact-success">
                        ✓ Message sent! We'll get back to you within 24 hours.
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════ FOOTER ══════════════════════════════ -->
<footer>
    <div class="container">
        <div class="footer-top">
            <div>
                <a href="#" class="brand">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                              d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="brand-text">SUHAIM<span>SOFT</span></span>
                </a>
                <p class="brand-desc">The smart, modern workshop management platform designed to save you time and maximize your revenue.</p>
                <div class="socials">
                    <a href="#" aria-label="Facebook"><svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg></a>
                    <a href="#" aria-label="Twitter"><svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg></a>
                    <a href="#" aria-label="Instagram"><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg></a>
                </div>
            </div>
            
            <div>
                <h4>Features</h4>
                <ul>
                    <li><a href="#features">Smart Invoicing</a></li>
                    <li><a href="#features">Inventory Control</a></li>
                    <li><a href="#features">Customer Management</a></li>
                    <li><a href="#features">Work Orders</a></li>
                    <li><a href="#features">Expense Tracking</a></li>
                </ul>
            </div>
            
            <div>
                <h4>Company</h4>
                <ul>
                    <li><a href="#about">About Us</a></li>
                    <li><a href="#how">How It Works</a></li>
                    <li><a href="#results">Success Stories</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>
            
            <div>
                <h4>Get Started</h4>
                <ul>
                    <li><a href="{{ route('login') }}">Member Login</a></li>
                    <li><a href="{{ route('register') }}">Start Free Trial</a></li>
                    <li><a href="#contact">Book a Demo</a></li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>© {{ date('Y') }} Suhaim Soft Work Shop. All rights reserved.</p>
        </div>
    </div>
</footer>

<!-- ══════════════════════════════ SCRIPTS ══════════════════════════════ -->
<script>
    // Mobile hamburger toggle
    const hamburger = document.getElementById('hamburger');
    const mobileNav = document.getElementById('mobile-nav');
    if (hamburger && mobileNav) {
        hamburger.addEventListener('click', () => {
            mobileNav.classList.toggle('open');
        });
        // Close on link click
        mobileNav.querySelectorAll('a').forEach(a => {
            a.addEventListener('click', () => mobileNav.classList.remove('open'));
        });
    }

    // Smooth close mobile nav on resize
    window.addEventListener('resize', () => {
        if (window.innerWidth > 768) mobileNav?.classList.remove('open');
    });

    // Contact form
    function handleContactSubmit(event) {
        event.preventDefault();
        
        // Get form values
        const name = document.getElementById('contact-name')?.value || '';
        const phone = document.getElementById('contact-phone')?.value || '';
        const email = document.getElementById('contact-email')?.value || '';
        const workshop = document.getElementById('contact-workshop')?.value || '';
        const message = document.getElementById('contact-message')?.value || '';
        
        // Format WhatsApp message
        let text = `*New Contact Enquiry*%0A`;
        text += `Name: ${name}%0A`;
        if(phone) text += `Phone: ${phone}%0A`;
        text += `Email: ${email}%0A`;
        if(workshop) text += `Workshop: ${workshop}%0A`;
        text += `Message: ${message}`;
        
        // Open WhatsApp in new tab
        window.open(`https://wa.me/918891479505?text=${text}`, '_blank');
        
        const btn = document.getElementById('contact-submit');
        const original = btn.textContent;
        btn.textContent = 'Sending…';
        btn.disabled = true;
        btn.style.opacity = '0.7';
        setTimeout(() => {
            document.getElementById('contact-form').reset();
            btn.textContent = original;
            btn.disabled = false;
            btn.style.opacity = '1';
            const msg = document.getElementById('contact-success');
            msg.style.display = 'block';
            setTimeout(() => msg.style.display = 'none', 4500);
        }, 1100);
    }

    // Scroll-based header shadow enhancement
    window.addEventListener('scroll', () => {
        const header = document.querySelector('header');
        if (window.scrollY > 20) {
            header.style.boxShadow = '0 2px 16px rgba(0,0,0,0.1)';
        } else {
            header.style.boxShadow = '0 1px 6px rgba(0,0,0,0.06)';
        }
    });
</script>

<!-- PWA Service Worker & Custom Install Prompt -->
<script>
    let deferredPrompt;
    const installBtn = document.getElementById('welcome-pwa-install');

    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js')
                .then(reg => {})
                .catch(err => {});
        });
    }

    window.addEventListener('beforeinstallprompt', (e) => {
        if (installBtn) {
            // Prevent Chrome 67 and earlier from automatically showing the prompt
            e.preventDefault();
            // Stash the event so it can be triggered later.
            deferredPrompt = e;
            // Update UI to notify the user they can install the PWA
            installBtn.style.display = 'inline-flex';
        }
    });

    if (installBtn) {
        installBtn.addEventListener('click', async () => {
            if (!deferredPrompt) return;
            // Show the prompt
            deferredPrompt.prompt();
            // Wait for the user to respond to the prompt
            const { outcome } = await deferredPrompt.userChoice;
            console.log(`User choice outcome: ${outcome}`);
            // Reset prompt
            deferredPrompt = null;
            // Hide install button
            installBtn.style.display = 'none';
        });
    }

    window.addEventListener('appinstalled', (evt) => {
        console.log('PWA app was installed successfully');
        deferredPrompt = null;
        if (installBtn) {
            installBtn.style.display = 'none';
        }
    });
</script>

</body>
</html>
