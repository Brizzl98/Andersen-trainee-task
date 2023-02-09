<?php

namespace Tests\Unit;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $userService;

    public function setUp(): void
    {
        parent::setUp();

        $this->userService = new UserService;
    }

    /** @test */
    public function it_tests_create_method()
    {
        $data = [
            'email' => 'test@example.com',
            'password' => 'secret',
        ];

        $user = $this->userService->createUser($data);
        $this->assertInstanceOf(\App\Models\User::class, $user);
        $this->assertDatabaseHas('users', [
            'email' => $data['email'],
        ]);

        $this->assertTrue(Hash::check($data['password'], $user->password));
    }
}
