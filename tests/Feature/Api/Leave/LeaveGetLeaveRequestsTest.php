<?php

namespace Tests\Feature\Leave;

use App\Http\Resources\LeaveResource;
use App\LeaveStatus;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LeaveGetLeaveRequestsTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_requires_to_authentication()
    {
        $this->getJson('/api/leave-requests')
            ->assertUnauthorized()
            ->assertExactJson([
                'message' => 'Unauthenticated.'
            ]);
    }

    /** @test */
    public function it_can_get_leave_requests()
    {
        /** @var User $manager */
        $manager = User::factory()->create([
            'is_admin' => 1
        ]);

        /** @var User $user */
        $user = User::factory()->create([
            'manager_id' => $manager->id,
            'hr_id' => $manager->id
        ]);

        /** @var Leave $leave */
        $leave = Leave::factory()->create([
            'user_id' => $user->id,
            'status' => LeaveStatus::WAITING
        ]);

        $this->actingAs($manager)
            ->getJson('/api/leave-requests')
            ->assertOk()
            ->assertJsonFragment([
                (new LeaveResource($leave))->jsonSerialize()
            ]);
    }


    /** @test */
    public function it_cannot_get_other_users_leave_requests()
    {
        /** @var User $manager */
        $manager = User::factory()->create();

        /** @var Leave $leave */
        Leave::factory()->create([
            'status' => LeaveStatus::WAITING
        ]);

        $this->actingAs($manager)
            ->getJson('/api/leave-requests')
            ->assertForbidden();
    }

}
