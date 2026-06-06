<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Workshop — Suhaim Soft</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="icon" href="/images/logo.png" type="image/png">
    <link rel="apple-touch-icon" href="/images/logo.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                }
            }
        }
    </script>
    <style>
        body, html { margin: 0; padding: 0; background-color: #ffffff; }
        
        .login-layout {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* ── LEFT SIDE (GRAPHIC) ── */
        .login-hero {
            position: relative;
            flex: 1;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #3b82f6 100%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 4rem;
            color: #ffffff;
            overflow: hidden;
        }
        
        /* Subtle background pattern/overlay */
        .login-hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle at 20% 30%, rgba(255,255,255,0.1) 0%, transparent 40%),
                              radial-gradient(circle at 80% 80%, rgba(255,255,255,0.1) 0%, transparent 40%);
            z-index: 1;
        }

        /* ── RIGHT SIDE (FORM) ── */
        .login-form-container {
            flex: 1;
            max-width: 600px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 4rem;
            background: #ffffff;
            position: relative;
        }

        .pulse-glow { animation: pulseGlow 2s infinite; }
        @keyframes pulseGlow {
            0% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.4); }
            70% { box-shadow: 0 0 0 15px rgba(37, 99, 235, 0); }
            100% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0); }
        }

        /* ── ANIMATIONS ── */
        .fade-in { animation: fadeIn 0.8s ease-out forwards; opacity: 0; }
        .slide-up { animation: slideUp 0.6s ease-out forwards; opacity: 0; transform: translateY(20px); }
        @keyframes fadeIn { to { opacity: 1; } }
        @keyframes slideUp { to { opacity: 1; transform: translateY(0); } }

        /* ── RESPONSIVE ── */
        @media (max-width: 1024px) {
            .login-layout { flex-direction: column; }
            .login-hero { 
                flex: none; 
                padding: 3rem 2rem 4rem 2rem; 
                justify-content: center; 
                text-align: center; 
            }
            .login-hero-footer { display: none; }
            .login-hero h1 { font-size: 2.5rem; }
            .login-hero p { font-size: 1.1rem; margin: 0 auto; }
            .login-hero .inline-flex { margin: 0 auto 1.5rem auto; }
            
            .login-form-container { 
                max-width: 100%; 
                padding: 3rem 2rem; 
                border-radius: 2rem 2rem 0 0; 
                margin-top: -2rem; 
                z-index: 10; 
                box-shadow: 0 -15px 40px rgba(0,0,0,0.1); 
            }
        }
        @media (max-width: 640px) {
            .login-hero { padding: 2rem 1.5rem 3.5rem 1.5rem; }
            .login-hero h1 { font-size: 2rem; }
            .login-hero p { font-size: 1rem; }
            .login-form-container { padding: 2.5rem 1.5rem; }
        }
    </style>
</head>
<body>

    <div class="login-layout">
        
        {{-- LEFT SIDE --}}
        <div class="login-hero fade-in">
            <div class="z-10 text-center md:text-left">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 mb-8 mx-auto md:mx-0">
                    <svg width="32" height="32" viewBox="0 0 512 512" fill="currentColor" class="text-white">
                        <path d="M 334 165 C 334 165 298 140 256 140 C 214 140 178 165 178 210 C 178 260 230 275 270 285 C 300 292 342 308 342 355 C 342 410 290 432 256 432 C 210 432 170 410 170 410 L 182 355 C 182 355 220 380 256 380 C 300 380 342 360 342 315 C 342 265 285 245 242 235 C 208 227 170 205 170 160 C 170 100 226 80 256 80 C 306 80 342 105 342 105 Z" />
                    </svg>
                </div>
                <h1 class="text-4xl md:text-5xl font-black tracking-tight mb-4 leading-tight">Suhaim Soft <br/><span class="text-blue-300">Web Dashboard</span></h1>
                <p class="text-blue-100 text-lg md:text-xl font-medium max-w-md mx-auto md:mx-0">Empowering auto repair shops globally with enterprise-grade cloud tools.</p>
            </div>

            <div class="z-10 login-hero-footer mt-12">
                <div class="p-6 bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl max-w-sm">
                    <div class="flex gap-1 text-yellow-400 mb-3">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </div>
                    <p class="text-white text-sm font-medium italic">"Switching our garage to Suhaim Soft was the best decision. Invoicing is instant, and tracking vehicles is seamless."</p>
                    <p class="text-blue-200 text-xs font-bold mt-4 uppercase tracking-wider">— Verified Workshop Owner</p>
                </div>
            </div>
        </div>

        {{-- RIGHT SIDE (CONTENT) --}}
        <div class="login-form-container">
            <div class="w-full slide-up" style="animation-delay: 0.1s;">
                
                {{-- Return to Login Link --}}
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-blue-600 transition-colors mb-10 text-sm font-bold w-fit">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/></svg>
                    Back to Login
                </a>

                <div class="w-20 h-20 bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100 rounded-2xl flex items-center justify-center text-blue-600 shadow-inner mb-8 transform -rotate-3 hover:rotate-0 transition-transform duration-300">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6M9 11h6M9 15h4"/>
                    </svg>
                </div>

                <div class="mb-10">
                    <h2 class="text-3xl font-black text-slate-900 mb-3 tracking-tight">Register Your Garage</h2>
                    <p class="text-slate-500 font-medium text-base leading-relaxed">
                        To maintain a highly secure ecosystem, new workshop registrations and product keys are provided directly by our Super Admin.
                    </p>
                </div>

                {{-- Premium Call Box --}}
                <div class="block relative w-full p-1 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 group mt-4">
                    <div class="absolute inset-0 bg-white/20 rounded-2xl blur-sm transition-colors"></div>
                    <div class="relative bg-white rounded-xl p-5 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 shrink-0 pulse-glow">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <div class="text-left">
                                <p class="text-[10px] text-blue-500 font-extrabold uppercase tracking-wider mb-0.5">Direct Setup Line</p>
                                <p class="text-lg font-black text-slate-800">+91 8891479505</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Register Link --}}
                <div class="mt-12 pt-8 border-t border-slate-100 text-center">
                    <p class="text-sm text-slate-600 font-medium">Already registered? 
                        <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:text-blue-800 transition-colors ml-1 hover:underline underline-offset-4">Return to Login</a>
                    </p>
                </div>

                {{-- Footer --}}
                <div class="mt-8 text-center">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">© {{ date('Y') }} Suhaim Soft</p>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
