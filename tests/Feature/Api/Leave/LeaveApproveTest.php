<?php

namespace Tests\Feature\Leave;

use App\LeaveStatus;
use App\Models\Leave;
use App\Models\User;
use App\Notifications\LeaveApproveMail;
use App\Notifications\LeaveSendMail;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class LeaveApproveTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_requires_to_authentication()
    {
        $this->putJson('/api/leaves/1/approve')
            ->assertUnauthorized()
            ->assertExactJson([
                'message' => 'Unauthenticated.'
            ]);
    }

    /** @test */
    public function it_approves_a_leave()
    {
        Notification::fake();

        /** @var User $user */
        $user = User::factory()->create();

        /** @var User $humanResource */
        $humanResource = $user->humanResource;

        /** @var Leave $leave */
        $leave = Leave::factory()->create([
            'user_id' => $user->id,
            'status' => LeaveStatus::WAITING
        ]);

        $this->actingAs($user->manager)
            ->putJson('/api/leaves/' . $leave->id . '/approve')
            ->assertOk();

        $leave->status = LeaveStatus::APPROVED;

        $this->assertDatabaseHas('leaves', $leave->toArray());

        Notification::assertSentTo($user, fn (LeaveApproveMail $notification, $channels, $user) => $notification->user->id === $user->id);

        Notification::assertSentTo($humanResource, fn (LeaveApproveMail $notification, $channels, $humanResource) => $notification->user->hr_id === $humanResource->id );

    }

    /** @test */
    public function it_cannot_approve_a_leave_with_a_status_which_is_not_equal_to_one()
    {
        /** @var User $manager */
        $manager = User::factory()->create();

        /** @var User $user */
        $user = User::factory()->create([
            'manager_id' => $manager->id,
            'hr_id' => $manager->id,
        ]);

        /** @var Leave $leave */
        $leave = Leave::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($manager)
            ->putJson('/api/leaves/' . $leave->id . '/approve')
            ->assertForbidden();

        $this->assertDatabaseHas('leaves', $leave->toArray());
    }

    /** @test */
    public function it_cannot_approve_other_users_leaves()
    {
        /** @var User $manager */
        $manager = User::factory()->create();

        /** @var Leave $leave */
        $leave = Leave::factory()->create([
            'status' =>  LeaveStatus::WAITING,
        ]);

        $this->actingAs($manager)
            ->putJson('/api/leaves/' . $leave->id . '/approve')
            ->assertForbidden();

    }

}
