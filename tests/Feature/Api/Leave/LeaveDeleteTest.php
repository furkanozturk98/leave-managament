<?php

namespace Tests\Feature\Leave;

use App\LeaveStatus;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LeaveDeleteTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_requires_to_authentication()
    {
        $this->deleteJson('/api/leaves/1')
            ->assertUnauthorized()
            ->assertExactJson([
                'message' => 'Unauthenticated.'
            ]);
    }

    /** @test */
    public function it_deletes_a_leave()
    {

        /** @var User $user */
        $user = User::factory()->create();

        /** @var Leave $leave */
        $leave = Leave::factory()->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->deleteJson('/api/leaves/' . $leave->id)
            ->assertStatus(204);

        $this->assertDatabaseMissing('leaves', $leave->toArray());
    }

    /** @test */
    public function it_cannot_delete_a_leave_with_a_status_which_is_not_equal_to_zero()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Leave $leave */
        $leave = Leave::factory()->create([
            'user_id' => $user->id,
            'status' => LeaveStatus::WAITING
        ]);

        $this->actingAs($user)
            ->deleteJson('/api/leaves/' . $leave->id)
            ->assertForbidden();

    }

    /** @test */
    public function it_cannot_delete_other_users_leaves()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Leave $leave */
        $leave = Leave::factory()->create();

        $this->actingAs($user)
            ->deleteJson('/api/leaves/' . $leave->id)
            ->assertForbidden();

    }

}
