<?php

namespace Tests\Feature;

use App\Http\Resources\LeaveResource;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LeaveShowTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_requires_to_authentication()
    {
        $this->getJson('/api/leaves/1')
            ->assertUnauthorized()
            ->assertExactJson([
                'message' => 'Unauthenticated.'
            ]);
    }

    /** @test */
    public function it_shows_a_leave()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Leave $leave */
        $leave = Leave::factory()->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->getJson('/api/leaves/'.$leave->id)
            ->assertOk()
            ->assertJsonFragment(
                (new LeaveResource($leave))->jsonSerialize()
            );
    }

    /** @test */
    public function it_cannot_show_other_users_leave()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Leave $leave */
        $leave = Leave::factory()->create();

        $this->actingAs($user)
            ->getJson('/api/leaves/'.$leave->id)
            ->assertForbidden();
    }
}
