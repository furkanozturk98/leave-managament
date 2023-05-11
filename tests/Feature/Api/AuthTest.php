<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{

    use DatabaseTransactions;

    /** @test */
    public function it_validates_login_request()
    {
        $this->postJson('/api/login/test')
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'email' => trans('validation.required', ['attribute' => 'email']),
                'password' => trans('validation.required', ['attribute' => 'password']),
            ]);
    }

    /** @test */
    public function requires_email_to_be_email_in_login_request()
    {
        $this->postJson('/api/login/test',[
                'email' => 'test',
                'password' => 'password'
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'email' => trans('validation.email', ['attribute' => 'email']),
            ]);
    }

    /** @test */
    public function it_returns_token()
    {
        /** @var User $user */
        $user = User::factory()->create([
            'password' => Hash::make('password')
        ]);

        $response = $this->post('/api/login/test', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertOk();

        $this->assertDatabaseHas('personal_access_tokens',[
            'id' => $user->tokens()->latest()->first()->id
        ]);
    }

    /** @test */
    public function it_cannot_logs_in_if_credentials_are_incorrect()
    {
        $this->postJson('/api/login/test', [
            'email' => 'test@test.com',
            'password' => 'test',
        ])->assertStatus(422)
            ->assertJsonValidationErrors([
            'email' => trans('auth.failed'),
        ]);

    }
}
