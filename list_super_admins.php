<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Create / update Super Admin 1
$existing = \App\Models\User::where('email', 'infosuhaimsoft@gmail.com')->first();

if ($existing) {
    $existing->update([
        'name'        => 'Suhaim Soft Super Admin',
        'email'       => 'infosuhaimsoft@gmail.com',
        'password'    => \Illuminate\Support\Facades\Hash::make('12345678'),
        'role'        => 'super_admin',
        'workshop_id' => null,
    ]);
    echo "✓ Super Admin 1 updated!" . PHP_EOL;
} else {
    \App\Models\User::create([
        'name'        => 'Suhaim Soft Super Admin',
        'email'       => 'infosuhaimsoft@gmail.com',
        'password'    => \Illuminate\Support\Facades\Hash::make('12345678'),
        'role'        => 'super_admin',
        'workshop_id' => null,
    ]);
    echo "✓ Super Admin 1 created!" . PHP_EOL;
}

echo PHP_EOL . "=== All Super Admins ===" . PHP_EOL;
$users = \App\Models\User::where('role', 'super_admin')->get();
foreach ($users as $u) {
    echo "ID: {$u->id} | {$u->name} | {$u->email}" . PHP_EOL;
}
