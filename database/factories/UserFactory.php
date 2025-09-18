<?php

namespace Database\Factories;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = rand(0, 1) ? 'male' : 'female';

        return [
            'name' => fake()->name($gender),
            'surname' => fake()->lastName($gender),
            'phone' => fake()->unique()->e164PhoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            if (! $user->hasAnyRole(RoleEnum::values())) {
                $user->assignRole(RoleEnum::CUSTOMER->value);
            }
        });
    }

    public function admin(): static
    {
        return $this->afterCreating(function (User $user) {
            $user->syncRoles([RoleEnum::ADMIN->value]);
        });
    }

    public function moderator(): static
    {
        return $this->afterCreating(function (User $user) {
            $user->syncRoles([RoleEnum::MODERATOR->value]);
        });
    }
}
