<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LeavePageTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_required_authentication()
    {
        $this->get('/')
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function it_shows_leave_page()
    {
        $this->actingAs(User::factory()->create())
            ->get('/leaves')
            ->assertOk()
            ->assertViewIs('leave.index');
    }
}
