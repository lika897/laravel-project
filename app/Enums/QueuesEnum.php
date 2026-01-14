<?php

namespace App\Enums;

enum QueuesEnum: string
{
    case Default = 'default';
    case AdminNotifications = 'admin-notifications';
    case UsersNotifications = 'users-notifications';
    case ProductsExport = 'products-export';
    case ExportWriteLocal = 'products-export-write-local';
}
