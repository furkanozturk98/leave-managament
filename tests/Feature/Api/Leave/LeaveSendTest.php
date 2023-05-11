<?php

namespace Tests\Feature\Leave;

use App\LeaveStatus;
use App\Models\Leave;
use App\Models\User;
use App\Notifications\LeaveSendMail;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class LeaveSendTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_requires_to_authentication()
    {
        $this->putJson('/api/leaves/1/send')
            ->assertUnauthorized()
            ->assertExactJson([
                'message' => 'Unauthenticated.'
            ]);
    }

    /** @test */
    public function it_can_send_a_leave_to_be_approved()
    {
        Notification::fake();

        /** @var User $user */
        $user = User::factory()->create();

        /** @var User $manager */
        $manager = $user->manager;

        /** @var Leave $leave */
        $leave = Leave::factory()->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->putJson('/api/leaves/' . $leave->id . '/send')
            ->assertOk();

        $leave->status = LeaveStatus::WAITING;

        $this->assertDatabaseHas('leaves', $leave->toArray());

        Notification::assertSentTo($manager, fn(LeaveSendMail $notification, $channels, $manager) => $notification->user->manager_id === $manager->id );
    }

    /** @test */
    public function it_cannot_send_a_leave_with_a_status_is_not_equal_to_zero()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Leave $leave */
        $leave = Leave::factory()->create([
            'user_id' => $user->id,
            'status' => LeaveStatus::WAITING
        ]);

        $this->actingAs($user)
            ->putJson('/api/leaves/' . $leave->id . '/send')
            ->assertForbidden();

    }

    /** @test */
    public function it_cannot_send_other_users_leaves()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Leave $leave */
        $leave = Leave::factory()->create();

        $this->actingAs($user)
            ->putJson('/api/leaves/' . $leave->id . '/send')
            ->assertForbidden();

    }

}
