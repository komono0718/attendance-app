<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceListController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', now()->toDateString());

        $carbon = Carbon::parse($date);

        $attendances = Attendance::with('breakTimes')
            ->where('user_id', auth()->id())
            ->whereYear('work_date', $carbon->year)
            ->whereMonth('work_date', $carbon->month)
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->work_date)->format('Y-m-d');
            });

        $days = [];

        for ($i = 1; $i <= $carbon->daysInMonth; $i++) {
            $day = $carbon->copy()->day($i)->format('Y-m-d');

            $days[] = [
                'date' => $day,
                'attendance' => $attendances[$day] ?? null,
            ];
        }

        return view('attendance.list', compact('days', 'date'));
    }
}
