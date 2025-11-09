<?php

namespace App\Enums\Permissions;

use App\Enums\Traits\Values;

enum ProductEnum: string
{
    use Values;
    case PUBLISH = 'publish product';
    case EDIT = 'edit product';
    case DELETE = 'delete product';

}
