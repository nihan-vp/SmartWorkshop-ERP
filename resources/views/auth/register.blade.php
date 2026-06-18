<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — Suhaim Soft</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-4 sm:p-6 md:p-8">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8 text-center">
        
        <div class="mb-5 sm:mb-6">
            <h1 class="text-xl sm:text-2xl font-bold text-slate-800">Register Your Garage</h1>
            <p class="text-slate-500 text-sm mt-2 sm:mt-3 leading-relaxed">
                To maintain a highly secure ecosystem, new workshop registrations and product keys are provided directly by our team.
            </p>
        </div>

        <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 sm:p-5 mb-5 sm:mb-6">
            <p class="text-xs text-blue-600 font-bold uppercase tracking-wider mb-1">Direct Setup Line</p>
            <p class="text-lg sm:text-xl font-bold text-slate-800">+91 8891479505</p>
        </div>

        <div class="text-sm text-slate-600 flex flex-col sm:flex-row items-center justify-center gap-1 sm:gap-1.5">
            <span>Already registered?</span>
            <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline">Return to Login</a>
        </div>

    </div>

</body>
</html>
