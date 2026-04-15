<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StampCorrectionRequest;

class RequestController extends Controller
{
    public function approve($id)
    {
        $request = StampCorrectionRequest::find($id);

        $request->update([
            'status' => 'approved'
        ]);

        return redirect()->back();
    }
}
