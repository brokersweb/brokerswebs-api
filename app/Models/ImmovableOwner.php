<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImmovableOwner extends Model
{
    use HasUuids, HasFactory;
    protected $table = 'immovable_owner';
    protected $fillable = [
        'immovable_id',
        'owner_id',
    ];

    public function immovable()
    {
        return $this->belongsTo(Immovable::class, 'immovable_owner', 'immovable_id', 'owner_id');
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class, 'immovable_owner', 'immovable_id', 'owner_id');
    }
}
