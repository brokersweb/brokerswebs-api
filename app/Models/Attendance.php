<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasUuids;

    protected $fillable = [
        'attdate',
        'status',
        'user_id',
        'notes',
        'confirmed_id'
    ];


    public function items()
    {
        return $this->hasMany(AttendanceItem::class);
    }
}
