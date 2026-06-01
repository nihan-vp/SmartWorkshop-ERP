<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Workshop;
use App\Models\User;

echo "=== FIXING ORPHANED ADMIN USERS ===\n\n";

// Get all admin users with no workshop
$orphans = User::where('role', 'admin')->whereNull('workshop_id')->get();

echo "Found {$orphans->count()} admin user(s) with no workshop.\n\n";

foreach ($orphans as $user) {
    DB::transaction(function () use ($user) {
        // Create a workshop based on the user's name
        $workshop = Workshop::create([
            'name'                => $user->name . "'s Workshop",
            'phone'               => 'N/A',
            'email'               => $user->email,
            'address'             => null,
            'gstin'               => null,
            'subscription_status' => 'active',
            'trial_ends_at'       => null,
            'alert_message'       => null,
            'alert_expires_at'    => null,
        ]);

        // Link user to the workshop
        $user->update(['workshop_id' => $workshop->id]);

        echo "  ✓ Created workshop '{$workshop->name}' (ID={$workshop->id}) for user '{$user->name}' (ID={$user->id})\n";
    });
}

echo "\n=== CLEARING OLD DATABASE SESSIONS ===\n";
try {
    $deleted = DB::table('sessions')->delete();
    echo "  ✓ Cleared {$deleted} old session(s) from database.\n";
} catch (\Exception $e) {
    echo "  ⚠ Sessions table not in DB (file driver in use) — skipping.\n";
}

echo "\n=== VERIFYING FIX ===\n";
echo "Total workshops now: " . Workshop::count() . "\n";
echo "Users with workshop_id:\n";
User::where('role', 'admin')->each(function ($u) {
    echo "  ID={$u->id}  name={$u->name}  workshop_id={$u->workshop_id}\n";
});
echo "\nDone! Please log out and log back in.\n";
