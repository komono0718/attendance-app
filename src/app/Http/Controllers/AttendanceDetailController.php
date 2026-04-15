<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests\AttendanceUpdateRequest;
use App\Models\StampCorrectionRequest;

class AttendanceDetailController extends Controller
{
    public function show(Request $request, $id = null)
    {
        $date = $request->input('date');

        if ($date) {
            $attendance = Attendance::firstOrCreate(
                [
                    'user_id' => auth()->id(),
                    'work_date' => $date
                ],
                [
                    'status' => 'off'
                ]
            )->load('breakTimes');
        } else {
            $attendance = Attendance::with('breakTimes')->find($id);
        }

        $pendingRequest = false;
        $approvedRequest = false;
        $requestData = null;

        if ($request->has('request_id')) {
            $requestData = StampCorrectionRequest::find($request->input('request_id'));

            if ($requestData) {
                if ($requestData->status === 'pending') {
                    $pendingRequest = true;
                }

                if ($requestData->status === 'approved') {
                    $approvedRequest = true;
                }
            }
        }

        return view('attendance.detail', compact(
            'attendance',
            'pendingRequest',
            'approvedRequest',
            'requestData'
        ));
    }
    public function update(AttendanceUpdateRequest $request, $id)
    {
        StampCorrectionRequest::create([
            'attendance_id' => $id,
            'clock_in' => $request->clock_in,
            'clock_out' => $request->clock_out,
            'note' => $request->note,
        ]);

        return redirect('/attendance/list');
    }
}
