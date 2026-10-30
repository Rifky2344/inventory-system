<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StockMutationController;

use App\Http\Controllers\WarehouseOrderController;
use App\Http\Controllers\Sales\SalesVisitController;
use App\Http\Controllers\Sales\InvoiceController;
use App\Http\Controllers\Finance\PaymentController;
use App\Http\Controllers\Finance\InstallmentPlanController;
use App\Http\Controllers\InventoryExportController;
use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    $role = auth()->user()->role?->slug;

    return match ($role) {
        'admin', 'super-admin' => redirect()->route('dashboard'),
        'staff-gudang' => redirect()->route('warehouse-orders.index'),
        'sales' => redirect()->route('invoices.index'),
        default => redirect()->route('attendances.index'),
    };
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::get('/dashboard', DashboardController::class)
        ->middleware('role:super-admin,admin')
        ->name('dashboard');

    // Attendance Routes (All authenticated users)
    Route::get('/attendances/export', [InventoryExportController::class, 'attendances'])->name('attendances.export');
    Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
    Route::get('/attendances/create', [AttendanceController::class, 'create'])->name('attendances.create');
    Route::post('/attendances', [AttendanceController::class, 'store'])->name('attendances.store');
    Route::get('/attendances/{attendance}', [AttendanceController::class, 'show'])->name('attendances.show');

    // Staff Gudang (read-only + input transaksi)
    Route::middleware('role:staff-gudang,admin,super-admin')->group(function () {
        Route::get('/items', [ItemController::class, 'index'])->name('items.index');
        Route::get('/items/{item}', [ItemController::class, 'show'])
            ->whereUuid('item')
            ->name('items.show');

        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

        Route::get('/warehouse-orders', [WarehouseOrderController::class, 'index'])->name('warehouse-orders.index');
        Route::get('/warehouse-orders/{warehouse_order}', [WarehouseOrderController::class, 'show'])->name('warehouse-orders.show');
        Route::post('/warehouse-orders/{warehouse_order}/process', [WarehouseOrderController::class, 'process'])->name('warehouse-orders.process');
    });

    // Admin & Super Admin
    Route::middleware('role:admin,super-admin')->group(function () {
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::post('/categories/import', [\App\Http\Controllers\Admin\DataImportController::class, 'categories'])->name('categories.import');
        Route::get('/categories/import-template', [\App\Http\Controllers\Admin\DataImportController::class, 'categoriesTemplate'])->name('categories.import-template');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])
            ->whereUuid('category')
            ->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])
            ->whereUuid('category')
            ->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])
            ->whereUuid('category')
            ->name('categories.destroy');

        Route::get('/items/export', [InventoryExportController::class, 'items'])->name('items.export');
        Route::post('/items/import', [\App\Http\Controllers\Admin\DataImportController::class, 'items'])->name('items.import');
        Route::get('/items/import-template', [\App\Http\Controllers\Admin\DataImportController::class, 'itemsTemplate'])->name('items.import-template');
        Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
        Route::post('/items', [ItemController::class, 'store'])->name('items.store');
        Route::get('/items/{item}/edit', [ItemController::class, 'edit'])
            ->whereUuid('item')
            ->name('items.edit');
        Route::put('/items/{item}', [ItemController::class, 'update'])
            ->whereUuid('item')
            ->name('items.update');
        Route::delete('/items/{item}', [ItemController::class, 'destroy'])
            ->whereUuid('item')
            ->name('items.destroy');


        Route::get('/mutations', [StockMutationController::class, 'index'])->name('mutations.index');
        Route::get('/mutations/export', [InventoryExportController::class, 'mutations'])
            ->name('mutations.export');
        Route::get('/mutations/{mutation}', [StockMutationController::class, 'show'])
            ->whereUuid('mutation')
            ->name('mutations.show');
    });

    // ERP Routes - Customer Management (Admin & Super Admin)
    Route::middleware('role:admin,super-admin')->group(function () {
        Route::get('/customers/export', [InventoryExportController::class, 'customers'])->name('customers.export');
        Route::resource('customers', CustomerController::class);
    });

    // ERP Routes - Sales (Sales & Admin & Super Admin)
    Route::middleware('role:sales,admin,super-admin')->group(function () {
        Route::get('/sales-visits/export', [InventoryExportController::class, 'salesVisits'])->name('sales-visits.export');
        Route::resource('sales-visits', SalesVisitController::class);

        Route::get('/invoices/export', [InventoryExportController::class, 'invoices'])->name('invoices.export');
        Route::resource('invoices', InvoiceController::class);
    });

    // ERP Routes - Finance (Admin & Super Admin)
    Route::middleware('role:admin,super-admin')->group(function () {
        Route::get('/payments/export', [InventoryExportController::class, 'payments'])->name('payments.export');
        Route::resource('payments', PaymentController::class, ['only' => ['index', 'create', 'store', 'show', 'destroy']]);

        Route::get('/installment-plans/export', [InventoryExportController::class, 'installments'])->name('installment-plans.export');
        Route::resource('installment-plans', InstallmentPlanController::class, ['only' => ['index', 'show', 'update']]);
    });

    // Super Admin only
    Route::middleware('role:super-admin')->group(function () {
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])
            ->whereUuid('user')
            ->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])
            ->whereUuid('user')
            ->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])
            ->whereUuid('user')
            ->name('users.destroy');
    });
});
