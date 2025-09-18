<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\OnDemand\AdminUser;
use Database\Seeders\OnDemand\PermissionsAndRolesSeeder;
use Illuminate\Database\Seeder;

class onDemandSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PermissionsAndRolesSeeder::class);
        $this->call(AdminUser::class);

    }
}
