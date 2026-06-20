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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { font-family: 'Inter', sans-serif; box-sizing: border-box; }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            position: relative;
            overflow: hidden;
        }

        /* Animated background orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            animation: floatOrb 8s ease-in-out infinite;
            pointer-events: none;
        }
        .orb-1 { width: 400px; height: 400px; background: rgba(99,102,241,0.18); top: -10%; left: -10%; animation-delay: 0s; }
        .orb-2 { width: 300px; height: 300px; background: rgba(139,92,246,0.15); bottom: -5%; right: -5%; animation-delay: 3s; }
        .orb-3 { width: 200px; height: 200px; background: rgba(59,130,246,0.12); top: 50%; left: 50%; animation-delay: 6s; }

        @keyframes floatOrb {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33%       { transform: translate(20px, -20px) scale(1.05); }
            66%       { transform: translate(-15px, 15px) scale(0.95); }
        }

        /* Card */
        .card {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 28px;
            width: 100%;
            max-width: 440px;
            padding: 2.5rem;
            box-shadow: 0 32px 64px rgba(0,0,0,0.4), 0 0 0 1px rgba(255,255,255,0.05);
            animation: slideUp 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px) scale(0.96); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Lock icon ring */
        .icon-ring {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(239,68,68,0.15) 0%, rgba(239,68,68,0.05) 100%);
            border: 1.5px solid rgba(239,68,68,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            animation: pulseLock 3s ease-in-out infinite;
        }

        @keyframes pulseLock {
            0%, 100% { box-shadow: 0 0 0 0 rgba(239,68,68,0.3); }
            50%       { box-shadow: 0 0 0 12px rgba(239,68,68,0); }
        }

        /* Key input */
        .key-input {
            background: rgba(255,255,255,0.06);
            border: 1.5px solid rgba(255,255,255,0.12);
            border-radius: 16px;
            width: 100%;
            padding: 1rem 1.25rem;
            color: #f1f5f9;
            font-family: 'Courier New', monospace;
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-align: center;
            text-transform: uppercase;
            outline: none;
            transition: all 0.3s ease;
        }
        .key-input::placeholder {
            color: rgba(255,255,255,0.2);
            font-weight: 400;
            letter-spacing: 0.1em;
            font-size: 0.95rem;
        }
        .key-input:focus {
            border-color: rgba(139,92,246,0.6);
            background: rgba(139,92,246,0.08);
            box-shadow: 0 0 0 4px rgba(139,92,246,0.15), 0 0 20px rgba(139,92,246,0.1);
        }
        .key-input.error {
            border-color: rgba(239,68,68,0.6);
            background: rgba(239,68,68,0.06);
            box-shadow: 0 0 0 4px rgba(239,68,68,0.1);
            animation: shake 0.4s ease;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%       { transform: translateX(-8px); }
            40%       { transform: translateX(8px); }
            60%       { transform: translateX(-5px); }
            80%       { transform: translateX(5px); }
        }

        /* Activate button */
        .btn-activate {
            width: 100%;
            padding: 1rem;
            border-radius: 16px;
            background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 50%, #2563eb 100%);
            color: white;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: 0.02em;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 24px rgba(124,58,237,0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            position: relative;
            overflow: hidden;
        }
        .btn-activate::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, transparent 60%);
        }
        .btn-activate:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(124,58,237,0.5);
        }
        .btn-activate:active { transform: translateY(0) scale(0.98); }
        .btn-activate:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        /* Loading spinner on button */
        .btn-spinner {
            width: 18px; height: 18px;
            border: 2.5px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            display: none;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Error / Success alert */
        .alert {
            border-radius: 14px;
            padding: 0.875rem 1.1rem;
            font-size: 0.875rem;
            font-weight: 600;
            display: flex;
            align-items: flex-start;
            gap: 0.625rem;
            animation: fadeIn 0.3s ease;
        }
        .alert-error   { background: rgba(239,68,68,0.12);  border: 1px solid rgba(239,68,68,0.25);  color: #fca5a5; }
        .alert-success { background: rgba(34,197,94,0.12);  border: 1px solid rgba(34,197,94,0.25);  color: #86efac; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Segment boxes for key display */
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
            background: rgba(255,255,255,0.1);
            transition: background 0.3s ease;
        }
        .key-segment.filled { background: linear-gradient(90deg, #7c3aed, #4f46e5); }

        /* Divider */
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.08), transparent);
            margin: 1.5rem 0;
        }

        /* Text helpers */
        .text-dim    { color: rgba(255,255,255,0.4); }
        .text-soft   { color: rgba(255,255,255,0.65); }
        .text-bright { color: rgba(255,255,255,0.9); }

        /* Tooltip hint */
        .key-hint {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.3);
            text-align: center;
            margin-top: 0.5rem;
            letter-spacing: 0.05em;
        }
    </style>
