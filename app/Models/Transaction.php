<?php

namespace App\Models;

use App\Enums\PaymentSystemEnum;
use App\Enums\TransactionStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Transaction extends Model
{
    protected $quarded = [];

    protected $casts = [
        'status' => TransactionStatusEnum::class,
        'payment_system' => PaymentSystemEnum::class,
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): HasOneThrough
    {
        return $this->hasOneThrough(User::class, Order::class);
    }
}
