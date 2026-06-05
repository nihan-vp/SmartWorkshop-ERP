<?php
$file = 'resources/views/layouts/app.blade.php';
$content = file_get_contents($file);
$content = preg_replace('/\{\{-- React-style Floating Toast Notifications --\}\}.*?<\/div>\s*<main/s', '<main', $content);
file_put_contents($file, $content);
echo 'Removed toasts';
