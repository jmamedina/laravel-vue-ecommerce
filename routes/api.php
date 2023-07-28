<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\UserController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum', 'admin')->group(function () {
    Route::get('/user', [AuthController::class, 'getUser'])->name('getUser');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::apiResource('/products', ProductController::class); //getproducts
    Route::apiResource('/users', UserController::class);
    Route::delete('/products/{id}/delete', [ProductController::class, 'destroy']); // delete product
    Route::get('/products/{id}/show', [ProductController::class, 'show']); // show product
    Route::put('/products/{id}/update', [ProductController::class, 'update']); // show product
    Route::get('/orders',[OrderController::class, 'index']);
    Route::get('/orders/statuses', [OrderController::class, 'getStatuses']);
    Route::get('/orders/{order}',[OrderController::class, 'view']);
    Route::post('orders/change-status/{order}/{status}', [OrderController::class, 'changeStatus']);

});

Route::post('/login', [AuthController::class, 'login'])->name('login');;

// ('/user', function (Request $request) {
//     return $request->user();
// });
