<?php

namespace Tests\Feature;

use App\Mail\ForgotPasswordMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ForgotPasswordFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_reset_link_can_be_requested_and_stored(): void
    {
        Mail::fake();

        User::factory()->create([
            'email' => 'user@example.com',
        ]);

        $response = $this->post(route('password.email'), [
            'email' => 'user@example.com',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'user@example.com',
        ]);
        Mail::assertQueued(ForgotPasswordMail::class);
    }
}
