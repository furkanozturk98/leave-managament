<?php

namespace Tests\Feature\Api;

use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DepartmentTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_requires_to_authentication()
    {
        $this->getJson('/api/departments')
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

        /** @var Department[] $leaves */
        $departments = Department::factory()->count(10)->create();

        $this->actingAs($user)
            ->getJson('/api/departments')
            ->assertOk()
            ->assertJsonFragment(
                ['data' => (DepartmentResource::collection($departments))->jsonSerialize()]
            );
    }
}
