<?php

namespace App\Enums;

enum PaymentStatus: string
{
        // Payment status: Pending
        // 支払いステータス: 保留中
    case Pending = 'pending';

        // Payment status: Paid
        // 支払いステータス: 支払済み
    case Paid = 'paid';

        // Payment status: Failed
        // 支払いステータス: 失敗
    case Failed = 'failed';
}
