<?php

namespace App\Http\Controllers\Api;

use App\Enums\AddressType;
use App\Enums\CustomerStatus;
use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\OrderResource;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Traits\ReportTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Controller for managing dashboard-related API endpoints
// ダッシュボード関連のAPIエンドポイントを管理するためのコントローラー
class DashboardController extends Controller
{
    use ReportTrait;

    /**
     * Get the count of active customers.
     * アクティブな顧客の数を取得します。
     *
     * @return int
     */
    public function activeCustomers()
    {
        // Retrieve the count of active customers
        // アクティブな顧客の数を取得します
        return Customer::where('status', CustomerStatus::Active->value)->count();
    }

    /**
     * Get the count of active products.
     * アクティブな製品の数を取得します。
     *
     * @return int
     */
    public function activeProducts()
    {
        // Retrieve the count of active products
        // アクティブな製品の数を取得します
        return Product::where('published', '=', 1)->count();
    }

    /**
     * Get the count of paid orders.
     * 支払い済み注文の数を取得します。
     *
     * @return int
     */
    public function paidOrders()
    {
        // Retrieve the count of paid orders based on a specified date
        // 指定された日付に基づいて支払い済み注文の数を取得します
        $fromDate = $this->getFromDate();
        $query = Order::query()->where('status', OrderStatus::Paid->value);

        if ($fromDate) {
            $query->where('created_at', '>', $fromDate);
        }

        return $query->count();
    }

    /**
     * Get the total income from paid orders.
     * 支払い済み注文からの総収入を取得します。
     *
     * @return float
     */
    public function totalIncome()
    {
        // Retrieve the total income from paid orders based on a specified date
        // 指定された日付に基づいて支払い済み注文からの総収入を取得します
        $fromDate = $this->getFromDate();
        $query = Order::query()->where('status', OrderStatus::Paid->value);

        if ($fromDate) {
            $query->where('created_at', '>', $fromDate);
        }
        return round($query->sum('total_price'));
    }

    /**
     * Get the count of orders by country.
     * 国別の注文数を取得します。
     *
     * @return \Illuminate\Support\Collection
     */
    public function ordersByCountry()
    {
        // Retrieve the count of orders grouped by country based on a specified date
        // 指定された日付に基づいて国別にグループ化された注文数を取得します
        $fromDate = $this->getFromDate();
        $query = Order::query()
            ->select(['c.name', DB::raw('count(orders.id) as count')])
            ->join('users', 'created_by', '=', 'users.id')
            ->join('customer_addresses AS a', 'users.id', '=', 'a.customer_id')
            ->join('countries AS c', 'a.country_code', '=', 'c.code')
            ->where('status', OrderStatus::Paid->value)
            ->where('a.type', AddressType::Billing->value)
            ->groupBy('c.name');

        if ($fromDate) {
            $query->where('orders.created_at', '>', $fromDate);
        }

        return $query->get();
    }

    /**
     * Get the latest active customers.
     * 最新のアクティブな顧客を取得します。
     *
     * @return \Illuminate\Support\Collection
     */
    public function latestCustomers()
    {
        // Retrieve the latest active customers
        // 最新のアクティブな顧客を取得します
        return Customer::query()
            ->select(['id', 'first_name', 'last_name', 'u.email', 'phone', 'u.created_at'])
            ->join('users AS u', 'u.id', '=', 'customers.user_id')
            ->where('status', CustomerStatus::Active->value)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Get the latest paid orders.
     * 最新の支払い済み注文を取得します。
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function latestOrders()
    {
        // Retrieve the latest paid orders along with customer information
        // 最新の支払い済み注文と顧客情報を取得します
        return OrderResource::collection(
            Order::query()
                ->select(['o.id', 'o.total_price', 'o.created_at', DB::raw('COUNT(oi.id) AS items'),
                    'c.user_id', 'c.first_name', 'c.last_name'])
                ->from('orders AS o')
                ->join('order_items AS oi', 'oi.order_id', '=', 'o.id')
                ->join('customers AS c', 'c.user_id', '=', 'o.created_by')
                ->where('o.status', OrderStatus::Paid->value)
                ->limit(10)
                ->orderBy('o.created_at', 'desc')
                ->groupBy('o.id', 'o.total_price', 'o.created_at', 'c.user_id', 'c.first_name', 'c.last_name')
                ->get()
        );
    }
}
