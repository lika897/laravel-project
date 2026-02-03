<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_without_role_cannot_access_admin_panel(): void
    {
        $user = User::factory()->create([
            'role' => RoleEnum::CUSTOMER,
        ]);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    #[Test]
    public function moderator_can_access_admin_panel(): void
    {
        $user = User::factory()->create([
            'role' => RoleEnum::MODERATOR,
        ]);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertOk();
    }

    #[Test]
    public function admin_can_access_admin_panel(): void
    {
        $user = User::factory()->create([
            'role' => RoleEnum::ADMIN,
        ]);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertOk();
    }

}
