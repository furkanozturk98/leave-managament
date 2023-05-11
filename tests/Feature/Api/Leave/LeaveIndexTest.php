<?php

namespace Tests\Feature\Leave;

use App\Http\Resources\LeaveResource;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LeaveIndexTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_requires_to_authentication()
    {
        $this->getJson('/api/leaves')
            ->assertUnauthorized()
            ->assertExactJson([
                'message' => 'Unauthenticated.'
            ]);
    }

    /** @test */
    public function it_returns_all_records()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Leave[] $leaves */
        $leaves = Leave::factory()->count(10)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->getJson('/api/leaves')
            ->assertOk()
            ->assertJsonFragment(
                ['data' => (LeaveResource::collection($leaves))->jsonSerialize()] //json'a çevirme
            );
    }

    /** @test */
    public function it_returns_leaves_by_user_id()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Leave[] $leaves */
        $leaves = Leave::factory()->count(10)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->getJson('/api/leaves?user_id='.$user->id)
            ->assertOk()
            ->assertJsonFragment(
                [LeaveResource::collection($leaves)->jsonSerialize()]
            );
    }

    /** @test */
    public function it_returns_leaves_by_leave_type_id()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Leave $leave */
        $leave = Leave::factory()->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->getJson('/api/leaves?leave_type_id='.$leave->leave_type_id)
            ->assertOk()
            ->assertJsonFragment(
                [(new LeaveResource($leave))->jsonSerialize()]
            );
    }

    /** @test */
    public function it_returns_leaves_by_status()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Leave $leave */
        $leave = Leave::factory()->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->getJson('/api/leaves?status='.$leave->status)
            ->assertOk()
            ->assertJsonFragment([
                (new LeaveResource($leave))->jsonSerialize()
            ]);
    }

}
