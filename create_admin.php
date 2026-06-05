<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Workshop;
use Illuminate\Support\Facades\Hash;

echo "=== DATABASE SEEDING & ADMIN CREATION ===\n\n";

// 1. Ensure Default User (User ID 1) exists
$adminEmail = env('ADMIN_EMAIL', 'infosuhaimsoft@gmail.com');
$adminPass = env('ADMIN_PASSWORD', '12345678');
$adminUser = User::find(1);

if (!$adminUser) {
    // If user 1 doesn't exist by ID, check by email
    $adminUser = User::where('email', $adminEmail)->first();
}

if (!$adminUser) {
    $adminUser = User::create([
        'id' => 1,
        'name' => 'Suhaim Soft Workshop',
        'email' => $adminEmail,
        'password' => Hash::make($adminPass),
        'role' => 'super_admin',
        'workshop_id' => null,
    ]);
    echo "✓ Default Super Admin User '{$adminEmail}' created successfully.\n";
} else {
    $adminUser->update([
        'id' => 1,
        'name' => 'Suhaim Soft Workshop',
        'email' => $adminEmail,
        'password' => Hash::make($adminPass),
        'role' => 'super_admin',
        'workshop_id' => null,
    ]);
    echo "✓ Default Super Admin User '{$adminEmail}' already exists (credentials updated).\n";
}

// 2. Remove all other users
$deletedCount = User::where('id', '!=', 1)->delete();
if ($deletedCount > 0) {
    echo "✓ Deleted {$deletedCount} other user(s) to ensure only 1 user remains.\n";
} else {
    echo "✓ No other users to delete.\n";
}

echo "\nAll systems ready to go! You can now log in.\n";
