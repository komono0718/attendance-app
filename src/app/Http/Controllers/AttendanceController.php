<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use App\Models\BreakTime;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendance = Attendance::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'work_date' => today()
            ],
            [
                'status' => 'off'
            ]
        );

        return view('attendance.index', compact('attendance'));
    }

    public function clockIn()
    {
        $attendance = Attendance::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'work_date' => today(),
            ],
            [
                'status' => 'off',
            ]
        );

        if ($attendance->clock_in) {
            return back();
        }

        $attendance->update([
            'clock_in' => now(),
            'status' => 'working'
        ]);

        return back();
    }
    public function clockOut()
    {
        $attendance = Attendance::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'work_date' => today(),
            ],
            [
                'status' => 'off',
            ]
        );

        $attendance->update([
            'clock_out' => now(),
            'status' => 'finished'
        ]);

        return back()->with('message', 'お疲れ様でした');
    }

    public function breakStart()
    {
        $attendance = Attendance::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'work_date' => today(),
            ],
            [
                'status' => 'off',
            ]
        );

        BreakTime::create([
            'attendance_id' => $attendance->id,
            'break_start' => now()
        ]);

        $attendance->update([
            'status' => 'break'
        ]);

        return back();
    }

    public function breakEnd()
    {
        $attendance = Attendance::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'work_date' => today(),
            ],
            [
                'status' => 'off',
            ]
        );

        $break = BreakTime::where('attendance_id', $attendance->id)
            ->whereNull('break_end')
            ->latest()
            ->first();

        if ($break) {
            $break->update([
                'break_end' => now()
            ]);
        }

        $attendance->update([
            'status' => 'working'
        ]);

        return back();
    }
    public function break()
    {
        $attendance = Attendance::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'work_date' => today(),
            ],
            [
                'status' => 'off',
            ]
        );

        $attendance->update([
            'status' => 'break'
        ]);

        return back();
    }

    public function breakReturn()
    {
        $attendance = Attendance::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'work_date' => today(),
            ],
            [
                'status' => 'off',
            ]
        );

        $attendance->update([
            'status' => 'working'
        ]);

        return back();
    }

    public function show($id)
    {
        $attendance = Attendance::with(['user', 'breakTimes'])->findOrFail($id);

        $pendingRequest = false;
        $approvedRequest = false;
        $requestData = null;

        return view('attendance.detail', compact(
            'attendance',
            'pendingRequest',
            'approvedRequest',
            'requestData'
        ));
    }
}
