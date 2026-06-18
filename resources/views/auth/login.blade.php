<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Suhaim Soft</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="/images/logo.png" type="image/png">
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
                theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] } } }
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-4 sm:p-6 md:p-8">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">

        {{-- Back Button --}}
        <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-slate-500 hover:text-blue-600 text-sm font-semibold transition-colors mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
            </svg>
            Back
        </a>

        {{-- Header --}}
        <div class="text-center mb-6 sm:mb-8">
            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-blue-600 to-blue-500 rounded-2xl flex items-center justify-center mx-auto mb-3 sm:mb-4 shadow-lg shadow-blue-500/30">
                <svg width="24" height="24" viewBox="0 0 512 512" fill="currentColor" class="text-white sm:w-[28px] sm:h-[28px]">
                    <path d="M 334 165 C 334 165 298 140 256 140 C 214 140 178 165 178 210 C 178 260 230 275 270 285 C 300 292 342 308 342 355 C 342 410 290 432 256 432 C 210 432 170 410 170 410 L 182 355 C 182 355 220 380 256 380 C 300 380 342 360 342 315 C 342 265 285 245 242 235 C 208 227 170 205 170 160 C 170 100 226 80 256 80 C 306 80 342 105 342 105 Z" />
                </svg>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-800">Welcome Back</h1>
            <p class="text-slate-500 text-sm mt-1">Log in to your Suhaim Soft account</p>
        </div>

        <form action="{{ route('login.post') }}" method="POST" class="space-y-4 sm:space-y-5" x-data="{ loading: false, showToast: false }" @submit="if(!navigator.onLine) { $event.preventDefault(); showToast = true; setTimeout(() => showToast = false, 3000); return; } loading = true; setTimeout(() => loading = false, 2000)">
            @csrf

            {{-- Email --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Email
                    </span>
                </label>
                <div class="relative">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <input type="email" name="email" required placeholder="admin@example.com" value="{{ old('email') }}"
                           class="w-full pl-10 pr-4 py-2 sm:py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                @error('email')<p class="text-xs text-red-500 mt-1 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </p>@enderror
            </div>

            {{-- Password --}}
            <div x-data="{ show: false }">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 sm:gap-0 mb-1">
                    <label class="block text-sm font-semibold text-slate-700">
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Password
                        </span>
                    </label>
                    <a href="{{ route('support') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 hover:underline">
                        Forgot password?
                    </a>
                </div>
                <div class="relative">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <input :type="show ? 'text' : 'password'" name="password" required placeholder="••••••••"
                           class="w-full pl-10 pr-10 py-2 sm:py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 flex items-center gap-1 p-1">
                        <template x-if="!show">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </template>
                        <template x-if="show">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </template>
                    </button>
                </div>
                @error('password')<p class="text-xs text-red-500 mt-1 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </p>@enderror
            </div>

            {{-- Submit --}}
            <div>
                <button type="submit" :disabled="loading"
                        class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white font-semibold py-2 sm:py-2.5 rounded-lg transition-colors mt-2 flex items-center justify-center gap-2">
                    <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" style="display: none;">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="loading ? 'Signing in...' : 'Sign in'">Sign in</span>
                </button>
            </div>

            {{-- Offline Toast --}}
            <div x-show="showToast" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-2"
                 class="fixed bottom-4 left-1/2 -translate-x-1/2 bg-red-600 text-white px-4 py-2 rounded-lg shadow-lg text-sm font-semibold flex items-center gap-2 z-50"
                 style="display: none;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                No internet connection. Please try again.
            </div>
        </form>

        <div class="mt-5 sm:mt-6 text-center text-sm text-slate-600 flex flex-col sm:flex-row items-center justify-center gap-1 sm:gap-1.5">
            <span>Don't have an account?</span>
            <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:underline">Register your garage</a>
        </div>

    </div>

</body>
</html>
