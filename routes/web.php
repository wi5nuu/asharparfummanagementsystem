<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SettingController;

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Products
    Route::resource('products', ProductController::class);
    Route::get('/products/{product}/barcode', [ProductController::class, 'printBarcode'])->name('products.barcode');
    
    // Transactions - IMPORTANT: Manual routes before resource to avoid conflicts
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::resource('transactions', TransactionController::class)->except(['create', 'store']);
    Route::get('/transactions/{transaction}/print', [TransactionController::class, 'printInvoice'])->name('transactions.print');
    Route::get('/api/products/{id}', [TransactionController::class, 'getProductInfo']);
    
    // Inventory
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::post('/inventory/adjust', [InventoryController::class, 'adjust'])->name('inventory.adjust');
    Route::post('/inventory/audit', [InventoryController::class, 'audit'])->name('inventory.audit');
    
    // Customers
    Route::resource('customers', CustomerController::class);
    
    // Coupons
    Route::resource('coupons', CouponController::class);
    Route::post('/coupons/{coupon}/redeem', [CouponController::class, 'redeem'])->name('coupons.redeem');
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
        Route::get('/inventory', [ReportController::class, 'inventory'])->name('inventory');
        Route::get('/profit-loss', [ReportController::class, 'profitLoss'])->name('profit-loss');
        Route::get('/customers', [ReportController::class, 'customerAnalytics'])->name('customers');
        Route::get('/export/sales', [ReportController::class, 'exportSales'])->name('export.sales');
    });
    
    // Employees
    Route::resource('employees', EmployeeController::class);
    Route::post('/employees/{employee}/attendance', [EmployeeController::class, 'attendance'])->name('employees.attendance');
    
    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings/backup', [SettingController::class, 'backup'])->name('settings.backup');
    Route::post('/settings/restore', [SettingController::class, 'restore'])->name('settings.restore');
});

// API Routes
Route::prefix('api')->middleware('auth')->group(function () {
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats']);
    Route::get('/products/search', [ProductController::class, 'search']);
    Route::get('/inventory/alerts', [InventoryController::class, 'getAlerts']);
});

require __DIR__.'/auth.php';