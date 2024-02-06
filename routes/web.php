<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\User\CategoriesController;
use App\Http\Controllers\User\CompanySettingController;
use App\Http\Controllers\User\OnboardingController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\PosController;
use App\Http\Controllers\User\ProductController;
use App\Http\Controllers\User\ProfileController;
use Illuminate\Support\Facades\Route;

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

Route::get('/',  [AuthenticatedSessionController::class, 'create']);
Route::get('/self_order', [OrderController::class, 'selfOrder'])->name('order.selfOrder');
Route::post('/self_order', [OrderController::class, 'storeSelfOrder'])->name('order.store.selfOrder');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [ProfileController::class, 'dashboard'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('/pos', PosController::class)->only(['index']);
    Route::resource('/product', ProductController::class)->only(['store', 'update', 'destroy']);
    Route::resource('/category', CategoriesController::class)->only(['store', 'show', 'update', 'destroy']);

    Route::post('/company/add-table', [CompanySettingController::class, 'addTable'])->name('company.add-table');
    Route::put('/company/update-table/{table}', [CompanySettingController::class, 'updateTable'])->name('company.update-table');
    Route::post('/company/remove-logo', [CompanySettingController::class, 'removeLogo'])->name('company.remove-logo');
    Route::resource('/company', CompanySettingController::class)->only(['index', 'store']);

    Route::get('/order/print', [OrderController::class, 'printOrder'])->name('print.order');
    Route::get('/receipt/print/{order}', [OrderController::class, 'printReceipt'])->name('print.receipt');
    Route::get('/order-receipt/print/{order}', [OrderController::class, 'printOrderReceipt'])->name('print.order-receipt');
    Route::post('/order/pay/{order}', [OrderController::class, 'payOrder'])->name('order.pay');
    Route::post('/order/add/{order}', [OrderController::class, 'addOrder'])->name('order.addOrder');
    Route::resource('/order', OrderController::class)->only(['index', 'store', 'show', 'update']);
});

require __DIR__.'/auth.php';
