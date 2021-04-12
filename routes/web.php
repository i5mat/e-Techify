<?php

use Illuminate\Support\Facades\Route;
use Admin\UserController;
use User\Profile;
use Product\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});

// User related pages
Route::prefix('user')->middleware(['auth', 'verified'])->name('user.')->group(function () {
    Route::get('profile', Profile::class)->name('profile');
    Route::get('userdash', [App\Http\Controllers\User\Profile::class, 'userDash'])->name('userdash');
    Route::get('address', [App\Http\Controllers\User\Profile::class, 'userAddressIndex'])->name('useraddress');
    Route::post('addUserAddress', [App\Http\Controllers\User\Profile::class, 'addAddress'])->name('userinsertaddress');
    Route::delete('delAddress/{id}', [App\Http\Controllers\User\Profile::class, 'destroyAddress'])->name('userdel_address');

});

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'auth.isReseller', 'verified'])->name('admin.')->group(function () {
    Route::resource('/users', UserController::class);
});

// Product non-resource related pages
Route::prefix('product')->middleware(['auth', 'verified'])->name('product.')->group(function () {
    Route::get('insertProduct', ProductController::class)->name('insertProd');
    Route::get('manageProduct', [App\Http\Controllers\Product\ProductController::class, 'manageProductIndex'])->name('manageProd');
    Route::get('cart', [App\Http\Controllers\Product\ProductController::class, 'manageCartIndex'])->name('manageCart');
    Route::post('addProductCart/{id}', [App\Http\Controllers\Product\ProductController::class, 'addToCart'])->name('addProdCart');
});

// Product related pages
Route::prefix('product')->middleware(['auth', 'verified'])->name('product.')->group(function () {
    Route::resource('/items', ProductController::class);
});
