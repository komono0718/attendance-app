<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AdminAttendanceController extends Controller
{

    public function index()
    {

        $attendances = Attendance::with('user')
            ->orderBy('work_date', 'desc')
            ->get();

        return view('admin.attendance.list', compact('attendances'));
    }
}
