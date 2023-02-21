<?php

namespace Tests\Feature;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Traits\FakerTrait;

class UpdateEmailUserTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations, FakerTrait
;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:install', ['--force' => true]);
    }

    /** @test */
    public function updateUserEmail()
    {
        $newEmail = $this->fake()->email;
        $user = User::factory()->create();
        $this->actingAs($user, 'api');
        $response = $this->putJson('/api/users', ['email' => $newEmail]);
        $response->assertStatus(200);
    }
}
