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
        $content = str_replace('Suhaim Soft Work Shop', 'Suhaim Vlog', $content);
        $content = str_replace('Suhaim Soft Workshop', 'Suhaim Vlog', $content);
        $content = str_replace('Suhaim Soft', 'Suhaim Vlog', $content);
        $content = str_replace('SUHAIM<span>SOFT</span>', 'SUHAIM<span>VLOG</span>', $content);
        $content = str_replace('SUHAIMSOFT', 'SUHAIMVLOG', $content);
        file_put_contents($file, $content);
    }
}
echo "Renamed successfully!";
