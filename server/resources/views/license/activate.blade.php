<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activate License — Suhaim Soft</title>
    <link rel="shortcut icon" href="/images/logo.png" type="image/png">
    <link rel="icon" href="/images/logo.png" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .bg-blur-mock {
            position: fixed;
            inset: 0;
            display: flex;
            background: #f1f5f9;
            filter: blur(8px);
            opacity: 0.5;
            pointer-events: none;
            z-index: 0;
        }
        /* Backdrop */
        .overlay {
            position: fixed;
            inset: 0;
            background: rgba(30, 41, 59, 0.4);
            backdrop-filter: blur(4px);
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        /* Progress segments */
        .key-segments {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin-top: 0.5rem;
        }
        .key-segment {
            flex: 1;
            height: 4px;
            border-radius: 999px;
            background: rgba(0,0,0,0.08);
            transition: background 0.3s ease;
        }
        .key-segment.filled { background: linear-gradient(90deg, #3b82f6, #2563eb); }
        .key-input.error {
            border-color: #ef4444;
            background: #fef2f2;
            box-shadow: 0 0 0 1px #ef4444;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center font-sans antialiased">

    {{-- Blurred BG Mockup of the dashboard to simulate popping up --}}
    <div class="bg-blur-mock">
        <div style="width:220px;background:#fff;border-right:1px solid #e2e8f0;display:flex;flex-direction:column;padding:1.25rem;gap:1rem;">
            <div style="display:flex;align-items:center;gap:8px;padding-bottom:1rem;border-bottom:1px solid #f1f5f9;">
                <div style="width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,#1d4ed8,#2563eb);display:flex;align-items:center;justify-content:center;">
                    <svg width="16" height="16" viewBox="0 0 512 512" fill="#fff"><path d="M 334 165 C 334 165 298 140 256 140 C 214 140 178 165 178 210 C 178 260 230 275 270 285 C 300 292 342 308 342 355 C 342 410 290 432 256 432 C 210 432 170 410 170 410 L 182 355 C 182 355 220 380 256 380 C 300 380 342 360 342 315 C 342 265 285 245 242 235 C 208 227 170 205 170 160 C 170 100 226 80 256 80 C 306 80 342 105 342 105 Z" /></svg>
                </div>
                <div style="width:80px;height:12px;background:#e2e8f0;border-radius:4px;"></div>
            </div>
            <div style="height:16px;border-radius:4px;background:#f1f5f9;margin-bottom:8px;"></div>
            <div style="height:16px;border-radius:4px;background:#f1f5f9;margin-bottom:8px;"></div>
            <div style="height:16px;border-radius:4px;background:#f1f5f9;margin-bottom:8px;"></div>
            <div style="height:16px;border-radius:4px;background:#f1f5f9;margin-bottom:8px;"></div>
            <div style="height:16px;border-radius:4px;background:#f1f5f9;margin-bottom:8px;"></div>
        </div>
        <div style="flex:1;display:flex;flex-direction:column;">
            <div style="height:52px;background:#fff;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;padding:0 1.5rem;">
                <div style="width:120px;height:10px;border-radius:5px;background:#f1f5f9;"></div>
            </div>
            <div style="flex:1;padding:1.5rem;display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;align-content:start;">
                <div style="background:#fff;border-radius:14px;padding:1rem;border:1px solid #f1f5f9;height:60px;"></div>
                <div style="background:#fff;border-radius:14px;padding:1rem;border:1px solid #f1f5f9;height:60px;"></div>
                <div style="background:#fff;border-radius:14px;padding:1rem;border:1px solid #f1f5f9;height:60px;"></div>
                <div style="grid-column:span 3;background:#fff;border-radius:14px;padding:1rem;border:1px solid #f1f5f9;height:120px;"></div>
            </div>
        </div>
    </div>

    {{-- The overlay and modal card --}}
    <div class="overlay">
        <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl overflow-hidden">
            
            <form id="layout-modal-logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>

            <form id="licenseForm" action="{{ route('activate_license') }}" method="POST" novalidate>
                @csrf
                
                <div class="px-6 pt-6 pb-6 sm:px-8 sm:pt-8">
                    {{-- Header --}}
                    <div class="mb-5">
                        <h3 class="text-xl font-bold text-slate-900 font-outfit">Activate License</h3>
                        <p class="text-sm text-slate-500 mt-1.5">Redeem your product key to activate or extend subscription</p>
                    </div>

                    {{-- Session error --}}
                    @if(session('error'))
                    <div class="mb-5 bg-red-50 border border-red-200 rounded-lg p-3 flex items-start gap-2">
                        <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>
                            <h4 class="text-sm font-bold text-red-800">Activation Error</h4>
                            <span class="text-sm font-medium text-red-700">{{ session('error') }}</span>
                        </div>
                    </div>
                    @endif

                    {{-- Key Input --}}
                    <div>
                        <input
                            id="product_key"
                            type="text"
                            name="product_key"
                            required
                            autocomplete="off"
                            spellcheck="false"
                            maxlength="27"
                            class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl font-mono text-base tracking-[0.1em] text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-1 focus:border-blue-500 focus:ring-blue-500 transition-colors"
                            placeholder="SUHAIM-XXXX-XXXX-XXXX-XXXX"
                        >
                        <!-- Progress segments -->
                        <div class="key-segments" id="keySegments">
                            <div class="key-segment" id="seg0"></div>
                            <div class="key-segment" id="seg1"></div>
                            <div class="key-segment" id="seg2"></div>
                            <div class="key-segment" id="seg3"></div>
                        </div>
                        
                        <!-- Inline validation message -->
                        <div id="inlineError" class="mt-3 bg-red-50 border border-red-200 rounded-lg p-3 flex items-start gap-2" style="display:none;">
                            <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span id="inlineErrorText" class="text-sm font-medium text-red-700"></span>
                        </div>
                    </div>
                </div>

                {{-- Footer with buttons --}}
                <div class="bg-white px-6 py-4 sm:px-8 sm:pb-8 flex flex-col-reverse sm:flex-row sm:items-center justify-between gap-4 rounded-b-2xl">
                    <div class="text-sm text-slate-500 text-center sm:text-left">
                        Need a key? Contact Suhaim Soft<br>
                        <a href="tel:8891479505" class="text-blue-500 font-semibold hover:underline">8891479505</a> or 
                        <a href="tel:7736708566" class="text-blue-500 font-semibold hover:underline">7736708566</a>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 w-full sm:w-auto">
                        <button type="button" onclick="document.getElementById('layout-modal-logout-form').submit();"
                                class="px-5 py-2.5 w-full sm:w-auto rounded-xl border border-rose-200 bg-rose-50 hover:bg-rose-100 text-sm font-semibold text-rose-600 transition-colors flex items-center justify-center gap-2 focus:outline-none focus:ring-2 focus:ring-rose-200"
                                title="Sign Out">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Sign Out
                        </button>
                        <a href="{{ route('landing') }}"
                           class="px-5 py-2.5 w-full sm:w-auto rounded-xl border border-slate-300 bg-white text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors focus:outline-none focus:ring-2 focus:ring-slate-200 text-center">
                            Cancel
                        </a>
                        <button type="submit" id="submitBtn"
                                class="px-5 py-2.5 w-full sm:w-auto rounded-xl bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold transition-colors flex items-center justify-center gap-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                            <div class="btn-spinner w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin hidden" id="btnSpinner"></div>
                            <span id="btnText">Activate License</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        const input = document.getElementById('product_key');
        const form  = document.getElementById('licenseForm');
        const submitBtn  = document.getElementById('submitBtn');
        const btnSpinner = document.getElementById('btnSpinner');
        const btnText    = document.getElementById('btnText');
        const inlineError    = document.getElementById('inlineError');
        const inlineErrorText= document.getElementById('inlineErrorText');
        const segments = [
            document.getElementById('seg0'),
            document.getElementById('seg1'),
            document.getElementById('seg2'),
            document.getElementById('seg3'),
        ];

        // Auto-format key with dashes as user types
        input.addEventListener('input', function (e) {
            let raw = this.value.replace(/[^A-Za-z0-9]/g, '').toUpperCase();
            let isSuhaim = raw.startsWith('SUHAIM');
            let formatted = '';

            if (isSuhaim) {
                let rest = raw.slice(6, 22);
                formatted = 'SUHAIM';
                if (rest.length > 0) {
                    formatted += '-';
                    for (let i = 0; i < rest.length; i++) {
                        if (i > 0 && i % 4 === 0) formatted += '-';
                        formatted += rest[i];
                    }
                }
            } else {
                let clean = raw.slice(0, 16);
                for (let i = 0; i < clean.length; i++) {
                    if (i > 0 && i % 4 === 0) formatted += '-';
                    formatted += clean[i];
                }
            }
            this.value = formatted;

            // Update segment indicators
            const parts = raw.length;
            segments.forEach((seg, i) => {
                const required = isSuhaim ? (6 + (i + 1) * 4) : ((i + 1) * 4);
                const filled = parts >= required;
                seg.classList.toggle('filled', filled);
            });

            // Clear error state
            this.classList.remove('error');
            inlineError.style.display = 'none';
        });

        // Handle backspace for dashes
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Backspace') {
                const val = this.value;
                if (val.length > 0 && val[val.length - 1] === '-') {
                    e.preventDefault();
                    this.value = val.slice(0, -2);
                    // Re-trigger input
                    this.dispatchEvent(new Event('input'));
                }
            }
        });

        // Form submit with loading state
        form.addEventListener('submit', function (e) {
            const raw = input.value.replace(/[^A-Za-z0-9]/g, '');
            const isSuhaim = raw.startsWith('SUHAIM');
            const expectedLength = isSuhaim ? 22 : 16;
            if (raw.length < expectedLength) {
                e.preventDefault();
                input.classList.add('error');
                inlineErrorText.textContent = isSuhaim 
                    ? 'Please enter a complete 22-character license key (including SUHAIM prefix).'
                    : 'Please enter a complete 16-character license key.';
                inlineError.style.display = 'flex';
                input.focus();
                return;
            }

            // Show loading
            submitBtn.disabled = true;
            btnSpinner.classList.remove('hidden');
            btnText.textContent = 'Activating…';
        });

        // Remove error class on focus
        input.addEventListener('focus', function () {
            this.classList.remove('error');
            inlineError.style.display = 'none';
        });

        // Paste support — strip formatting automatically
        input.addEventListener('paste', function (e) {
            e.preventDefault();
            const pasted = (e.clipboardData || window.clipboardData).getData('text');
            const cleaned = pasted.replace(/[^A-Za-z0-9]/g, '').toUpperCase();
            let isSuhaim = cleaned.startsWith('SUHAIM');
            let formatted = '';
            if (isSuhaim) {
                let rest = cleaned.slice(6, 22);
                formatted = 'SUHAIM';
                if (rest.length > 0) {
                    formatted += '-';
                    for (let i = 0; i < rest.length; i++) {
                        if (i > 0 && i % 4 === 0) formatted += '-';
                        formatted += rest[i];
                    }
                }
            } else {
                let clean = cleaned.slice(0, 16);
                for (let i = 0; i < clean.length; i++) {
                    if (i > 0 && i % 4 === 0) formatted += '-';
                    formatted += clean[i];
                }
            }
            this.value = formatted;
            this.dispatchEvent(new Event('input'));
        });

        // Trigger input event on load if input has value
        if (input.value) {
            input.dispatchEvent(new Event('input'));
        }
    </script>
</body>
</html>
