<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;

class AdminCsvController extends Controller
{
    public function export($user_id)
    {
        $attendances = Attendance::with('user')
            ->where('user_id', $user_id)
            ->get();

        $filename = "attendance_user_{$user_id}.csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($attendances) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['日付', '出勤', '退勤']);

            foreach ($attendances as $attendance) {
                fputcsv($handle, [
                    $attendance->work_date,
                    optional($attendance->clock_in)->format('H:i'),
                    optional($attendance->clock_out)->format('H:i'),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
