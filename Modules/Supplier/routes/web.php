<?php

use Illuminate\Support\Facades\Route;
use Modules\Supplier\Http\Controllers\SupplierController;

Route::middleware(['web', 'auth'])->group(function () {
    Route::post('suppliers/import', [SupplierController::class, 'importSuppliers'])->name('suppliers.import');

    Route::get('suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
    Route::post('suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('suppliers/{id}', [SupplierController::class, 'show'])->name('suppliers.show');
    Route::get('suppliers/{id}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::put('suppliers/{id}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('suppliers/{id}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
});
