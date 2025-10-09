<?php

namespace App\Enums\Permissions;

use App\Enums\Traits\Values;

enum UserEnum: string
{
    use Values;
    case PUBLISH = 'publish user';
    case EDIT = 'edit user';
    case DELETE = 'delete user';

}
