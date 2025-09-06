<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case CUSTOMER = 'customer';
    case MODERATOR = 'moderator';
}
