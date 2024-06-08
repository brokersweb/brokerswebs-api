<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasUuids, HasFactory;
    protected $table = 'addresses';
    protected $fillable = [
        'owner_id',
        'owner_type',
        'country',
        'municipality',
        'city',
        'neighborhood',
        'street',
    ];

    protected $hidden =
    [
        'created_at',
        'updated_at',
    ];

    public function immovable()
    {
        return $this->belongsTo(Immovable::class, 'owner_id');
    }

    public function buildingCompany()
    {
        return $this->belongsTo(BuildingCompany::class, 'owner_id', 'owner_type');
    }
}
