<?php

namespace App\Enums\Permissions;

enum CategoryEnum: string
{
    case PUBLISH = 'publish category';
    case EDIT = 'edit category';
    case DELETE = 'delete category';
}
