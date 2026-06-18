<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\WarrantyController;
use App\Http\Controllers\WorkOrderController;
use App\Http\Controllers\BillTemplateController;
use App\Http\Controllers\PurchaseController;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SuperAdminController;

// Guest Routes
Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->isSuperAdmin()) {
            return redirect()->route('super_admin.dashboard');
        }
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('landing');

Route::get('/landing', function () {
    return redirect()->route('landing');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('throttle:login');

Route::get('/support', function () {
    return view('auth.support');
})->name('support');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);


// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/license/activate', [App\Http\Controllers\LicenseController::class, 'activate'])->name('license.activate');
    Route::post('/activate-license', [App\Http\Controllers\ProductKeyController::class, 'activateLicense'])->name('activate_license');

    // Database Backup & Restore management routes (accessible to any authenticated admin / super admin)
    Route::get('/backup', [\App\Http\Controllers\BackupController::class, 'index'])->name('backup.index');
    Route::post('/backup/create', [\App\Http\Controllers\BackupController::class, 'create'])->name('backup.create');
    Route::get('/backup/{filename}/download', [\App\Http\Controllers\BackupController::class, 'download'])->name('backup.download');
    Route::post('/backup/{filename}/restore', [\App\Http\Controllers\BackupController::class, 'restore'])->name('backup.restore');
    Route::post('/backup/upload-restore', [\App\Http\Controllers\BackupController::class, 'uploadRestore'])->name('backup.upload_restore');
    Route::delete('/backup/{filename}', [\App\Http\Controllers\BackupController::class, 'destroy'])->name('backup.destroy');

    // GET fallbacks to prevent 405 Method Not Allowed/Page Expired on backup form refreshes
    Route::get('/backup/create', function () {
        return redirect()->route('backup.index');
    });
    Route::get('/backup/upload-restore', function () {
        return redirect()->route('backup.index');
    });

    // Tenant-scoped Routes
    Route::middleware(['workshop', 'check.trial'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('customers', CustomerController::class);
        Route::resource('vehicles', VehicleController::class);
        Route::resource('products', ProductController::class);
        Route::resource('services', ServiceController::class);
        Route::resource('employees', EmployeeController::class);
        Route::resource('bills', BillController::class);
        Route::get('/bills/{bill}/invoice', [BillController::class, 'invoice'])->name('bills.invoice');
        Route::post('/bills/{bill}/record-payment', [BillController::class, 'recordPayment'])->name('bills.record-payment');

        Route::resource('bill-templates', BillTemplateController::class);
        Route::resource('expenses', ExpenseController::class);
        Route::resource('purchases', PurchaseController::class);
        Route::resource('salaries', SalaryController::class);
        Route::resource('salary-advances', \App\Http\Controllers\SalaryAdvanceController::class);
        Route::resource('employee-payments', \App\Http\Controllers\EmployeePaymentController::class)->except(['index', 'create', 'show']);
        Route::resource('warranties', WarrantyController::class);
        Route::resource('work-orders', WorkOrderController::class);

        // System Settings routes (Admin Only)
        Route::get('/system', [\App\Http\Controllers\SystemController::class, 'index'])->name('system.index');
        Route::post('/system/update', [\App\Http\Controllers\SystemController::class, 'update'])->name('system.update');
        Route::post('/system/change-password', [\App\Http\Controllers\SystemController::class, 'changePassword'])->name('system.change_password');
        Route::post('/system/clear-data', [\App\Http\Controllers\SystemController::class, 'clearData'])->name('system.clear_data');

        // API endpoints for dynamic form data
        Route::get('/api/customers/{customer}/vehicles', function (App\Models\Customer $customer) {
            return $customer->vehicles()->with('customer')->get();
        })->name('api.customer.vehicles');
        Route::get('/api/bill-templates/{billTemplate}', [BillTemplateController::class, 'apiShow'])->name('api.bill-templates.show');
        Route::get('/api/bills/{bill}', function (App\Models\Bill $bill) {
            return $bill->load('customer', 'vehicle', 'items', 'workshop');
        })->name('api.bills.show');
    });

    // Super Admin Protected Routes
    Route::prefix('super-admin')->middleware('super_admin')->group(function () {
        Route::get('/dashboard', [SuperAdminController::class, 'index'])->name('super_admin.dashboard');
        Route::post('/workshops', [SuperAdminController::class, 'storeWorkshop'])->name('super_admin.store_workshop');
        Route::post('/workshops/{workshop}/impersonate', [SuperAdminController::class, 'impersonate'])->name('super_admin.impersonate');
        Route::put('/workshops/{workshop}', [SuperAdminController::class, 'updateWorkshop'])->name('super_admin.update_workshop');
        Route::delete('/workshops/{workshop}', [SuperAdminController::class, 'destroyWorkshop'])->name('super_admin.destroy_workshop');

        Route::post('/settings', [SuperAdminController::class, 'updateSettings'])->name('super_admin.update_settings');

        // Product Keys management routes
        Route::post('/product-keys', [App\Http\Controllers\ProductKeyController::class, 'store'])->name('super_admin.store_product_key');
        Route::put('/product-keys/{productKey}', [App\Http\Controllers\ProductKeyController::class, 'update'])->name('super_admin.update_product_key');
        Route::delete('/product-keys/{productKey}', [App\Http\Controllers\ProductKeyController::class, 'destroy'])->name('super_admin.destroy_product_key');
        Route::delete('/product-keys', [App\Http\Controllers\ProductKeyController::class, 'destroyAll'])->name('super_admin.destroy_all_product_keys');

        // License activation for a workshop (admin-initiated)
        Route::post('/workshops/{workshop}/activate-license', [App\Http\Controllers\SuperAdminController::class, 'activateLicense'])->name('super_admin.activate_license');

        // Activity Logs management routes
        Route::delete('/logs/clear', [SuperAdminController::class, 'clearLogs'])->name('super_admin.clear_logs');
        Route::delete('/logs/{activityLog}', [SuperAdminController::class, 'destroyLog'])->name('super_admin.destroy_log');

        // GET fallback for logs clearing route
        Route::get('/logs/clear', function () {
            return redirect()->route('super_admin.dashboard', ['tab' => 'logs']);
        });

        // GET fallbacks to prevent 405 Method Not Allowed/Page Expired on form refreshes or manual URL entries
        Route::get('/product-keys', function () {
            return redirect()->route('super_admin.dashboard', ['tab' => 'keys']);
        });
        Route::get('/workshops', function () {
            return redirect()->route('super_admin.dashboard', ['tab' => 'workshops']);
        });
        Route::get('/settings', function () {
            return redirect()->route('super_admin.dashboard', ['tab' => 'settings']);
        });
    });
});

