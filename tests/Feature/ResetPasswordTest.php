<?php
namespace Tests\Feature;

use App\Mail\ResetPasswordMailer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_password_reset()
    {
        // Create a test user
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);
        Mail::fake();
        // Make a request to generate a reset token
        $response = $this->postJson('/api/reset-password', [
            'email' => $user->email,
        ]);
        Mail::assertSent(ResetPasswordMailer::class);


//        $response->assertSuccessful();
////        echo $response;
//        // Get the token from the response
//        var_dump($response);
//        $token = $response['data']['token'];
//
//        // Make a request to update the password with the token
//        $newPassword = $this->faker->password();
//        $response = $this->postJson('/api/reset-password-with-token', [
//            'token' => $token,
//            'password' => $newPassword,
//            'password_confirmation' => $newPassword,
//        ]);
//
//        // Check that the response indicates success
//        $response->assertSuccessful();
//
//        // Check that the user's password has been updated
//        $this->assertTrue(Hash::check($newPassword, $user->fresh()->password));
    }
}
