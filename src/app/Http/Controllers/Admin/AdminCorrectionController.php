<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceCorrectionRequest;
use App\Models\Attendance;
use App\Models\StampCorrectionRequest;

class AdminCorrectionController extends Controller
{

    public function index()
    {
        $pending = StampCorrectionRequest::where('status', 'pending')->get();

        $approved = StampCorrectionRequest::where('status', 'approved')->get();

        return view('admin.request.list', compact('pending', 'approved'));
    }
    public function show($id)
    {
        $request = \App\Models\StampCorrectionRequest::with('attendance.user')->find($id);

        return view('admin.request.detail', compact('request'));
    }

    public function approve($id)
    {
        $request = StampCorrectionRequest::findOrFail($id);

        $attendance = Attendance::findOrFail($request->attendance_id);

        // 勤怠更新
        $attendance->update([
            'clock_in' => $request->clock_in,
            'clock_out' => $request->clock_out,
            'note' => $request->note,
        ]);

        // ← ここが超重要
        $request->update([
            'status' => 'approved',
        ]);

        return redirect('/admin/stamp_correction_request/list');
    }
}
