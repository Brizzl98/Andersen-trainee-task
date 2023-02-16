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
        $token='';
        Mail::assertSent(ResetPasswordMailer::class, function ( $mail) use(&$token) {
            $mail->build();
            $token = $mail->token;
            return is_string($token) && strlen($token) === 60;
        });

        // Make a request to update the password with the token
        $newPassword = $this->faker->password();
        $response = $this->postJson('/api/reset-password-with-token', [
            'token' => $token,
            'password' => $newPassword,
        ]);

        // Check that the response indicates success
//        var_dump($response);
        $response->assertSuccessful();
//        ($response->original["message"]);
        $response->assertStatus(200);
        $response->assertJson([
            "message" => "Password updated successfully"
        ]);
        // Check that the user's password has been updated
        $this->assertTrue(Hash::check($newPassword, $user->fresh()->password));
    }
}
