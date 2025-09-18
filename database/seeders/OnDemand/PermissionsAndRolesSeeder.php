<?php

namespace Database\Seeders\OnDemand;

use App\Enums\Permissions\AccountEnum;
use App\Enums\Permissions\CategoryEnum;
use App\Enums\Permissions\OrderEnum;
use App\Enums\Permissions\ProdectEnum;
use App\Enums\Permissions\UserEnum;
use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsAndRolesSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            ...AccountEnum::values(),
            ...CategoryEnum::values(),
            ...OrderEnum::values(),
            ...ProdectEnum::values(),
            ...UserEnum::values(),
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $this->createRoleAndSetPermissions(RoleEnum::CUSTOMER, [AccountEnum::values()]);
        $this->createRoleAndSetPermissions(RoleEnum::MODERATOR, [...CategoryEnum::values(), ...ProdectEnum::values()]);
        $this->createRoleAndSetPermissions(RoleEnum::ADMIN);
    }

    protected function createRoleAndSetPermissions(RoleEnum $role, ?array $permissions = null)
    {
        if (! Role::where('name', $role->value)->exists()) {
            Role::create(['name' => $role->value])
                ->givePermissionTo($permissions ?? Permission::all());
        }

    }
}
