<?php

namespace Database\Seeders\OnDemand;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //        if (!User::where('email', config('services.admin.email'))->exists()) {
        //            User::factory()->admin()->createOne();
        //        }
        $user = User::createOrFirst([
            'email' => config('services.admin.email'),
        ], [
            'name' => 'Admin',
            'surname' => 'Admin',
            'phone' => '+380664977927',
            'email' => config('services.admin.email'),
            'password' => Hash::make(config('services.admin.password')),
        ]);

        if (! $user->hasRole(RoleEnum::ADMIN->value)) {
            $user->syncRoles([RoleEnum::ADMIN->value]);
        }
    }
}
