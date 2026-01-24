<?php

namespace App\Enums;

enum TransactionStatusEnum: string
{
    case Success = 'Success';
    case Cancelled = 'Cancelled';
    case Pending = 'Pending';
}
