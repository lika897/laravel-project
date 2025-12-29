<?php

namespace App\Enums\Traits;

trait Values
{
    public static function values(): array
    {
//        return array_column(static::values(), 'value');
        return array_map(fn(self $case) => $case->value, static::cases());
    }

}
