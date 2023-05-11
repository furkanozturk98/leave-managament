<?php

namespace Tests\Feature\Leave;

use App\Models\Leave;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LeaveStoreTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_requires_to_authentication()
    {
        $this->postJson('/api/leaves')
            ->assertUnauthorized()
            ->assertExactJson([
                'message' => 'Unauthenticated.'
            ]);
    }

    /** @test */
    public function it_validates_post_request()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson('/api/leaves')
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
        $leave = Leave::factory()->make([
            'start_date' => '2016-01-23',
            'end_date' => '2016-01-22'
        ]);

        $this->actingAs($user)
            ->postJson('/api/leaves', $leave->toArray())
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'start_date' => trans('validation.before_or_equal', [
                    'attribute' => trans('validation.attributes.start_date'),
                    'date' => trans('validation.attributes.end_date')
                ]),
            ]);
    }

    /** @test */
    public function it_creates_a_leave()
    {
        $user = User::factory()->create();

        /** @var Leave $leave */
        $leave = Leave::factory()->make();

        $this->actingAs($user)
            ->postJson('/api/leaves', $leave->toArray())
            ->assertCreated();

        $this->assertDatabaseHas('leaves', [
            'leave_type_id' => $leave->leave_type_id,
            'description' => $leave->description,
            'start_date' => $leave->start_date->toDateTimeString(),
            'end_date' => $leave->end_date->toDateTimeString(),
        ]);
    }


}
