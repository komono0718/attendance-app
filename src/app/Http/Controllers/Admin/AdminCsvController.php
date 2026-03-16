<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;

class AdminCsvController extends Controller
{
    public function export($user_id)
    {
        $attendances = Attendance::where('user_id', $user_id)->get();

        $csvHeader = [
            '日付',
            '出勤',
            '退勤'
        ];

        $callback = function () use ($attendances, $csvHeader) {

            $file = fopen('php://output', 'w');

            fputcsv($file, $csvHeader);

            foreach ($attendances as $attendance) {
                fputcsv($file, [
                    $attendance->work_date,
                    $attendance->clock_in,
                    $attendance->clock_out
                ]);
            }

            fclose($file);
        };

        return response()->stream(
            $callback,
            200,
            [
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=attendance.csv"
            ]
        );
    }
}
