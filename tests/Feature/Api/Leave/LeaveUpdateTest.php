<?php

namespace Tests\Feature\Leave;

use App\Http\Resources\LeaveResource;
use App\LeaveStatus;
use App\Models\Leave;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class LeaveUpdateTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_requires_to_authentication()
    {
        $this->putJson('/api/leaves/1')
            ->assertUnauthorized()
            ->assertExactJson([
                'message' => 'Unauthenticated.'
            ]);
    }

    /** @test */
    public function it_validates_form_request()
    {
        $user = User::factory()->create();
        /** @var Leave $leave */
        $leave = Leave::factory()->create();

        $this->actingAs($user)
            ->putJson('/api/leaves/'.$leave->id)
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'leave_type_id' => trans('validation.required', ['attribute' => trans('validation.attributes.leave_type_id')]),
                'description' => trans('validation.required', ['attribute' => trans('validation.attributes.description')]),
                'start_date' => trans('validation.required', ['attribute' => trans('validation.attributes.start_date')]),
                'end_date' => trans('validation.required', ['attribute' => trans('validation.attributes.end_date')]),
            ]);
    }

    /** @test */
    public function it_validates_start_date_is_before_or_equal_to_end_date()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Leave $leave */
        $leave = Leave::factory()->create([
            'start_date' => '2016-01-23',
            'end_date' => '2016-01-22'
        ]);

        $this->actingAs($user)
            ->putJson('/api/leaves/'.$leave->id, $leave->toArray())
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'start_date' => trans('validation.before_or_equal', [
                    'attribute' => trans('validation.attributes.start_date'),
                    'date' => trans('validation.attributes.end_date')
                ]),
            ]);
    }

    /** @test */
    public function it_updates_a_leave()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Leave $leave */
        $leave = Leave::factory()->create([
            'user_id' => $user->id
        ]);

        /** @var Leave $newLeave */
        $newLeave = Leave::factory()->make();

        $this->actingAs($user)
            ->putJson('/api/leaves/' . $leave->id, $newLeave->toArray())
            ->assertOk();

        $this->assertDatabaseHas('leaves', [
            'id' => $leave->id,
            'leave_type_id' => $newLeave->leave_type_id,
            'description' => $newLeave->description,
            'start_date' => $newLeave->start_date,
            'end_date' => $newLeave->end_date,
        ]);
    }

    /** @test */
    public function it_cannot_update_a_leave_with_a_status_which_is_not_equal_to_zero()
    {
        $user = User::factory()->create();

        /** @var Leave $leave */
        $leave = Leave::factory()->create([
            'user_id' => $user->id,
            'status' => LeaveStatus::WAITING
        ]);

        $this->actingAs($user)
            ->putJson('/api/leaves/' . $leave->id, $leave->toArray())
            ->assertForbidden();
    }

    /** @test */
    public function it_cannot_update_other_users_leaves()
    {
        $user = User::factory()->create();

        /** @var Leave $leave */
        $leave = Leave::factory()->create();

        $this->actingAs($user)
            ->putJson('/api/leaves/' . $leave->id, $leave->toArray())
            ->assertForbidden();
    }

}
