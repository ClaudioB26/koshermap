<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;

// Cambia la ruta '/' que estaba antes por esta:
Route::get('/', [SearchController::class, 'index'])->name('home');
// Ruta para ver un producto individual
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('products.show');