</head>
<body>
    <!-- Background orbs -->
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div class="card">

        <!-- Lock Icon -->
        <div class="text-center">
            <div class="icon-ring">
                <svg width="32" height="32" fill="none" stroke="#f87171" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-bright mb-1">License Required</h1>
            <p class="text-soft text-sm leading-relaxed">
                Your trial has expired. Enter your license key<br>to restore full access to your workshop.
            </p>
        </div>

        <div class="divider"></div>

        
        <?php if(session('error')): ?>
        <div class="alert alert-error mb-5">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="shrink-0 mt-0.5">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span><?php echo e(session('error')); ?></span>
        </div>
        <?php endif; ?>

        
        <form id="licenseForm" action="<?php echo e(route('activate_license')); ?>" method="POST" novalidate>
            <?php echo csrf_field(); ?>

            <div class="mb-2">
                <label class="block text-xs font-bold text-soft uppercase tracking-widest mb-3">License Key</label>
                <input
                    id="product_key"
                    type="text"
                    name="product_key"
                    class="key-input"
                    placeholder="XXXX-XXXX-XXXX-XXXX"
                    maxlength="23"
                    autocomplete="off"
                    spellcheck="false"
                    autofocus
                    required
                >
                <!-- Progress segments -->
                <div class="key-segments" id="keySegments">
                    <div class="key-segment" id="seg0"></div>
                    <div class="key-segment" id="seg1"></div>
                    <div class="key-segment" id="seg2"></div>
                    <div class="key-segment" id="seg3"></div>
                </div>
                <p class="key-hint">Format: XXXX-XXXX-XXXX-XXXX</p>
            </div>

            <!-- Inline validation message -->
            <div id="inlineError" class="alert alert-error mb-4" style="display:none;">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="shrink-0 mt-0.5">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span id="inlineErrorText"></span>
            </div>

            <button type="submit" class="btn-activate mt-4" id="submitBtn">
                <div class="btn-spinner" id="btnSpinner"></div>
                <svg id="btnIcon" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                </svg>
                <span id="btnText">Activate License</span>
            </button>
        </form>

        <div class="divider"></div>

        <!-- Footer -->
        <div class="text-center space-y-3">
            <form action="<?php echo e(route('logout')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" class="text-sm text-dim hover:text-soft transition-colors font-semibold flex items-center gap-1.5 mx-auto">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Sign out
                </button>
            </form>
            <div class="flex items-center justify-center gap-1.5">
                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-dim">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.948V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                <span class="text-xs text-dim font-medium">Need help? Call <a href="tel:8891479505" class="text-violet-400 hover:text-violet-300 font-bold transition-colors">8891479505</a></span>
            </div>
        </div>

    </div>

    <script>
        const input = document.getElementById('product_key');
        const form  = document.getElementById('licenseForm');
        const submitBtn  = document.getElementById('submitBtn');
        const btnSpinner = document.getElementById('btnSpinner');
        const btnIcon    = document.getElementById('btnIcon');
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
            if (raw.length > 16) raw = raw.slice(0, 16);

            let formatted = '';
            for (let i = 0; i < raw.length; i++) {
                if (i > 0 && i % 4 === 0) formatted += '-';
                formatted += raw[i];
            }
            this.value = formatted;

            // Update segment indicators
            const parts = raw.length;
            segments.forEach((seg, i) => {
                const filled = parts >= (i + 1) * 4;
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
            const raw = input.value.replace(/-/g, '');
            if (raw.length < 16) {
                e.preventDefault();
                input.classList.add('error');
                inlineErrorText.textContent = 'Please enter a complete 16-character license key.';
                inlineError.style.display = 'flex';
                input.focus();
                return;
            }

            // Show loading
            submitBtn.disabled = true;
            btnSpinner.style.display = 'block';
            btnIcon.style.display = 'none';
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
            const cleaned = pasted.replace(/[^A-Za-z0-9]/g, '').toUpperCase().slice(0, 16);
            let formatted = '';
            for (let i = 0; i < cleaned.length; i++) {
                if (i > 0 && i % 4 === 0) formatted += '-';
                formatted += cleaned[i];
            }
            this.value = formatted;
            this.dispatchEvent(new Event('input'));
        });
    </script>
</body>
</html>
<?php /**PATH E:\Suhaim Soft Work Shop\suhaimsoftworkshop\server\resources\views\license\activate.blade.php ENDPATH**/ ?>