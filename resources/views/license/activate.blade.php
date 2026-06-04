<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Software Locked - Suhaim Soft</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="glass-card rounded-2xl p-8 max-w-md w-full shadow-xl">

        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-slate-800 transition-colors group">
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Dashboard
            </a>
        </div>

        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <h2 class="text-2xl font-bold text-slate-800">Software Locked</h2>
            <p class="text-slate-600 mt-2 text-sm leading-relaxed">
                Your trial period has expired. Please activate a license key to ensure continuous access to your invoices, customers, and operations.
            </p>
        </div>

        @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-600 text-sm font-semibold rounded-xl p-4 mb-6">
            {{ session('error') }}
        </div>
        @endif

        <form action="{{ route('activate_license') }}" method="POST">
            @csrf
            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-700 mb-2">License Key</label>
                <input type="text" name="product_key" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-mono text-center tracking-widest uppercase placeholder:normal-case placeholder:tracking-normal text-slate-800" placeholder="XXXX-XXXX-XXXX-XXXX" required>
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition-all shadow-md hover:shadow-lg active:scale-[0.98]">
                Activate License
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-slate-200 text-center">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-sm font-semibold text-slate-500 hover:text-slate-800 transition-colors">
                    Sign Out
                </button>
            </form>
            <p class="text-xs text-slate-400 mt-4">Contact Support: 8891479505</p>
        </div>
    </div>
</body>
</html>
