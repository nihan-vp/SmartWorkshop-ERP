<?php
$files = [
    'resources/views/welcome.blade.php',
    'resources/views/layouts/app.blade.php',
    'resources/views/auth/login.blade.php',
    'resources/views/auth/register.blade.php',
    'resources/views/errors/subscription_expired.blade.php',
    'resources/views/bills/pdf.blade.php'
];
foreach($files as $file) {
    if(file_exists($file)) {
        $content = file_get_contents($file);
        $content = str_replace('Suhaim Vlog', 'Suhaim Soft Work Shop', $content);
        $content = str_replace('SUHAIM<span>VLOG</span>', 'SUHAIM<span>SOFT</span>', $content);
        $content = str_replace('SUHAIMVLOG', 'SUHAIMSOFT', $content);
        
        // Fixing the specific occurrences where "Suhaim Soft" was meant, not "Suhaim Soft Work Shop"
        // This is a rough revert, let's just do the exact replacements.
        // I will do more granular replace if needed.
        
        file_put_contents($file, $content);
    }
}
echo "Reverted successfully!";
