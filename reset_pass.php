<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$u = \App\Models\User::where('email', 'admin1@suhaimsoft.com')->first();
if ($u) {
    $u->password = \Illuminate\Support\Facades\Hash::make('12345678');
    $u->save();
    echo "Password reset for " . $u->email . "\n";
} else {
    echo "User not found\n";
}
