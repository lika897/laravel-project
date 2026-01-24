<?php
namespace App\Policies\Api\v2;

use App\Enums\RoleEnum;
use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Product $product): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole([RoleEnum::ADMIN->value, RoleEnum::MODERATOR->value]);
    }

    public function update(User $user, Product $product): bool
    {
        return $user->hasAnyRole([RoleEnum::ADMIN->value, RoleEnum::MODERATOR->value]);
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->hasAnyRole([RoleEnum::ADMIN->value]);
    }
}
