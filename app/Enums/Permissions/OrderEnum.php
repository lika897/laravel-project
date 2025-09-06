<?php

namespace App\Enums\Permissions;

enum OrderEnum: string
{
    case EDIT = 'edit order';
    case DELETE = 'delete order';
}
