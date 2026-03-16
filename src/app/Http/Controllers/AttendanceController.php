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

        $attendance = Attendance::firstOrCreate([
            'user_id' => Auth::id(),
            'work_date' => today()
        ]);

        return view('attendance.index', compact('attendance'));
    }

    public function clockIn()
    {

        Attendance::where('user_id', Auth::id())
            ->whereDate('work_date', today())
            ->update([
                'clock_in' => now(),
                'status' => 'working'
            ]);

        return back();
    }
    public function clockOut()
    {
        Attendance::where('user_id', Auth::id())
            ->whereDate('work_date', today())
            ->update([
                'clock_out' => now(),
                'status' => 'finished'
            ]);

        return back()->with('message', 'お疲れ様でした');
    }

    public function breakStart()
    {
        $attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('work_date', today())
            ->first();

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
        $break = BreakTime::whereNull('break_end')->latest()->first();

        $break->update([
            'break_end' => now()
        ]);

        Attendance::where('user_id', Auth::id())
            ->whereDate('work_date', today())
            ->update([
                'status' => 'working'
            ]);

        return back();
    }
}