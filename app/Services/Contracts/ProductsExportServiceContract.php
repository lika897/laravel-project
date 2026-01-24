<?php

namespace App\Services\Contracts;

use App\Models\User;

interface ProductsExportServiceContract
{
    public function export(User $user): void;

}
