<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceCorrectionRequest;
use App\Models\StampCorrectionRequest;
use Illuminate\Support\Facades\Auth;


class RequestListController extends Controller
{
    public function index()
    {
        $pending = StampCorrectionRequest::where('status', 'pending')
            ->whereHas('attendance', fn($q) => $q->where('user_id', Auth::id()))
            ->get();

        $approved = StampCorrectionRequest::where('status', 'approved')
            ->whereHas('attendance', fn($q) => $q->where('user_id', Auth::id()))
            ->get();

        return view('request.list', compact('pending', 'approved'));
    }
}
