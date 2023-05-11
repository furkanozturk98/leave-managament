<?php

namespace Tests\Feature;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\Factory;
use Laravel\Socialite\Contracts\Provider;
use Mockery;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_redirects_to_home_page_if_already_authenticated()
    {
        $this->actingAs(User::factory()->create())
            ->get(route('login'))
            ->assertRedirect(RouteServiceProvider::HOME);
    }

    /** @test */
    public function it_shows_login_page()
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertViewIs('auth.login');
    }

    /** @test */
    public function it_validates_login_request()
    {
        $this->post(route('login'))
            ->assertRedirect()
            ->assertSessionHasErrors([
                'email' => trans('validation.required', ['attribute' => 'email']),
                'password' => trans('validation.required', ['attribute' => 'password']),
            ]);
    }

    /** @test */
    public function it_requires_email_to_be_string_in_login_request()
    {
        $this->post(route('login'), [
            'email' => 1,
        ])
            ->assertRedirect()
            ->assertSessionHasErrors([
                'email' => trans('validation.string', ['attribute' => 'email']),
            ]);
    }

    /** @test */
    public function requires_password_to_be_string_in_login_request()
    {

        $this->post(route('login'), [
            'password' => 1,
        ])
            ->assertRedirect()
            ->assertSessionHasErrors([
                'password' => trans('validation.string', ['attribute' => 'password']),
            ]);
    }

    /** @test */
    public function it_logs_in()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => $user->password,
        ])
            ->assertRedirect('/');
    }


    /** @test */
    public function it_redirects_to_google_login_page()
    {
        $this->get(route('google-login-redirect'))
            ->assertRedirect();
    }

    /** @test */
    public function it_cannot_logs_in_with_google_if_user_is_not_sign_in_google()
    {
        $this->get(route('google-login-callback'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function it_logs_in_with_google()
    {
        $abstractUser = $this->mock(\Laravel\Socialite\Two\User::class, function ($mock) {
            $mock->shouldReceive('getId')
                ->andReturn(rand())
                ->shouldReceive('getName')
                ->andReturn(Str::random(10))
                ->shouldReceive('getEmail')
                ->andReturn('fozturk@user.com.tr');
        });

        $provider = $this->mock(Provider::class, function ($mock) use($abstractUser) {
            $mock->shouldReceive('user')
                ->andReturn($abstractUser);
        });

        $this->app[Factory::class] = $this->mock(Factory::class, function ($mock) use($provider){
            $mock->shouldReceive('driver')->andReturn($provider);
        });

        $this->get(route('google-login-callback'))
            ->assertRedirect(route('leave.index'));
    }

    /** @test */
    public function it_cannot_logs_in_with_google_if_mail_domain_is_not_appropriate()
    {
        $abstractUser = $this->mock(\Laravel\Socialite\Two\User::class, function ($mock) {
            $mock->shouldReceive('getId')
                ->andReturn(rand())
                ->shouldReceive('getName')
                ->andReturn(Str::random(10))
                ->shouldReceive('getEmail')
                ->andReturn('fozturk@gmail.com');
        });

        $abstractUser->user = ['hd' => 'gmail.com'];

        $provider = $this->mock(Provider::class, function ($mock) use($abstractUser) {
            $mock->shouldReceive('user')
                ->andReturn($abstractUser);
        });

        $this->app[Factory::class] = $this->mock(Factory::class, function ($mock) use($provider){
            $mock->shouldReceive('driver')->andReturn($provider);
        });

        $this->get(route('google-login-callback'))
            ->assertRedirect(route('login'));

    }

}
