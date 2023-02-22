<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;


class GetUsersTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test the login method
     *
     * @return void
     */
    public function testGetUser()
    {
        // Create a user
        $user = User::factory()->create();
        $this->actingAs($user, 'api');
        $user->save();
        // Send a GET request to the /users/{id} endpoint with the user ID
        $response = $this->get("api/users/{$user->id}");
        // Assert that the response has a successful status code
        $response->assertStatus(200);

        // Assert that the response contains the user data
        $response->assertJson([
            'Your data' => [
                'id' => $user->id,
                'email' => $user->email,
            ]
        ]);
    }

    public function testGetNonExistingUser()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');
        $lastId = User::max('id');
        $non_existing_user_id = $lastId + 1;
        // Send a GET request to the /users/{id} endpoint with a non-existing user ID
        $response = $this->get("api/users/{$non_existing_user_id}");

        // Assert that the response has a not found status code
        $response->assertStatus(404);

        // Assert that the response contains the "User not found" error message
        $response->assertJson([
            'error' => 'User not found',
        ]);
    }

    public function testGetForbiddenUser()
    {
        // Get two users from the database
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        // Authenticate the first user
        $this->actingAs($user1, 'api');

        // Send a GET request to the /users/{id} endpoint with the second user's ID
        $response = $this->get("api/users/{$user2->id}");

        // Assert that the response has a forbidden status code
        $response->assertStatus(403);

        // Assert that the response contains the "Forbidden" error message
        $response->assertJson([
            'error' => 'Forbidden',
        ]);
    }

    public function testGetUsersList()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');
        $response = $this->get("api/users");
        $response->assertStatus(200);
    }
}
