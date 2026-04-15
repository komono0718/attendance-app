<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Attendance extends Model
{

    protected $fillable = [
        'user_id',
        'work_date',
        'clock_in',
        'clock_out',
        'status',
        'note'
    ];

    protected $casts = [
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function breakTimes()
    {
        return $this->hasMany(\App\Models\BreakTime::class);
    }
}
