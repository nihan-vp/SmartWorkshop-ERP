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

        // Product Keys management routes
        Route::post('/product-keys', [App\Http\Controllers\ProductKeyController::class, 'store'])->name('super_admin.store_product_key');
        Route::put('/product-keys/{productKey}', [App\Http\Controllers\ProductKeyController::class, 'update'])->name('super_admin.update_product_key');
        Route::delete('/product-keys/{productKey}', [App\Http\Controllers\ProductKeyController::class, 'destroy'])->name('super_admin.destroy_product_key');
    });
});
