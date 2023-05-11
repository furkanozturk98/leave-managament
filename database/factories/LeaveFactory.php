<?php

namespace Database\Factories;

use App\Models\Leave;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LeaveFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Leave::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory()->create()->id,
            'leave_type_id' => 1,
            'description' => $this->faker->text,
            'start_date' => now(),
            'end_date'=> now(),
            'status' => 0
        ];
    }
}
