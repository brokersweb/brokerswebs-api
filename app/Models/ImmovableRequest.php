<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImmovableRequest extends Model
{
    use HasUuids, HasFactory;
    protected $table = 'immovable_requests';
    protected $fillable = [
        'fullname',
        'email',
        'message',
        'phone',
        'type',
        'immovable_id',
        'status',
        'terms',
    ];

    public function immovable()
    {
        return $this->belongsTo(Immovable::class);
    }
}
