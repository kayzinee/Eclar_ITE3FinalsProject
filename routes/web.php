<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Laravel Breeze Authentication Routes
require __DIR__.'/auth.php';

// Public PDF exports
Route::get('/dashboard/export-pdf', [DashboardController::class, 'exportPdf'])->name('dashboard.export.pdf');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    
    // Dashboard (Products Page)
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard')
        ->middleware('auth');
    
    // Product Management
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('{product}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::post('{product}/update', [ProductController::class, 'update'])->name('update');
        Route::post('{product}/delete', [ProductController::class, 'destroy'])->name('destroy');
        Route::put('/{product}', [ProductController::class, 'update'])->name('update.rest');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy.rest');

        // Trash management routes
        Route::get('/trash/list', [ProductController::class, 'trash'])->name('trash');
        Route::put('{id}/restore', [ProductController::class, 'restore'])->name('restore');
        Route::delete('{id}/force', [ProductController::class, 'forceDelete'])->name('force-delete');
        // PDF export route
        Route::get('/export/pdf', [ProductController::class, 'exportPdf'])->name('export.pdf');
    });
    
    // Category Management
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::post('{category}/update', [CategoryController::class, 'update'])->name('update');
        Route::post('{category}/delete', [CategoryController::class, 'destroy'])->name('destroy');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update.rest');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy.rest');
        // Trash management routes
        Route::get('/trash/list', [CategoryController::class, 'trash'])->name('trash');
        Route::put('{id}/restore', [CategoryController::class, 'restore'])->name('restore');
        Route::delete('{id}/force', [CategoryController::class, 'forceDelete'])->name('force-delete');
        // PDF export route
        Route::get('/export/pdf', [CategoryController::class, 'exportPdf'])->name('export.pdf');
    });
});