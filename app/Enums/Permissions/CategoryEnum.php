<?php

namespace App\Enums\Permissions;

use App\Enums\Traits\Values;

enum CategoryEnum: string
{
    use Values;
    case PUBLISH = 'publish category';
    case EDIT = 'edit category';
    case DELETE = 'delete category';

}
