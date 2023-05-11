<?php

namespace Tests\Feature\Api;

use App\Http\Resources\LeaveTypeResource;
use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LeaveTypeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_requires_to_authentication()
    {
        $this->getJson('/api/leave-types')
            ->assertUnauthorized()
            ->assertExactJson([
                'message' => 'Unauthenticated.'
            ]);
    }

    /** @test */
    public function it_returns_all_records()
    {
        $user = User::factory()->create();

        $leaveType = LeaveType::factory()->create();

         $this->actingAs($user)
            ->getJson('/api/leave-types')
            ->assertOk()
            ->assertJsonFragment(
                (new LeaveTypeResource($leaveType))->jsonSerialize() //json'a çevirme
            );
    }

}
