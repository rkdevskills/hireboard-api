<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        $response = $this->postJson('api/register', [
            'name' => 'employer',
            'email' => 'employer@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'employer'
        ]);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'user' => ['id', 'name', 'email', 'role'],
            'token'
        ]);

        $this->assertDatabaseHas('users',[
            'email' => 'employer@gmail.com',
            'role' => 'employer'
        ]);
    }

    public function test_user_cannot_register_with_duplicate_email(): void
    {
        $this->postJson('api/register', [
            'name' => 'employer',
            'email' => 'employer@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'employer'
        ]);

        $response = $this->postJson('api/register', [
            'name' => 'employer',
            'email' => 'employer@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'employer'
        ]);

        $response->assertStatus(422);

        $this->assertDatabaseCount('users', 1);
    }

    public function test_user_can_login(): void {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
            'role' => 'employer'
        ]);

        $response = $this->postJson('api/login', [
            'email' => $user->email,
            'password' =>'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'user' => ['id', 'name', 'email', 'role'],
            'token'
        ]);
    }
}
