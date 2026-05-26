<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StockMutationController;
use App\Http\Controllers\StockTransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    $role = auth()->user()->role?->slug;

    return in_array($role, ['admin', 'super-admin'], true)
        ? redirect()->route('dashboard')
        : redirect()->route('transactions.index');
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

    Route::get('/dashboard', DashboardController::class)
        ->middleware('role:super-admin,admin')
        ->name('dashboard');

    // Staff Gudang (read-only + input transaksi)
    Route::middleware('role:staff-gudang,admin,super-admin')->group(function () {
        Route::get('/items', [ItemController::class, 'index'])->name('items.index');
        Route::get('/items/{item}', [ItemController::class, 'show'])
            ->whereUuid('item')
            ->name('items.show');

        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

        Route::get('/transactions', [StockTransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/{transaction}', [StockTransactionController::class, 'show'])
            ->whereUuid('transaction')
            ->name('transactions.show');
        Route::get('/transactions/in/create', [StockTransactionController::class, 'createIn'])->name('transactions.in.create');
        Route::post('/transactions/in', [StockTransactionController::class, 'storeIn'])->name('transactions.in.store');
        Route::get('/transactions/out/create', [StockTransactionController::class, 'createOut'])->name('transactions.out.create');
        Route::post('/transactions/out', [StockTransactionController::class, 'storeOut'])->name('transactions.out.store');
    });

    // Admin & Super Admin
    Route::middleware('role:admin,super-admin')->group(function () {
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])
            ->whereUuid('category')
            ->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])
            ->whereUuid('category')
            ->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])
            ->whereUuid('category')
            ->name('categories.destroy');

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

        Route::get('/transactions/{transaction}/edit', [StockTransactionController::class, 'edit'])
            ->whereUuid('transaction')
            ->name('transactions.edit');
        Route::get('/transactions/export', [StockTransactionController::class, 'export'])
            ->name('transactions.export');
        Route::put('/transactions/{transaction}', [StockTransactionController::class, 'update'])
            ->whereUuid('transaction')
            ->name('transactions.update');
        Route::delete('/transactions/{transaction}', [StockTransactionController::class, 'destroy'])
            ->whereUuid('transaction')
            ->name('transactions.destroy');

        Route::get('/mutations', [StockMutationController::class, 'index'])->name('mutations.index');
        Route::get('/mutations/export', [StockMutationController::class, 'export'])
            ->name('mutations.export');
        Route::get('/mutations/{mutation}', [StockMutationController::class, 'show'])
            ->whereUuid('mutation')
            ->name('mutations.show');
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
