<?php

use Order\OrderController;
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

// Order related pages
Route::prefix('order')->middleware(['auth', 'verified'])->name('order.')->group(function () {
    Route::resource('/orders', OrderController::class);
});

// Order non-resource related pages
Route::prefix('order')->middleware(['auth', 'verified'])->name('order.')->group(function () {
    Route::get('purchase/{id}', [App\Http\Controllers\Order\OrderController::class, 'orderDetailsIndex'])->name('index.orderdetails');
    Route::post('purchase/success/{id}', [App\Http\Controllers\Order\OrderController::class, 'orderConfirm'])->name('purchase.orderdetails');
    Route::get('purchase/success/thank-you', [App\Http\Controllers\Order\OrderController::class, 'thankYouIndex'])->name('purchase.thanks');
    Route::post('purchase/awb/{id}', [App\Http\Controllers\Order\OrderController::class, 'airwayBill'])->name('purchase.awb');
    Route::get('receipt/{id}', [App\Http\Controllers\Order\OrderController::class, 'receiptIndex'])->name('purchase.receipt');
    Route::get('receipt/insert-prod-sn/{id}', [App\Http\Controllers\Order\OrderController::class, 'addProductSN'])->name('purchase.insertsn');
    Route::post('receipt/update-prod-sn/{id}', [App\Http\Controllers\Order\OrderController::class, 'updateProductSN'])->name('purchase.updatesn');
    Route::delete('purchase/delete/{id}', [App\Http\Controllers\Order\OrderController::class, 'cancelOrder'])->name('order.cancel');
});

Route::prefix('tracking')->middleware(['auth', 'verified'])->name('track.')->group(function () {
    Route::get('track-parcel/{id}', [App\Http\Controllers\TrackingController::class, 'trackingIndex'])->name('index.trackparcel');
    Route::post('track-parcel/insert/{id}', [App\Http\Controllers\TrackingController::class, 'insertTracking'])->name('insert.trackparcel');
    Route::get('parcel', [App\Http\Controllers\TrackingController::class, 'trackOutParcelIndex'])->name('new.parcel');
    Route::post('parcel/success', [App\Http\Controllers\TrackingController::class, 'trackOutParcel'])->name('parcel');
});

Route::prefix('distributor')->middleware(['auth', 'verified'])->name('distributor.')->group(function () {
    Route::get('insert-product', [App\Http\Controllers\Product\ProductController::class, 'distriInsertProductIndex'])->name('insert.product.dist');
    Route::post('insert-sn-product', [App\Http\Controllers\Product\ProductController::class, 'distriInsertSN'])->name('insertsn.product.dist');
});

Route::prefix('rma')->middleware(['auth', 'verified'])->name('rma.')->group(function () {
    Route::get('new-request', [App\Http\Controllers\RepairController::class, 'RMAIndex'])->name('new.request');
    Route::post('new-request-post', [App\Http\Controllers\RepairController::class, 'storeRMA'])->name('new.post.request');
});

Route::prefix('job')->middleware(['auth', 'verified'])->name('job.')->group(function () {
    Route::get('new-job', [App\Http\Controllers\JobController::class, 'jobIndex'])->name('new');
    Route::post('new-job-request', [App\Http\Controllers\JobController::class, 'jobInsert'])->name('request.new');
});
