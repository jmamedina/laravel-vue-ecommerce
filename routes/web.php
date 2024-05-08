<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['guestOrVerified'])->group(function () {
    // Home route
    // ホームルート
    Route::get('/', [ProductController::class, 'index'])->name('home');
    // Category route
    // カテゴリールート
    Route::get('/category/{category:slug}', [ProductController::class, 'byCategory'])->name('byCategory');
    // Product view route
    // 製品表示ルート
    Route::get('/product/{product:slug}', [ProductController::class, 'view'])->name('product.view');

    Route::prefix('/cart')->name('cart.')->group(function () {
        // Cart index route
        // カートインデックスルート
        Route::get('/', [CartController::class, 'index'])->name('index');
        // Add to cart route
        // カートに追加するルート
        Route::post('/add/{product:slug}', [CartController::class, 'add'])->name('add');
        // Remove from cart route
        // カートから削除するルート
        Route::post('/remove/{product:slug}', [CartController::class, 'remove'])->name('remove');
        // Update quantity in cart route
        // カート内の数量を更新するルート
        Route::post('/update-quantity/{product:slug}', [CartController::class, 'updateQuantity'])->name('update-quantity');
    });
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Profile view route
    // プロフィール表示ルート
    Route::get('/profile', [ProfileController::class, 'view'])->name('profile');
    // Profile update route
    // プロフィール更新ルート
    Route::post('/profile', [ProfileController::class, 'store'])->name('profile.update');
    // Password update route
    // パスワード更新ルート
    Route::post('/profile/password-update', [ProfileController::class, 'passwordUpdate'])->name('profile_password.update');
    // Checkout route
    // チェックアウトルート
    Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('cart.checkout');
    // Checkout order route
    // 注文チェックアウトルート
    Route::post('/checkout/{order}', [CheckoutController::class, 'checkoutOrder'])->name('cart.checkout-order');
    // Checkout success route
    // チェックアウト成功ルート
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    // Checkout failure route
    // チェックアウト失敗ルート
    Route::get('/checkout/failure', [CheckoutController::class, 'failure'])->name('checkout.failure');
    // Orders index route
    // 注文一覧ルート
    Route::get('/orders', [OrderController::class, 'index'])->name('order.index');
    // Order view route
    // 注文表示ルート
    Route::get('/orders/{order}', [OrderController::class, 'view'])->name('order.view');
});

// Stripe webhook route
// Stripeウェブフックルート
Route::post('/webhook/stripe', [CheckoutController::class, 'webhook']);

require __DIR__ . '/auth.php';
