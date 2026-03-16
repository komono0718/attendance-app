<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceCorrectionRequest;
use Illuminate\Support\Facades\Auth;

class RequestListController extends Controller
{

    public function index()
    {

        $requests = AttendanceCorrectionRequest::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('request.list', compact('requests'));
    }
}
