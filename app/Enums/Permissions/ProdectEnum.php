<?php

namespace App\Enums\Permissions;

enum ProdectEnum: string
{
    case PUBLISH = 'publish product';
    case EDIT = 'edit product';
    case DELETE = 'delete product';
}
