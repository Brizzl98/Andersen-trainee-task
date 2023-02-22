<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\UserService;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Traits\FakerTrait;


class UpdateEmailUserServiceTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations, FakerTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:install', ['--force' => true]);
    }

    /** @test */
    public function updateUserEmail()
    {
        $user = User::factory()->create();
        $newEmail = $this->fake()->email;
        $service = new UserService();
        $service->updateUserEmail($newEmail, $user);
        $this->assertDatabaseHas('users', [
            'email' => $newEmail
        ]);
    }

    /** @test */
    public function newToCurrentEmailComparison()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $email = $user->email;
        $service = new UserService();
        $result = $service->updateUserEmail($email, $user);
        $this->assertEquals("New and current emails are the same. No need to update", $result);
    }
}
