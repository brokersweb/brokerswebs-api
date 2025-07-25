<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImmovableType extends Model
{
    use HasUuids, HasFactory;
    protected $table = 'immovabletypes';
    protected $fillable = [
        'description',
    ];

    protected $hidden =
    [
        'created_at',
        'updated_at',
    ];

    public function immovables()
    {
        return $this->hasMany(Immovable::class);
    }
}
