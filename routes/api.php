<?php

use App\Http\Controllers\Api\SyncController;
use Illuminate\Support\Facades\Route;

Route::middleware(\App\Http\Middleware\VerifySyncKey::class)->group(function () {

    // Orden importa: dependencias primero
    Route::post('/sync/countries',   [SyncController::class, 'countries']);
    Route::post('/sync/certifiers',  [SyncController::class, 'certifiers']);
    Route::post('/sync/categories',  [SyncController::class, 'categories']);
    Route::post('/sync/brands',      [SyncController::class, 'brands']);
    Route::post('/sync/products',    [SyncController::class, 'products']);
    Route::post('/sync/cities',      [SyncController::class, 'cities']);
    Route::post('/sync/places',      [SyncController::class, 'places']);
});
