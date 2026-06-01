<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Workshop;
use Illuminate\Support\Facades\Hash;

echo "=== DATABASE SEEDING & ADMIN CREATION ===\n\n";

// 1. Ensure Default Workshop exists
$workshop = Workshop::find(1);
if (!$workshop) {
    $workshop = Workshop::create([
        'id' => 1,
        'name' => 'Suhaim Soft Work Shop',
        'phone' => '+91 9876543210',
        'email' => 'info@suhaimsoft.com',
        'address' => '123 Workshop Avenue, City',
        'gstin' => '29XXXXXXXXXX1Z5',
        'subscription_status' => 'active',
    ]);
    echo "✓ Default Workshop ID=1 created.\n";
} else {
    echo "✓ Default Workshop ID=1 already exists.\n";
}

// 2. Ensure Admin User exists
$adminEmail = 'alivpsuhaim@gmail.com';
$adminUser = User::where('email', $adminEmail)->first();
if (!$adminUser) {
    $adminUser = User::create([
        'name' => 'Suhaim Admin',
        'email' => $adminEmail,
        'password' => Hash::make('12345678'),
        'workshop_id' => $workshop->id,
        'role' => 'admin',
    ]);
    echo "✓ Admin User '{$adminEmail}' created successfully with password '12345678'.\n";
} else {
    // Update password just in case it was modified
    $adminUser->update([
        'password' => Hash::make('12345678'),
        'workshop_id' => $workshop->id,
        'role' => 'admin',
    ]);
    echo "✓ Admin User '{$adminEmail}' already exists (password reset to '12345678').\n";
}

// 3. Ensure Super Admin User exists for testing
$superAdminEmail = 'superadmin@suhaimsoft.com';
$superAdmin = User::where('email', $superAdminEmail)->first();
if (!$superAdmin) {
    $superAdmin = User::create([
        'name' => 'Super Admin',
        'email' => $superAdminEmail,
        'password' => Hash::make('12345678'),
        'workshop_id' => $workshop->id,
        'role' => 'super_admin',
    ]);
    echo "✓ Super Admin User '{$superAdminEmail}' created successfully with password '12345678'.\n";
} else {
    $superAdmin->update([
        'password' => Hash::make('12345678'),
        'workshop_id' => $workshop->id,
        'role' => 'super_admin',
    ]);
    echo "✓ Super Admin User '{$superAdminEmail}' already exists.\n";
}

// 4. Run database seeders if database is empty
if (\App\Models\Customer::count() == 0) {
    echo "\nSeeding remaining tables...\n";
    $seeder = new \Database\Seeders\DatabaseSeeder();
    $seeder->run();
    echo "✓ Remaining tables seeded successfully.\n";
} else {
    echo "✓ Database tables are already seeded.\n";
}

echo "\nAll systems ready to go! You can now log in.\n";
