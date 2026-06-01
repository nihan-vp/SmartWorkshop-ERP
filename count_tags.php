<?php
$content = file_get_contents('e:/Suhaim Soft Work Shop/suhaimsoftworkshop/resources/views/layouts/app.blade.php');
echo "LAYOUT:\n";
echo "if: " . substr_count($content, '@if') . "\n";
echo "endif: " . substr_count($content, '@endif') . "\n";
echo "auth: " . substr_count($content, '@auth') . "\n";
echo "endauth: " . substr_count($content, '@endauth') . "\n";
echo "guest: " . substr_count($content, '@guest') . "\n";
echo "endguest: " . substr_count($content, '@endguest') . "\n";
echo "section: " . substr_count($content, '@section') . "\n";
echo "endsection: " . substr_count($content, '@endsection') . "\n";
echo "push: " . substr_count($content, '@push') . "\n";
echo "endpush: " . substr_count($content, '@endpush') . "\n";

$content = file_get_contents('e:/Suhaim Soft Work Shop/suhaimsoftworkshop/resources/views/dashboard.blade.php');
echo "DASHBOARD:\n";
echo "if: " . substr_count($content, '@if') . "\n";
echo "endif: " . substr_count($content, '@endif') . "\n";
echo "auth: " . substr_count($content, '@auth') . "\n";
echo "endauth: " . substr_count($content, '@endauth') . "\n";
echo "guest: " . substr_count($content, '@guest') . "\n";
echo "endguest: " . substr_count($content, '@endguest') . "\n";
echo "section: " . substr_count($content, '@section') . "\n";
echo "endsection: " . substr_count($content, '@endsection') . "\n";
echo "push: " . substr_count($content, '@push') . "\n";
echo "endpush: " . substr_count($content, '@endpush') . "\n";
