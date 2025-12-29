<?php

namespace App\Models;

use App\Enums\OrderStatusEnum;
use App\Policies\OrderPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[UsePolicy(OrderPolicy::class)]
class Order extends Model
{
    protected $guarded = [];

    protected $casts = [
        'status' => OrderStatusEnum::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot(['quantity', 'single_price', 'title']);
    }

    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }
}
