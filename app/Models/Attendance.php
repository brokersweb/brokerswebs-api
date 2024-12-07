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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function confirmed()
    {
        return $this->belongsTo(User::class, 'confirmed_id');
    }


    public function getStatusAttribute()
    {

        switch ($this->attributes['status']) {
            case 'pending':
                return 'Por confirmar';
                break;
            case 'confirmed':
                return 'Confirmada';
                break;
            default:
                return 'Creada';
        }
    }
}
