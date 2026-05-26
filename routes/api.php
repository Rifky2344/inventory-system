<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\StockMutationController;
use App\Http\Controllers\Api\StockTransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return ['status' => 'ok'];
});

Route::post('/login', [AuthController::class, 'login'])->name('api.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me'])->name('api.me');
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

    Route::middleware('role:staff-gudang,admin,super-admin')->group(function () {
        // Read
        Route::get('/categories', [CategoryController::class, 'index'])->name('api.categories.index');
        Route::get('/categories/{category}', [CategoryController::class, 'show'])
            ->whereUuid('category')
            ->name('api.categories.show');

        Route::get('/items', [ItemController::class, 'index'])->name('api.items.index');
        Route::get('/items/{item}', [ItemController::class, 'show'])
            ->whereUuid('item')
            ->name('api.items.show');

        Route::get('/transactions', [StockTransactionController::class, 'index'])->name('api.transactions.index');
        Route::get('/transactions/{transaction}', [StockTransactionController::class, 'show'])
            ->whereUuid('transaction')
            ->name('api.transactions.show');

        // Input transaksi
        Route::post('/transactions/in', [StockTransactionController::class, 'storeIn'])->name('api.transactions.in.store');
        Route::post('/transactions/out', [StockTransactionController::class, 'storeOut'])->name('api.transactions.out.store');
    });

    Route::middleware('role:admin,super-admin')->group(function () {
        // Write
        Route::post('/categories', [CategoryController::class, 'store'])->name('api.categories.store');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])
            ->whereUuid('category')
            ->name('api.categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])
            ->whereUuid('category')
            ->name('api.categories.destroy');

        Route::post('/items', [ItemController::class, 'store'])->name('api.items.store');
        Route::put('/items/{item}', [ItemController::class, 'update'])
            ->whereUuid('item')
            ->name('api.items.update');
        Route::delete('/items/{item}', [ItemController::class, 'destroy'])
            ->whereUuid('item')
            ->name('api.items.destroy');

        Route::put('/transactions/{transaction}', [StockTransactionController::class, 'update'])
            ->whereUuid('transaction')
            ->name('api.transactions.update');
        Route::delete('/transactions/{transaction}', [StockTransactionController::class, 'destroy'])
            ->whereUuid('transaction')
            ->name('api.transactions.destroy');

        // Mutations
        Route::get('/mutations', [StockMutationController::class, 'index'])->name('api.mutations.index');
        Route::get('/mutations/{mutation}', [StockMutationController::class, 'show'])
            ->whereUuid('mutation')
            ->name('api.mutations.show');
    });
});
