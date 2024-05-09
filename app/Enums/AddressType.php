<?php

namespace App\Enums;

enum AddressType: string
{
        // Shipping address type
        // 配送先住所タイプ
    case Shipping = 'shipping';

        // Billing address type
        // 請求先住所タイプ
    case Billing = 'billing';
}
