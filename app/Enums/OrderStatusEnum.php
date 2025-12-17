<?php

namespace App\Enums;

use App\Enums\Traits\Values;

enum OrderStatusEnum: string
{
    use Values;

    case InProcess = 'In Process';
    case Completed = 'Completed';
    case Cancelled = 'Cancelled';
    case Paid = 'Paid';
}
