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
        $newEmail = 'new_email@example.com';
        var_dump($user = Auth::user());
        $service = new UpdateEmailUserService();
        $service->updateUserEmail($newEmail);

        $this->assertDatabaseHas('users', [
            'email' => $newEmail
        ]);
    }

    /** @test */
    public function newCurrentEmailComparison()
    {
        $user = User::factory()->create();
        $service = new UpdateEmailUserService();
        $result = $service->updateUserEmail($user->email);

        $this->assertEquals("New and current emails are the same. No need to update", $result);
    }

    /** @test */
    public function updateAnotherUsersEmail()
    {
        // Create two users
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Login as user1
        $this->actingAs($user1);

        // Try to update user2's email
        $response = $this->post('/api/user/update', ['email' => $user2->email]);

        // Check that the response status is 403 (Forbidden)
        $response->assertStatus(403);

        // Check that the email was not updated
        $this->assertNotEquals($user2->email, $user2->fresh()->email);
    }
}
