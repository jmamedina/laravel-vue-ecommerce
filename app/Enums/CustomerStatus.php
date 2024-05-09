<?php

namespace App\Enums;

enum CustomerStatus: string
{
        // Active customer status
        // アクティブな顧客ステータス
    case Active = 'active';

        // Disabled customer status
        // 無効な顧客ステータス
    case Disabled = 'disabled';
}
