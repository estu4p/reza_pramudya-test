<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ImportController;
use App\Http\Controllers\LayerController;
use App\Http\Controllers\LayupController;
use App\Http\Controllers\SupplierController;
use App\Models\Supplier;
use Illuminate\Support\Facades\Route;

// Supplier CRUD
Route::apiResource('suppliers', SupplierController::class);

// Layup (nested under Supplier)
Route::apiResource('suppliers.layups', LayupController::class);

// Layer (nested under Layup)
Route::apiResource('layups.layers', LayerController::class);

// Import / Export
Route::post('suppliers/{supplier}/import', [ImportController::class, 'import']);
Route::get('suppliers/{supplier}/export', [SupplierController::class, 'export']);
