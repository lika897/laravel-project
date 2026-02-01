<?php

namespace Tests;

use App\Enums\RoleEnum;
use App\Models\User;
use Database\Seeders\OnDemand\AdminUser;
use Database\Seeders\OnDemand\PermissionsAndRolesSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use DatabaseTransactions;
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        $this->artisan('migrate');

        $this->seed([
            PermissionsAndRolesSeeder::class,
            AdminUser::class,
        ]);


    }

    protected function user(RoleEnum $role): User
    {
        $user = User::role($role)->first();


        if(!$user) {
            $factory = User::factory();

            $factory = match($role) {
                RoleEnum::MODERATOR => $factory->moderator(),
                RoleEnum::ADMIN => $factory->admin(),
                default => $factory,
            };

            $user = $factory->create();


        }
        return $user;
    }



}
