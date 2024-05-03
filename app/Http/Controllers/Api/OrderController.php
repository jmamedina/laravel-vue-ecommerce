<?php

/**
 * OrderController
 * 注文コントローラー
 */

namespace App\Http\Controllers\Api;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderListResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ProductListResource;
use App\Mail\OrderUpdateEmail;
use App\Models\Api\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     * リソースの一覧を表示する。
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Set pagination parameters
        // ページネーションのパラメーターを設定
        $perPage = request('per_page', 10);
        $search = request('search', '');
        $sortField = request('sort_field', 'updated_at');
        $sortDirection = request('sort_direction', 'desc');

        // Query orders with counts of items and related user/customer
        // アイテムの数と関連するユーザー/顧客を持つ注文をクエリ
        $query = Order::query()
            ->withCount('items')
            ->with('user.customer')
            ->where('id', 'like', "%{$search}%")
            ->orderBy($sortField, $sortDirection)
            ->paginate($perPage);

        return OrderListResource::collection($query);
    }

    /**
     * View a specific order.
     * 特定の注文を表示する。
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function view(Order $order)
    {
        // Load order items with their related products
        // 関連する商品を含めて注文アイテムをロードする
        $order->load('items.product');

        return new OrderResource($order);
    }

    /**
     * Get available order statuses.
     * 利用可能な注文ステータスを取得する。
     *
     * @return array
     */
    public function getStatuses()
    {
        return OrderStatus::getStatuses();
    }

    /**
     * Change the status of an order.
     * 注文のステータスを変更する。
     *
     * @param  \App\Models\Order  $order
     * @param  string  $status
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Order $order, $status)
    {
        // Start database transaction
        // データベーストランザクションを開始する
        DB::beginTransaction();
        try {
            // Update order status
            // 注文ステータスを更新する
            $order->status = $status;
            $order->save();

            // If order is cancelled, restore product quantities
            // 注文がキャンセルされた場合、商品の数量を復元する
            if ($status === OrderStatus::Cancelled->value) {
                foreach ($order->items as $item) {
                    $product = $item->product;
                    if ($product && $product->quantity !== null) {
                        $product->quantity += $item->quantity;
                        $product->save();
                    }
                }
            }

            // Send order update email to user
            // ユーザーに注文更新のメールを送信する
            Mail::to($order->user)->send(new OrderUpdateEmail($order));
        } catch (\Exception $e) {
            // Rollback transaction on exception
            // 例外が発生した場合はトランザクションをロールバックする
            DB::rollBack();
            throw $e;
        }

        // Commit transaction
        // トランザクションをコミットする
        DB::commit();

        return response('', 200);
    }
}
