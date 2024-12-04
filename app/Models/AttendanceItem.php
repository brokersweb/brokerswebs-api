<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class AttendanceItem extends Model
{
    use HasUuids;

    protected $fillable = [
        'attendance_id',
        'staff_id',
        'position',
        'worksite',
        'payment',
        'status',
    ];


    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function staff()
    {
        return $this->belongsTo(User::class);
    }
}
