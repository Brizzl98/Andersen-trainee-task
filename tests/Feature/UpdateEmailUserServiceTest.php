<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\UpdateEmailUserService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateEmailUserServiceTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:install', ['--force' => true]);
    }

    /** @test */
    public function updateUserEmail()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $newEmail = 'newtestemail@example.com';
        $service = new UpdateEmailUserService();
        $service->updateUserEmail($newEmail);
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
        $service = new UpdateEmailUserService();
        $result = $service->updateUserEmail($email);
        $this->assertEquals("New and current emails are the same. No need to update", $result);
    }
}
