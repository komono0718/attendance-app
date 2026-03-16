<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceCorrectionRequest;
use App\Models\Attendance;

class AdminCorrectionController extends Controller
{

    public function index()
    {

        $requests = AttendanceCorrectionRequest::orderBy('created_at', 'desc')->get();

        return view('admin.request.list', compact('requests'));
    }

    public function approve($id)
    {

        $request = AttendanceCorrectionRequest::findOrFail($id);

        $attendance = Attendance::findOrFail($request->attendance_id);

        $attendance->update([

            'clock_in' => $request->requested_clock_in,
            'clock_out' => $request->requested_clock_out,

        ]);

        $request->update([

            'status' => 'approved'

        ]);

        return back();
    }
}
