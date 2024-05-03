<?php

/**
 * Controller for managing shopping cart operations.
 * ショッピングカート操作を管理するコントローラーです。
 */

namespace App\Http\Controllers;

use App\Helpers\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CartController extends Controller
{
    // Display the cart contents
    // カートの内容を表示する
    public function index()
    {
        // Get products and cart items
        // 商品とカートアイテムを取得する
        [$products, $cartItems] = Cart::getProductsAndCartItems();

        // Calculate total price
        // 合計金額を計算する
        $total = 0;
        foreach ($products as $product) {
            $total += $product->price * $cartItems[$product->id]['quantity'];
        }

        // Render the cart view with data
        // データを使用してカートビューをレンダリングする
        return view('cart.index', compact('cartItems', 'products', 'total'));
    }

    // Add a product to the cart
    // 商品をカートに追加する
    public function add(Request $request, Product $product)
    {
        // Get quantity from request
        // リクエストから数量を取得する
        $quantity = $request->post('quantity', 1);
        $user = $request->user(); // Get current user

        $totalQuantity = 0;

        // Check if user is logged in
        // ユーザーがログインしているかどうかを確認する
        if ($user) {
            // Check if the user already has the product in the cart
            // ユーザーが既にカートに商品を持っているかどうかを確認する
            $cartItem = CartItem::where(['user_id' => $user->id, 'product_id' => $product->id])->first();
            if ($cartItem) {
                $totalQuantity = $cartItem->quantity + $quantity;
            } else {
                $totalQuantity = $quantity;
            }
        } else {
            // If user is not logged in, manage cart items via cookies
            // ユーザーがログインしていない場合、クッキーを使用してカートアイテムを管理する
            $cartItems = json_decode($request->cookie('cart_items', '[]'), true);
            $productFound = false;
            foreach ($cartItems as &$item) {
                if ($item['product_id'] === $product->id) {
                    $totalQuantity = $item['quantity'] + $quantity;
                    $productFound = true;
                    break;
                }
            }
            if (!$productFound) {
                $totalQuantity = $quantity;
            }
        }

        // Check if product quantity is sufficient
        // 商品の数量が十分かどうかをチェックする
        if ($product->quantity !== null && $product->quantity < $totalQuantity) {
            return response([
                'message' => match ($product->quantity) {
                    0 => 'The product is out of stock',
                    1 => 'There is only one item left',
                    default => 'There are only ' . $product->quantity . ' items left'
                }
            ], 422);
        }

        // If user is logged in, update cart in database
        // ユーザーがログインしている場合、データベースのカートを更新する
        if ($user) {
            $cartItem = CartItem::where(['user_id' => $user->id, 'product_id' => $product->id])->first();

            if ($cartItem) {
                $cartItem->quantity += $quantity;
                $cartItem->update();
            } else {
                $data = [
                    'user_id' => $request->user()->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                ];
                CartItem::create($data);
            }

            // Return response with updated cart item count
            // 更新されたカートアイテム数を含むレスポンスを返す
            return response([
                'count' => Cart::getCartItemsCount()
            ]);
        } else {
            // If user is not logged in, update cart via cookies
            // ユーザーがログインしていない場合、クッキーを使用してカートを更新する
            $cartItems = json_decode($request->cookie('cart_items', '[]'), true);
            $productFound = false;
            foreach ($cartItems as &$item) {
                if ($item['product_id'] === $product->id) {
                    $item['quantity'] += $quantity;
                    $productFound = true;
                    break;
                }
            }
            if (!$productFound) {
                $cartItems[] = [
                    'user_id' => null,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price
                ];
            }
            Cookie::queue('cart_items', json_encode($cartItems), 60 * 24 * 30);

            // Return response with updated cart item count
            // 更新されたカートアイテム数を含むレスポンスを返す
            return response(['count' => Cart::getCountFromItems($cartItems)]);
        }
    }

    // Remove a product from the cart
    // カートから商品を削除する
    public function remove(Request $request, Product $product)
    {
        $user = $request->user();
        if ($user) {
            // Remove item from database cart
            // データベースのカートからアイテムを削除する
            $cartItem = CartItem::query()->where(['user_id' => $user->id, 'product_id' => $product->id])->first();
            if ($cartItem) {
                $cartItem->delete();
            }

            // Return response with updated cart item count
            // 更新されたカートアイテム数を含むレスポンスを返す
            return response([
                'count' => Cart::getCartItemsCount(),
            ]);
        } else {
            // Remove item from cookie cart
            // クッキーのカートからアイテムを削除する
            $cartItems = json_decode($request->cookie('cart_items', '[]'), true);
            foreach ($cartItems as $i => &$item) {
                if ($item['product_id'] === $product->id) {
                    array_splice($cartItems, $i, 1);
                    break;
                }
            }
            Cookie::queue('cart_items', json_encode($cartItems), 60 * 24 * 30);

            // Return response with updated cart item count
            // 更新されたカートアイテム数を含むレスポンスを返す
            return response(['count' => Cart::getCountFromItems($cartItems)]);
        }
    }

    // Update quantity of a product in the cart
    // カート内の商品の数量を更新する
    public function updateQuantity(Request $request, Product $product)
    {
        $quantity = (int)$request->post('quantity');
        $user = $request->user();

        // Check if product quantity is sufficient
        // 商品の数量が十分かどうかをチェックする
        if ($product->quantity !== null && $product->quantity < $quantity) {
            return response([
                'message' => match ($product->quantity) {
                    0 => 'The product is out of stock',
                    1 => 'There is only one item left',
                    default => 'There are only ' . $product->quantity . ' items left'
                }
            ], 422);
        }

        // If user is logged in, update cart in database
        // ユーザーがログインしている場合、データベースのカートを更新する
        if ($user) {
            CartItem::where(['user_id' => $request->user()->id, 'product_id' => $product->id])->update(['quantity' => $quantity]);

            // Return response with updated cart item count
            // 更新されたカートアイテム数を含むレスポンスを返す
            return response([
                'count' => Cart::getCartItemsCount(),
            ]);
        } else {
            // If user is not logged in, update cart via cookies
            // ユーザーがログインしていない場合、クッキーを使用してカートを更新する
            $cartItems = json_decode($request->cookie('cart_items', '[]'), true);
            foreach ($cartItems as &$item) {
                if ($item['product_id'] === $product->id) {
                    $item['quantity'] = $quantity;
                    break;
                }
            }
            Cookie::queue('cart_items', json_encode($cartItems), 60 * 24 * 30);

            // Return response with updated cart item count
            // 更新されたカートアイテム数を含むレスポンスを返す
            return response(['count' => Cart::getCountFromItems($cartItems)]);
        }
    }
}
