<?php

namespace App\Enums\Permissions;

use App\Enums\Traits\Values;

enum AccountEnum: string
{
    use Values;
    case EDIT = 'edit account';
    case DELETE = 'delete account';

}
