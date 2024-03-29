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
    Route::get('invalid-dashboard', [App\Http\Controllers\User\Profile::class, 'userDash'])->name('invalid');
    Route::get('address', [App\Http\Controllers\User\Profile::class, 'userAddressIndex'])->name('useraddress');
    Route::post('addUserAddress', [App\Http\Controllers\User\Profile::class, 'addAddress'])->name('userinsertaddress');
    Route::delete('delAddress/{id}', [App\Http\Controllers\User\Profile::class, 'destroyAddress'])->name('userdel_address');
    Route::patch('updateAddressStatus/{id}', [App\Http\Controllers\User\Profile::class, 'userAddressUpdate'])->name('update.status');
    Route::get('userdash/pagination', [App\Http\Controllers\User\Profile::class, 'fetch_data']);
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
    Route::get('addProductCart/{id}', [App\Http\Controllers\Product\ProductController::class, 'addToCart'])->name('addProdCart');
    Route::delete('del-item-cart/{id}', [App\Http\Controllers\Product\ProductController::class, 'delItemCart'])->name('del-cart');
    Route::get('manageProduct/pagination', [App\Http\Controllers\Product\ProductController::class, 'fetch_data']);
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
    Route::get('purchase/success/{id}', [App\Http\Controllers\Order\OrderController::class, 'orderConfirm'])->name('purchase.orderdetails');
    Route::get('success/thank-you', [App\Http\Controllers\Order\OrderController::class, 'thankYouIndex'])->name('purchase.thanks');
    Route::post('purchase/awb/{id}', [App\Http\Controllers\Order\OrderController::class, 'airwayBill'])->name('purchase.awb');
    Route::get('receipt/{id}', [App\Http\Controllers\Order\OrderController::class, 'receiptIndex'])->name('purchase.receipt');
    Route::get('insert-prod-sn/{id}', [App\Http\Controllers\Order\OrderController::class, 'addProductSN'])->name('purchase.insertsn');
    Route::post('update-prod-sn/{id}', [App\Http\Controllers\Order\OrderController::class, 'updateProductSN'])->name('purchase.updatesn');
    Route::patch('purchase/delete/{id}', [App\Http\Controllers\Order\OrderController::class, 'cancelOrder'])->name('order.cancel');
    Route::post('paypal-test', [App\Http\Controllers\Order\OrderController::class, 'payPalTest'])->name('paypal.test');
    Route::get('receipt-finder', [App\Http\Controllers\Order\OrderController::class, 'receiptFinder'])->name('find-receipt');
    Route::get('confirm-order-email/{id}', [App\Http\Controllers\Order\OrderController::class, 'sendEmail'])->name('email');
});

Route::prefix('tracking')->middleware(['auth', 'verified'])->name('track.')->group(function () {
    Route::get('track-parcel/{id}', [App\Http\Controllers\TrackingController::class, 'trackingIndex'])->name('index.trackparcel');
    Route::post('track-parcel/insert/{id}', [App\Http\Controllers\TrackingController::class, 'insertTracking'])->name('insert.trackparcel');
    Route::get('parcel', [App\Http\Controllers\TrackingController::class, 'trackOutParcelIndex'])->name('new.parcel');
    Route::post('parcel/success', [App\Http\Controllers\TrackingController::class, 'trackOutParcel'])->name('parcel');
    Route::get('track-parcel-google/{id}', [App\Http\Controllers\TrackingController::class, 'googleTrack']);
});

Route::prefix('distributor')->middleware(['auth', 'verified'])->name('distributor.')->group(function () {
    Route::get('insert-product', [App\Http\Controllers\Product\ProductController::class, 'distriInsertProductIndex'])->name('insert.product.dist');
    Route::post('insert-sn-product', [App\Http\Controllers\Product\ProductController::class, 'distriInsertSN'])->name('insertsn.product.dist');
    Route::get('stock-management', [App\Http\Controllers\Product\ProductController::class, 'stockManagementIndex'])->name('stock.management.index');
    Route::get('stock-management/{id}', [App\Http\Controllers\Product\ProductController::class, 'stockManagementView'])->name('specific.stock.view');
    Route::patch('product/update-status/{id}', [App\Http\Controllers\Product\ProductController::class, 'updateStatusSNProduct'])->name('update-status');
});

Route::prefix('rma')->middleware(['auth', 'verified'])->name('rma.')->group(function () {
    Route::get('new-request', [App\Http\Controllers\RepairController::class, 'RMAIndex'])->name('new.request');
    Route::post('new-request-post', [App\Http\Controllers\RepairController::class, 'storeRMA'])->name('new.post.request');
    Route::get('rma-job-sheet/{id}', [App\Http\Controllers\RepairController::class, 'jobSheet'])->name('job-sheet');
    Route::patch('rma-update/{id}', [App\Http\Controllers\RepairController::class, 'updateRMA'])->name('update');
});

Route::prefix('job')->middleware(['auth', 'verified'])->name('job.')->group(function () {
    Route::get('new-job', [App\Http\Controllers\JobController::class, 'jobIndex'])->name('new');
    Route::post('new-job-request', [App\Http\Controllers\JobController::class, 'jobInsert'])->name('request.new');
    Route::patch('update/{id}', [App\Http\Controllers\JobController::class, 'updateJob'])->name('update');
    Route::patch('update-job-info/{id}', [App\Http\Controllers\JobController::class, 'updateJobInfo'])->name('updateJI');
    Route::patch('update-job-status/{id}', [App\Http\Controllers\JobController::class, 'updateJobStatus'])->name('updateJS');
    Route::get('job-approved-email/{id}', [App\Http\Controllers\JobController::class, 'emailJob'])->name('email');
    Route::post('job-approved/{id}', [App\Http\Controllers\JobController::class, 'approveJob'])->name('approve');
    Route::post('job-declined/{id}', [App\Http\Controllers\JobController::class, 'declineJob'])->name('decline');
});

Route::prefix('shipment')->middleware(['auth', 'verified'])->name('shipment.')->group(function () {
    Route::get('new-shipment', [App\Http\Controllers\ShipmentController::class, 'shipmentIndex'])->name('new');
    Route::post('new-shipment-request/{id}', [App\Http\Controllers\ShipmentController::class, 'shipmentInsert'])->name('request.new');
    Route::patch('shipment-request/{id}', [App\Http\Controllers\ShipmentController::class, 'shipmentUpdate'])->name('request');
    Route::patch('shipment-request-approval-distributor/{id}', [App\Http\Controllers\ShipmentController::class, 'shipmentApprovalDistributor'])->name('request.approval');
    Route::get('shipment-details/{id}', [App\Http\Controllers\ShipmentController::class, 'shipmentDetailsIndex'])->name('details');
    Route::delete('remove-item-request/{id}', [App\Http\Controllers\ShipmentController::class, 'removeItem'])->name('remove.item');
    Route::get('shipment-sheet/{id}', [App\Http\Controllers\ShipmentController::class, 'shipmentSheet'])->name('sheet');
});

Route::prefix('covid')->name('covid.')->group(function () {
    Route::get('vaccine_stats', [App\Http\Controllers\User\Profile::class, 'covidIndex'])->name('index');
});
