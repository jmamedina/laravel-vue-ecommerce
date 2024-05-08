<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\ReportController;
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

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    // 認証API
    // Authentication API
    Route::get('/user', [\App\Http\Controllers\Api\AuthController::class, 'getUser']);
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);

    // 商品
    // Products
    Route::apiResource('products', ProductController::class);

    // ユーザー
    // Users
    Route::apiResource('users', UserController::class);

    // 顧客
    // Customers
    Route::apiResource('customers', CustomerController::class);

    // カテゴリー
    // Categories
    Route::apiResource('categories', CategoryController::class)->except('show');
    Route::get('/categories/tree', [CategoryController::class, 'getAsTree']);

    // 国
    // Countries
    Route::get('/countries', [CustomerController::class, 'countries']);

    // 注文
    // Orders
    Route::get('orders', [OrderController::class, 'index']);
    Route::get('orders/statuses', [OrderController::class, 'getStatuses']);
    Route::post('orders/change-status/{order}/{status}', [OrderController::class, 'changeStatus']);
    Route::get('orders/{order}', [OrderController::class, 'view']);

    // ダッシュボードルート
    // Dashboard Routes
    // ダッシュボード - 顧客数
    // Dashboard - Customer Count
    Route::get('/dashboard/customers-count', [DashboardController::class, 'activeCustomers']);

    // ダッシュボード - 商品数
    // Dashboard - Products Count
    Route::get('/dashboard/products-count', [DashboardController::class, 'activeProducts']);

    // ダッシュボード - 注文数
    // Dashboard - Orders Count
    Route::get('/dashboard/orders-count', [DashboardController::class, 'paidOrders']);

    // ダッシュボード - 収益額
    // Dashboard - Income Amount
    Route::get('/dashboard/income-amount', [DashboardController::class, 'totalIncome']);

    // ダッシュボード - 国別注文数
    // Dashboard - Orders by Country
    Route::get('/dashboard/orders-by-country', [DashboardController::class, 'ordersByCountry']);

    // ダッシュボード - 最新顧客数
    // Dashboard - Latest Customers
    Route::get('/dashboard/latest-customers', [DashboardController::class, 'latestCustomers']);

    // ダッシュボード - 最新注文数
    // Dashboard - Latest Orders
    Route::get('/dashboard/latest-orders', [DashboardController::class, 'latestOrders']);

    // レポートルート
    // Report Routes
    // レポート - 注文
    // Report - Orders
    Route::get('/report/orders', [ReportController::class, 'orders']);

    // レポート - 顧客
    // Report - Customers
    Route::get('/report/customers', [ReportController::class, 'customers']);
});

// ログインエンドポイント
// Login Endpoint
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
