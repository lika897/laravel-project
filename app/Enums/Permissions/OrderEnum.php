<?php

namespace App\Enums\Permissions;

use App\Enums\Traits\Values;

enum OrderEnum: string
{
    use Values;
    case EDIT = 'edit order';
    case DELETE = 'delete order';

}
