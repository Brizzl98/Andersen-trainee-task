<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;



class RegisterMethodTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    public function setUp(): void
    {
        parent::setUp();

        if (env('APP_ENV') == 'testing') {
            // Run the boot method to make sure passport is installed for testing
            $this->artisan('passport:install', [
                '--force' => true,
                '--no-interaction' => true
            ]);
        }

    }
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testRegister()
    {
        $data = [
            'email' => 'testmail@mail.ru',
            'password' => 'mypass',
            'password_confirmation' => 'mypass'
        ];

        $response = $this->json('POST', 'api/users', $data)->assertStatus(201);

        $response->assertJsonStructure(['token']);

        $this->assertDatabaseHas('users', [
            'email' => 'testmail@mail.ru',
        ]);
    }
}
