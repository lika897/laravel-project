<?php

namespace App\Enums\Permissions;

enum AccountEnum: string
{
    case EDIT = 'edit account';
    case DELETE = 'delete account';
}
