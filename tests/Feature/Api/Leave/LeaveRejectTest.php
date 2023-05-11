<?php

namespace Tests\Feature\Leave;

use App\LeaveStatus;
use App\Models\Leave;
use App\Models\User;
use App\Notifications\LeaveRejectMail;
use App\Notifications\LeaveSendMail;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class LeaveRejectTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_requires_to_authentication()
    {
        $this->putJson('/api/leaves/1/reject')
            ->assertUnauthorized()
            ->assertExactJson([
                'message' => 'Unauthenticated.'
            ]);
    }

    /** @test */
    public function it_rejects_a_leave()
    {
        Notification::fake();

        $user = User::factory()->create();

        /** @var Leave $leave */
        $leave = Leave::factory()->create([
            'user_id' => $user->id,
            'status' => LeaveStatus::WAITING
        ]);

        $this->actingAs($user->manager)
            ->putJson('/api/leaves/' . $leave->id . '/reject')
            ->assertOk();

        $leave->status = LeaveStatus::REJECTED;

        $this->assertDatabaseHas('leaves', $leave->toArray());

        Notification::assertSentTo($user, fn (LeaveRejectMail $notification, $channels, $user) => $notification->user->id === $user->id );

    }

    /** @test */
    public function it_cannot_rejects_a_leave_with_a_status_is_not_equal_to_one()
    {
        /** @var User $user */
        $manager = User::factory()->create();

        $user = User::factory()->create([
            'manager_id' => $manager->id,
            'hr_id' => $manager->id,
        ]);

        /** @var Leave $leave */
        $leave = Leave::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($manager)
            ->putJson('/api/leaves/' . $leave->id . '/reject')
            ->assertForbidden();

    }

    /** @test */
    public function it_cannot_rejects_other_users_leaves()
    {
        /** @var User $user */
        $manager = User::factory()->create();

        /** @var Leave $leave */
        $leave = Leave::factory()->create([
            'status' => LeaveStatus::WAITING
        ]);

        $this->actingAs($manager)
            ->putJson('/api/leaves/' . $leave->id . '/reject')
            ->assertForbidden();

    }

}
