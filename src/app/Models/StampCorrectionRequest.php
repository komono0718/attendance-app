<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StampCorrectionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'clock_in',
        'clock_out',
        'note',
        'status',
    ];

    public function attendance()
    {
        return $this->belongsTo(\App\Models\Attendance::class);
    }
}
