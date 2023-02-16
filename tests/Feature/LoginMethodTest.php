<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;


class LoginMethodTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:install', ['--force' => true]);
    }
    /**
     * Test the login method
     *
     * @return void
     */
    public function testLogin()
    {
        // Create a user
        $user = User::factory()->create();
        // Send a login request with the user's email and password
        $response = $this->json('POST', 'api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        // Assert the status code is 200
        $response->assertStatus(200);
        // Assert the response contains the token
        $response->assertJsonStructure([
            'token'
        ]);
    }
}
