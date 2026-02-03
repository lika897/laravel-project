<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_register_with_valid_data(): void
    {
        $data = $this->validRegistrationData();

        $response = $this->post(route('register'), $data);

        $response->assertRedirect('/');
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => $data['email'],
        ]);
    }

    protected function validRegistrationData(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Test',
            'surname' => 'User',
            'phone' => '+380991234567',
            'email' => 'test+' . Str::random(5) . '@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ], $overrides);
    }



    #[Test]
    public function user_cannot_register_with_invalid_data(): void
    {
        $response = $this->post(route('register'), [
            'name' => '',
            'email' => 'not-email',
            'password' => '123',
            'password_confirmation' => '321',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors([
            'name',
            'email',
            'password',
        ]);

        $this->assertDatabaseMissing('users', [
            'email' => 'not-email',
        ]);

        $this->assertGuest();
    }


    #[Test]
    public function user_cannot_register_with_existing_email(): void
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $response = $this->post(route('register'), [
            'name' => 'Another User',
            'email' => 'test@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');


        $this->assertDatabaseHas('users', [
            'email' => 'test@test.com',
        ]);
    }


}
