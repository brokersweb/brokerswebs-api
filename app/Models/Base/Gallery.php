<?php

namespace App\Models\Base;

use App\Models\Immovable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'galleries';
    protected $fillable = [
        'immovable_id',
        'url',
        'status'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'immovable_id'
    ];

    public function immovable()
    {
        return $this->belongsTo(Immovable::class, 'immovable_id');
    }

}
