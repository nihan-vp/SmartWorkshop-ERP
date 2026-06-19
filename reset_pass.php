<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = \App\Models\User::all();
echo "--- ALL ACCOUNTS AND PASSWORDS ---\n";
foreach ($users as $u) {
    $u->password = \Illuminate\Support\Facades\Hash::make('12345678');
    $u->save();
    echo "Email: " . str_pad($u->email, 30) . " | Role: " . str_pad($u->role, 15) . " | Password: 12345678\n";
}
echo "----------------------------------\n";