// Secure route to run migrations and reset super admin on serverless environments
// Usage: /run-migrations?key=YOUR_SECRET_KEY
Route::get('/run-migrations', function () {
    // Validate secret key — uses the ADMIN_RESET_KEY env var (set in Netlify dashboard)
    // Falls back to a hash of APP_KEY if ADMIN_RESET_KEY is not set
    $secretKey = env('ADMIN_RESET_KEY', hash('sha256', env('APP_KEY', '')));

    if (request()->get('key') !== $secretKey) {
        return response('Unauthorized. Provide the correct ?key= value.', 403)
            ->header('Content-Type', 'text/plain');
    }

    $output = [];

    try {
        // 1. Run migrations
        $output[] = '=== Running Migrations ===';
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $output[] = trim(\Illuminate\Support\Facades\Artisan::output()) ?: 'Migrations already up to date.';

        // 2. Reset super admin (ID=1)
        $output[] = '';
        $output[] = '=== Resetting Super Admin ===';

        $adminEmail = env('ADMIN_EMAIL', 'infosuhaimsoft@gmail.com');
        $adminPass  = env('ADMIN_PASSWORD', '12345678');

        $existing = \App\Models\User::find(1) ?? \App\Models\User::where('email', $adminEmail)->first();

        if ($existing) {
            $existing->update([
                'id'          => 1,
                'name'        => 'Suhaim Soft Super Admin',
                'email'       => $adminEmail,
                'password'    => \Illuminate\Support\Facades\Hash::make($adminPass),
                'role'        => 'super_admin',
                'workshop_id' => null,
            ]);
            $output[] = "✓ Super admin '{$adminEmail}' updated (credentials updated).";
        } else {
            \App\Models\User::create([
                'id'          => 1,
                'name'        => 'Suhaim Soft Super Admin',
                'email'       => $adminEmail,
                'password'    => \Illuminate\Support\Facades\Hash::make($adminPass),
                'role'        => 'super_admin',
                'workshop_id' => null,
            ]);
            $output[] = "✓ Super admin '{$adminEmail}' created.";
        }

        // 3. Remove any stale/old super admin accounts
        $deleted = \App\Models\User::where('id', '!=', 1)
            ->where('role', 'super_admin')
            ->delete();
        if ($deleted > 0) {
            $output[] = "✓ Removed {$deleted} old duplicate super_admin account(s).";
        }

        $output[] = '';
        $output[] = '=== Done! ===';
        $output[] = "Login at: " . config('app.url') . "/login";
        $output[] = "Email:    {$adminEmail}";
        $output[] = "Password: {$adminPass}";

        return response(implode("\n", $output), 200)
            ->header('Content-Type', 'text/plain');

    } catch (\Exception $e) {
        return response('Error: ' . $e->getMessage() . "\n\n" . $e->getTraceAsString(), 500)
            ->header('Content-Type', 'text/plain');
    }
});


// Secure route to import the local MySQL database dump directly to the online database
Route::get('/import-local-db', function () {
    $expectedKey = env('APP_KEY');
    if (str_starts_with($expectedKey, 'base64:')) {
        $expectedKey = substr($expectedKey, 7);
    }
    
    if (request()->get('key') !== $expectedKey) {
        return response('Unauthorized. Please provide the correct key.', 403);
    }

    $sqlPath = base_path('suhaim_workshop.sql');
    if (!file_exists($sqlPath)) {
        return response('Error: suhaim_workshop.sql file not found in repository root.', 404);
    }

    try {
        $sql = file_get_contents($sqlPath);
        
        // Disable foreign key checks temporarily to avoid constraint issues during table drops/creates
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Execute the database dump queries
        \Illuminate\Support\Facades\DB::unprepared($sql);
        
        // Re-enable foreign key checks
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return response("Database successfully imported to online server!\nAll tables and local data have been synchronized.", 200)
            ->header('Content-Type', 'text/plain');
    } catch (\Exception $e) {
        return response("Error importing database: " . $e->getMessage() . "\n\n" . $e->getTraceAsString(), 500)
            ->header('Content-Type', 'text/plain');
    }
});


