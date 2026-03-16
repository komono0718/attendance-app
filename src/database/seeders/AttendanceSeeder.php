<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        Attendance::create([
            'user_id' => 1,
            'work_date' => now(),
            'clock_in' => now()->setTime(9, 0),
            'clock_out' => now()->setTime(18, 0),
            'status' => 'finished'
        ]);

        Attendance::create([
            'user_id' => 2,
            'work_date' => now(),
            'clock_in' => now()->setTime(9, 30),
            'clock_out' => now()->setTime(18, 30),
            'status' => 'finished'
        ]);
    }
}
