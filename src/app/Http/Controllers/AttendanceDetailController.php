<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceDetailController extends Controller
{
    public function show($id)
    {

        $attendance = Attendance::findOrFail($id);

        return view('attendance.detail', compact('attendance'));
    }
}
