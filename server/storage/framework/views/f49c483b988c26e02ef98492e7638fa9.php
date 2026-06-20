<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support — Suhaim Soft</title>
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

    <div class="w-full max-w-md bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
        
        <div class="text-center mb-6 sm:mb-8">
            <h1 class="text-xl sm:text-2xl font-bold text-slate-800">Support Contacts</h1>
            <p class="text-slate-500 text-sm mt-2">Contact our developer support team to retrieve or reset credentials.</p>
        </div>

        <div class="space-y-4">
            
            
            <div class="p-4 border border-slate-200 rounded-xl bg-slate-50">
                <p class="text-[10px] font-bold text-blue-600 uppercase tracking-wider mb-1">Developer</p>
                <h4 class="font-bold text-slate-800">nihan</h4>
                <p class="text-sm text-slate-600 mt-1">Phone: <span class="font-medium text-slate-800 block sm:inline">+91 77367 08566</span></p>
                <p class="text-sm text-slate-600">Email: <span class="font-medium text-slate-800 block sm:inline break-all sm:break-normal">alivp@gmail.com</span></p>
            </div>

            
            <div class="p-4 border border-slate-200 rounded-xl bg-slate-50">
                <p class="text-[10px] font-bold text-blue-600 uppercase tracking-wider mb-1">System Support</p>
                <h4 class="font-bold text-slate-800">Suhaim Soft Developer</h4>
                <p class="text-sm text-slate-600 mt-1">Phone: <span class="font-medium text-slate-800 block sm:inline">+91 88914 79505</span></p>
                <p class="text-sm text-slate-600">Email: <span class="font-medium text-slate-800 block sm:inline break-all sm:break-normal">infosuhaimsoft@gmail.com</span></p>
            </div>

        </div>

        <div class="mt-6 sm:mt-8 text-center text-sm text-slate-600 flex flex-col sm:flex-row items-center justify-center gap-1 sm:gap-1.5">
            <span>Have your credentials?</span>
            <a href="<?php echo e(route('login')); ?>" class="text-blue-600 font-semibold hover:underline">Sign in securely</a>
        </div>

    </div>

</body>
</html>
<?php /**PATH E:\Suhaim Soft Work Shop\suhaimsoftworkshop\server\resources\views\auth\support.blade.php ENDPATH**/ ?>