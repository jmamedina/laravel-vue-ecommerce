<?php

/**
 * Controller responsible for handling order-related operations.
 * 注文関連の操作を処理するコントローラーです。
 */

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Display a paginated list of orders
    // 注文の一覧をページネーションして表示する
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        // Get the authenticated user
        // 認証されたユーザーを取得する
        $user = $request->user();

        // Fetch orders with item counts, ordered by creation date
        // アイテムの数を含む注文を作成日で並べ替えて取得する
        $orders = Order::withCount('items')
            ->where(['created_by' => $user->id])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('order.index', compact('orders'));
    }

    // View a specific order
    // 特定の注文を表示する
    public function view(Order $order)
    {
        /** @var \App\Models\User $user */
        // Get the authenticated user
        // 認証されたユーザーを取得する
        $user = \request()->user();
        
        // Check if the user has permission to view the order
        // ユーザーが注文を表示する権限を持っているかどうかをチェックする
        if ($order->created_by !== $user->id) {
            return response("You don't have permission to view this order", 403);
        }

        return view('order.view', compact('order'));
    }
}
