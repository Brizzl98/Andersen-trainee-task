<?php

namespace Tests\Feature;

use App\Mail\DeleteMailer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class DeleteUserTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private $test;

    public function testDelete()
    {
        // Create a test user
        $user = User::factory()->create();
        $this->actingAs($user, 'api');
        Mail::fake();
        $response = $this->delete("/api/users/{$user->id}");
        Mail::assertSent(DeleteMailer::class);
        $response->assertStatus(204);
    }

    public function testDeleteAnotherUser()
    {
        // Create a test user
        $user = User::factory()->create();
        $this->actingAs($user, 'api');
        $nonExistingUserId = User::max('id') + 1;
        Mail::fake();
        $response = $this->delete("/api/users/{$nonExistingUserId}");
        Mail::assertNothingSent(DeleteMailer::class);
        $response->assertStatus(403);
    }
}
