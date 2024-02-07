<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShoppingCartController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [ShoppingCartController::class, 'showLandingPage'])->name('landing');
Route::get('/cart', [ShoppingCartController::class, 'showCart'])->name('cart');
Route::put('/cart/{productId}', [ShoppingCartController::class, 'updateQuantity'])->name('cart.update');
Route::delete('/cart/{productId}', [ShoppingCartController::class, 'removeProduct'])->name('cart.remove');
Route::post('/add-to-cart/{productId}', [ShoppingCartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/apply-discount', [ShoppingCartController::class, 'applyDiscount'])->name('cart.applyDiscount');
Route::post('/cart/remove-discount', [ShoppingCartController::class, 'removeDiscount'])->name('cart.removeDiscount');
