<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;

class AdminAttendanceController extends Controller
{

    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());

        $attendances = Attendance::with('user')
            ->whereDate('work_date', $date)
            ->get();

        return view('admin.attendance.list', compact('attendances', 'date'));
    }
    public function staff(Request $request, $id)
    {
        $date = $request->input('date', now()->toDateString());

        $carbon = \Carbon\Carbon::parse($date);

        $user = User::find($id);

        $attendances = Attendance::with('user', 'breakTimes')
            ->where('user_id', $id)
            ->whereYear('work_date', $carbon->year)
            ->whereMonth('work_date', $carbon->month)
            ->get()
            ->keyBy(function ($item) {
                return \Carbon\Carbon::parse($item->work_date)->format('Y-m-d');
            });

        $days = [];

        for ($i = 1; $i <= $carbon->daysInMonth; $i++) {
            $day = $carbon->copy()->day($i)->format('Y-m-d');

            $days[] = [
                'date' => $day,
                'attendance' => $attendances[$day] ?? null,
            ];
        }

        return view('admin.attendance.staff.list', compact('days', 'date', 'user'));
    }
    public function show($id)
    {
        $attendance = \App\Models\Attendance::findOrFail($id);

        return view('admin.attendance.detail', compact('attendance'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'clock_in' => ['required'],
            'clock_out' => ['required', 'after:clock_in'],
            'note' => ['required'],
        ]);

        $attendance = Attendance::with('breakTimes')->findOrFail($id);

        $attendance->update([
            'clock_in' => $request->clock_in,
            'clock_out' => $request->clock_out,
        ]);

        return back();
    }
}
