<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic test example.
     */
    use RefreshDatabase;

    public function test_register_user(): void
    {
        $data = [
            'name' => "Neymar",
            'email' => "neymarzinho10@gmail.com",
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(200);
        $response->assertJson([
            'user' => [
                'name' => 'Neymar',
                'email' => 'neymarzinho10@gmail.com',
            ],
            'message' => 'Created successfully!'
        ]);    
    }

    public function test_cannot_register_user(): void
    {
        $data = [
            'name' => "",
            'email' => "neymarzinho10@gmail.com",
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(422);
        $response->assertJson([
            'error' => [
                'name' => [
                    'The name field is required.'
                ]
            ]
        ]);
    }

    public function test_login_returns_token_when_valid_email_and_password_provided()
    {
        // Arrange
        $user = User::factory()->create([
            'password' => bcrypt($password = 'i-love-laravel'),
        ]);
        $credentials = [
            'email' => $user->email,
            'password' => $password,
        ];

        // Act
        $response = $this->postJson('/api/login', $credentials);

        // Assert
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['token']);
    }

    public function test_login_returns_unauthorized_when_invalid_email_or_password_provided()
    {
        // Arrange
        $user = User::factory()->create([
            'password' => bcrypt('i-love-laravel'),
        ]);
        $credentials = [
            'email' => $user->email,
            'password' => 'wrong-password',
        ];

        // Act
        $response = $this->postJson('/api/login', $credentials);

        // Assert
        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(['message' => 'Unauthorized']);
    }
}
