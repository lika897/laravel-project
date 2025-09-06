<?php

namespace App\Enums\Permissions;

enum UserEnum: string
{
    case PUBLISH = 'publish user';
    case EDIT = 'edit user';
    case DELETE = 'delete user';
}
