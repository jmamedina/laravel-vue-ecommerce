<?php

namespace App\Enums;

enum OrderStatus: string
{
        // Order status: Unpaid
        // 注文ステータス: 未払い
    case Unpaid = 'unpaid';

        // Order status: Paid
        // 注文ステータス: 支払済み
    case Paid = 'paid';

        // Order status: Cancelled
        // 注文ステータス: キャンセル済み
    case Cancelled = 'cancelled';

        // Order status: Shipped
        // 注文ステータス: 発送済み
    case Shipped = 'shipped';

        // Order status: Completed
        // 注文ステータス: 完了済み
    case Completed = 'completed';

    // Get all order statuses
    // すべての注文ステータスを取得する
    public static function getStatuses()
    {
        return [
            self::Paid, self::Unpaid, self::Cancelled, self::Shipped, self::Completed
        ];
    }
}
