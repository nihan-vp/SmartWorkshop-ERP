<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $workshop->name }} — System Inactive</title>
    <link rel="shortcut icon" href="/images/logo.png" type="image/png">
    <link rel="icon" href="/images/logo.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        * { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }

        body {
            background: #f1f5f9;
            margin: 0;
        }

        /* ── Blurred BG Mockup ── */
        .bg-blur-mock {
            position: fixed;
            inset: 0;
            display: flex;
            background: #f1f5f9;
            filter: blur(6px);
            opacity: 0.45;
            pointer-events: none;
            z-index: 0;
        }

        /* ── Overlay ── */
        .overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.35);
            backdrop-filter: blur(2px);
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        /* ── Card ── */
        .card {
            background: #ffffff;
            border-radius: 20px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px -10px rgba(0, 0, 0, 0.18), 0 0 0 1px rgba(0,0,0,0.04);
            overflow: hidden;
            position: relative;
            animation: popIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        @keyframes popIn {
            from { opacity: 0; transform: scale(0.92) translateY(12px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }

        /* Top accent bar */
        .card-top-bar {
            height: 4px;
            background: linear-gradient(90deg, #1d4ed8 0%, #2563eb 50%, #3b82f6 100%);
        }

        .card-body {
            padding: 1.75rem 1.75rem 1.5rem;
        }

        /* Status badge */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 20px;
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #dbeafe;
            margin-bottom: 1rem;
        }

        /* Lock icon */
        .icon-wrap {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
        }

        /* Headings */
        .card h1 {
            font-size: 1.2rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 0.35rem;
            line-height: 1.25;
        }

        .card p.sub {
            font-size: 0.78rem;
            color: #64748b;
            line-height: 1.55;
            margin: 0 0 1.25rem;
        }

        /* Workshop info box */
        .info-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.85rem 1rem;
            margin-bottom: 1rem;
        }

        .info-box .label {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 0.3rem;
        }

        .info-box .name {
            font-size: 1rem;
            font-weight: 800;
            color: #0f172a;
        }

        .info-box .expiry {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.72rem;
            font-weight: 600;
            color: #ef4444;
            margin-top: 0.3rem;
        }

        /* Support button */
        .btn-support {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.7rem 0.9rem;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: #f8fafc;
            text-decoration: none;
            margin-bottom: 1rem;
            transition: all 0.18s ease;
            cursor: pointer;
        }

        .btn-support:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }

        .btn-support .icon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: #dbeafe;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .btn-support .text-label {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #94a3b8;
        }

        .btn-support .text-name {
            font-size: 0.8rem;
            font-weight: 700;
            color: #1e293b;
        }

        .btn-support .arrow {
            margin-left: auto;
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Divider */
        .divider {
            border: none;
            border-top: 1px solid #f1f5f9;
            margin: 0 0 1rem;
        }

        /* Activation input */
        .input-row {
            display: flex;
            gap: 8px;
            margin-bottom: 0.75rem;
        }

        .act-label {
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            margin-bottom: 0.5rem;
        }

        .act-input {
            flex: 1;
            padding: 0.65rem 0.85rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.78rem;
            font-weight: 700;
            font-family: 'Inter', monospace;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #0f172a;
            background: #f8fafc;
            outline: none;
            transition: border-color 0.15s;
            min-width: 0;
        }

        .act-input:focus {
            border-color: #2563eb;
            background: #fff;
        }

        .act-input::placeholder {
            text-transform: none;
            letter-spacing: normal;
            color: #cbd5e1;
            font-weight: 500;
        }

        .btn-activate {
            padding: 0.65rem 1rem;
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 0.78rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.15s;
            white-space: nowrap;
        }

        .btn-activate:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
        }

        .btn-activate:active {
            transform: scale(0.97);
        }

        /* Logout */
        .logout-row {
            text-align: center;
            font-size: 0.75rem;
            color: #94a3b8;
            font-weight: 500;
        }

        .logout-row button {
            background: none;
            border: none;
            color: #2563eb;
            font-weight: 700;
            cursor: pointer;
            font-size: 0.75rem;
            font-family: inherit;
            transition: color 0.15s;
        }

        .logout-row button:hover {
            color: #1d4ed8;
        }

        /* Toast */
        .toast-wrap {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            max-width: 320px;
            width: 100%;
            pointer-events: none;
        }

        .toast {
            pointer-events: all;
            background: #fff;
            border-radius: 12px;
            padding: 0.85rem 1rem;
            display: flex;
            align-items: flex-start;
            gap: 0.65rem;
            box-shadow: 0 8px 24px -4px rgba(0,0,0,0.12), 0 0 0 1px rgba(0,0,0,0.05);
        }

        .toast.error { border-left: 3px solid #ef4444; }
        .toast.success { border-left: 3px solid #10b981; }

        .toast .t-icon {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .toast.error .t-icon { background: #fef2f2; color: #ef4444; }
        .toast.success .t-icon { background: #f0fdf4; color: #10b981; }

        .toast .t-title {
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.07em;
            text-transform: uppercase;
        }

        .toast.error .t-title { color: #dc2626; }
        .toast.success .t-title { color: #059669; }

        .toast .t-msg {
            font-size: 0.75rem;
            font-weight: 500;
            color: #475569;
            margin-top: 1px;
        }

        .toast .t-close {
            margin-left: auto;
            background: none;
            border: none;
            cursor: pointer;
            color: #94a3b8;
            font-size: 14px;
            line-height: 1;
            padding: 2px;
            flex-shrink: 0;
        }

        .toast .t-close:hover { color: #475569; }

        /* Close button */
        .btn-close-modal {
            position: absolute;
            top: 14px;
            right: 14px;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #f1f5f9;
            border: none;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s;
            font-size: 10px;
            font-weight: 800;
            z-index: 20;
            text-decoration: none;
        }
        .btn-close-modal:hover {
            background: #e2e8f0;
            color: #0f172a;
            transform: scale(1.05);
        }

        /* Countdown text */
        .countdown-text {
            font-size: 0.7rem;
            font-weight: 600;
            color: #64748b;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin-bottom: 1rem;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .card { max-width: 100%; border-radius: 16px; }
            .card-body { padding: 1.4rem 1.2rem 1.25rem; }
            .card h1 { font-size: 1.1rem; }
            .toast-wrap { right: 0.75rem; left: 0.75rem; max-width: none; }
        }
    </style>
</head>
<body>

    {{-- ── BLURRED BG MOCKUP ── --}}
    <div class="bg-blur-mock">
        <div style="width:220px;background:#fff;border-right:1px solid #e2e8f0;display:flex;flex-direction:column;padding:1.25rem;gap:1rem;">
            <div style="display:flex;align-items:center;gap:8px;padding-bottom:1rem;border-bottom:1px solid #f1f5f9;">
                <div style="width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,#1d4ed8,#2563eb);display:flex;align-items:center;justify-content:center;">
                    <svg width="16" height="16" viewBox="0 0 512 512" fill="#fff"><path d="M 334 165 C 334 165 298 140 256 140 C 214 140 178 165 178 210 C 178 260 230 275 270 285 C 300 292 342 308 342 355 C 342 410 290 432 256 432 C 210 432 170 410 170 410 L 182 355 C 182 355 220 380 256 380 C 300 380 342 360 342 315 C 342 265 285 245 242 235 C 208 227 170 205 170 160 C 170 100 226 80 256 80 C 306 80 342 105 342 105 Z" /></svg>
                </div>
                <div>
                    <div style="font-size:11px;font-weight:800;color:#1e293b;">{{ $workshop->name }}</div>
                    <div style="font-size:9px;color:#94a3b8;font-weight:600;text-transform:uppercase;">Admin</div>
                </div>
            </div>
            @foreach(['Overview','Customers','Vehicles','Invoices','Expenses'] as $i => $nav)
            <div style="font-size:11px;font-weight:{{ $i===0?'700':'500' }};color:{{ $i===0?'#2563eb':'#94a3b8' }};padding:6px 8px;border-radius:8px;background:{{ $i===0?'#eff6ff':'transparent' }};">{{ $nav }}</div>
            @endforeach
        </div>
        <div style="flex:1;display:flex;flex-direction:column;">
            <div style="height:52px;background:#fff;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;padding:0 1.5rem;">
                <div style="width:120px;height:10px;border-radius:5px;background:#f1f5f9;"></div>
            </div>
            <div style="flex:1;padding:1.5rem;display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;align-content:start;">
                @foreach(['#6366f1','#10b981','#f59e0b'] as $clr)
                <div style="background:#fff;border-radius:14px;padding:1rem;border:1px solid #f1f5f9;">
                    <div style="width:60px;height:8px;border-radius:4px;background:#f1f5f9;margin-bottom:8px;"></div>
                    <div style="width:80px;height:14px;border-radius:4px;background:{{ $clr }}20;"></div>
                </div>
                @endforeach
                <div style="grid-column:span 3;background:#fff;border-radius:14px;padding:1rem;border:1px solid #f1f5f9;height:80px;"></div>
            </div>
        </div>
    </div>

    {{-- ── OVERLAY + MODAL ── --}}
    <div class="overlay">
        <div class="card">
            <a href="{{ route('login') }}" class="btn-close-modal" title="Go to login page">✕</a>
            <div class="card-top-bar"></div>
            <div class="card-body">

                {{-- Badge --}}
                <div class="badge">
                    <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                    {{ $isSuspended ? 'Access Suspended' : 'Trial Expired' }}
                </div>

                {{-- Icon + Title --}}
                <div class="icon-wrap">
                    <svg width="24" height="24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0110 0v4"/>
                    </svg>
                </div>

                <h1>{{ $workshop->name }} is Inactive</h1>
                <p class="sub">Contact us at <strong style="color:#1e293b;">8891479505</strong> to activate your system, or enter your key below.</p>

                {{-- Workshop Info --}}
                <div class="info-box">
                    <div class="label">Registered Account · ID #{{ $workshop->id }}</div>
                    <div class="name">{{ $workshop->name }}</div>
                    @if($workshop->trial_ends_at)
                    <div class="expiry">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                        Expired on: {{ $workshop->trial_ends_at->format('M d, Y h:i A') }} ({{ $workshop->trial_ends_at->diffForHumans() }})
                    </div>
                    @endif
                </div>

                {{-- Support Link --}}
                <a href="{{ route('support') }}" class="btn-support">
                    <div class="icon">
                        <svg width="16" height="16" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><path d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <div>
                        <div class="text-label">Developer Support</div>
                        <div class="text-name">Suhaim Soft &amp; nihan Support</div>
                    </div>
                    <div class="arrow">
                        <svg width="13" height="13" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </div>
                </a>

                <hr class="divider">

                {{-- Activation Key --}}
                <form action="{{ route('activate_license') }}" method="POST">
                    @csrf
                    <div class="act-label">Have an Activation Key?</div>
                    <div class="input-row">
                        <input type="text" name="product_key" required
                               class="act-input"
                               placeholder="SUHAIM-XXXX-XXXX-XXXX">
                        <button type="submit" class="btn-activate">Register</button>
                    </div>
                </form>

                {{-- Logout --}}
                <div class="logout-row" style="margin-bottom: 0.5rem;">
                    Logged in as Admin?&ensp;
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit">Log Out</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- ── TOAST NOTIFICATIONS ── --}}
    <div x-data="toastManager()" x-init="init()" class="toast-wrap" x-cloak>
        <template x-for="t in toasts" :key="t.id">
            <div x-show="t.show"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="toast"
                 :class="t.type">
                <div class="t-icon">
                    <template x-if="t.type === 'error'">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                    </template>
                    <template x-if="t.type === 'success'">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                    </template>
                </div>
                <div style="flex:1;min-width:0;">
                    <div class="t-title" x-text="t.title"></div>
                    <div class="t-msg" x-text="t.message"></div>
                </div>
                <button class="t-close" @click="t.show = false">✕</button>
            </div>
        </template>
    </div>

    <script>
        function toastManager() {
            return {
                toasts: [],
                init() {
                    @if(session('success'))
                        this.add('success', 'Activation Successful', "{{ session('success') }}");
                    @endif
                    @if(session('error'))
                        this.add('error', 'Activation Failed', "{{ session('error') }}");
                    @endif
                },
                add(type, title, message) {
                    const id = Date.now();
                    this.toasts.push({ id, type, title, message, show: true });
                    setTimeout(() => {
                        const t = this.toasts.find(t => t.id === id);
                        if (t) t.show = false;
                    }, 6000);
                }
            }
        }
    </script>
</body>
</html>
