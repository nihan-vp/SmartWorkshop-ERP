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

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/bills/{bill}/pdf', [BillController::class, 'downloadPDF'])->name('bills.pdf');

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

        Route::resource('bill-templates', BillTemplateController::class);
        Route::resource('expenses', ExpenseController::class);
        Route::resource('purchases', PurchaseController::class);
        Route::resource('salaries', SalaryController::class);
        Route::resource('salary-advances', \App\Http\Controllers\SalaryAdvanceController::class);
        Route::resource('employee-payments', \App\Http\Controllers\EmployeePaymentController::class)->except(['index', 'create', 'show']);
        Route::resource('warranties', WarrantyController::class);
        Route::resource('work-orders', WorkOrderController::class);

        // File Viewer routes (Admin Only)
        Route::get('/file-viewer', [\App\Http\Controllers\FileViewerController::class, 'index'])->name('file-viewer.index');
        Route::get('/file-viewer/download', [\App\Http\Controllers\FileViewerController::class, 'download'])->name('file-viewer.download');
        Route::delete('/file-viewer/delete', [\App\Http\Controllers\FileViewerController::class, 'destroy'])->name('file-viewer.destroy');

        // System Settings routes (Admin Only)
        Route::get('/system', [\App\Http\Controllers\SystemController::class, 'index'])->name('system.index');
        Route::post('/system/update', [\App\Http\Controllers\SystemController::class, 'update'])->name('system.update');
        Route::post('/system/clear-data', [\App\Http\Controllers\SystemController::class, 'clearData'])->name('system.clear_data');

        // API endpoints for dynamic form data
        Route::get('/api/customers/{customer}/vehicles', function (App\Models\Customer $customer) {
            return $customer->vehicles;
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
        Route::put('/workshops/{workshop}', [SuperAdminController::class, 'updateWorkshop'])->name('super_admin.update_workshop');
        Route::delete('/workshops/{workshop}', [SuperAdminController::class, 'destroyWorkshop'])->name('super_admin.destroy_workshop');

        Route::post('/settings', [SuperAdminController::class, 'updateSettings'])->name('super_admin.update_settings');

        // Product Keys management routes
        Route::post('/product-keys', [App\Http\Controllers\ProductKeyController::class, 'store'])->name('super_admin.store_product_key');
        Route::put('/product-keys/{productKey}', [App\Http\Controllers\ProductKeyController::class, 'update'])->name('super_admin.update_product_key');
        Route::delete('/product-keys/{productKey}', [App\Http\Controllers\ProductKeyController::class, 'destroy'])->name('super_admin.destroy_product_key');

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
