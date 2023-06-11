<?php


use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [AuthenticationController::class, 'register'])->name('register');
Route::post('/login', [AuthenticationController::class, 'login'])->name('login');

Route::middleware('auth:api')->group(function() {
    Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');

    Route::resource('/product', ProductController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::resource('/category', CategoriesController::class)->only(['index', 'store', 'show', 'update', 'destroy']);

    Route::get('/test', function(){
        $company = Auth::user()->company->first();
        return $company->id;
    });
});
