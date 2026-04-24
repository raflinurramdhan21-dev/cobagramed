<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SnapTokenController;


// Route Product 
Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Route Order 
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::get('/orders/{order:invoice_number}', [OrderController::class, 'show'])->name('orders.show');

// Midtrans Snap Token
Route::post('/orders/{order}/snap-token', [SnapTokenController::class, 'generateToken'])->name('snap.token');
