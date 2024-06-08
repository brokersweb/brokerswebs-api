<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comformt extends Model
{
    use HasUuids, HasFactory;
    protected $table = 'comformts';
    protected $fillable = [
        'immovable_id',
        'balcony',
        'patio_or_terrace',
        'library',
        'domestic_server_room',
        'alarm',
        'airconditioning',
        'homeautomation',
        'gasnetwork',
        'clotheszone',
        'waterheater',
    ];

    protected $hidden =
    [
        'created_at',
        'updated_at',
    ];

    public function immovable()
    {
        return $this->belongsTo(Immovable::class, 'immovable_id');
    }
}
