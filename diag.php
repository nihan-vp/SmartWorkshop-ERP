<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$workshops = \App\Models\Workshop::all();
echo "Total workshops: " . $workshops->count() . "\n";
foreach ($workshops as $w) {
    echo "  ID={$w->id}  name={$w->name}  status={$w->subscription_status}\n";
}

$users = \App\Models\User::all(['id','name','email','role','workshop_id']);
echo "\nTotal users: " . $users->count() . "\n";
foreach ($users as $u) {
    echo "  ID={$u->id}  name={$u->name}  role={$u->role}  workshop_id={$u->workshop_id}\n";
}

// Check what SuperAdminController::index actually returns
$ws = \App\Models\Workshop::with(['users' => fn($q) => $q->where('role','admin')])->withCount(['users','bills'])->orderBy('name')->get();
echo "\nWorkshops via controller query: " . $ws->count() . "\n";
