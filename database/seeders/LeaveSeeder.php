<?php

namespace Database\Seeders;

use App\LeaveStatus;
use App\Models\Leave;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LeaveSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        //

        Leave::create([
            'user_id'      => 2,
            'leave_type_id' => 1,
            'description' => Str::random(30),
            'start_date' => now(),
            'end_date' => now(),
            'status' => LeaveStatus::DRAFT
        ]);

        Leave::create([
            'user_id'      => 2,
            'leave_type_id' => 2,
            'description' => Str::random(30),
            'start_date' => now(),
            'end_date' => now(),
            'status' => LeaveStatus::WAITING
        ]);

        Leave::create([
            'user_id'      => 2,
            'leave_type_id' => 3,
            'description' => Str::random(30),
            'start_date' => now(),
            'end_date' => now(),
            'status' => LeaveStatus::APPROVED
        ]);

        Leave::create([
            'user_id'      => 2,
            'leave_type_id' => 4,
            'description' => Str::random(30),
            'start_date' => now(),
            'end_date' => now(),
            'status' => LeaveStatus::REJECTED
        ]);


        Leave::create([
            'user_id'      => 3,
            'leave_type_id' => 1,
            'description' => Str::random(30),
            'start_date' => now(),
            'end_date' => now(),
            'status' => LeaveStatus::DRAFT
        ]);

        Leave::create([
            'user_id'      => 3,
            'leave_type_id' => 2,
            'description' => Str::random(30),
            'start_date' => now(),
            'end_date' => now(),
            'status' => LeaveStatus::WAITING
        ]);

        Leave::create([
            'user_id'      => 3,
            'leave_type_id' => 3,
            'description' => Str::random(30),
            'start_date' => now(),
            'end_date' => now(),
            'status' => LeaveStatus::APPROVED
        ]);

        Leave::create([
            'user_id'      => 3,
            'leave_type_id' => 4,
            'description' => Str::random(30),
            'start_date' => now(),
            'end_date' => now(),
            'status' => LeaveStatus::REJECTED
        ]);
    }
}
