<?php

namespace App\Models;

use App\Models\Base\CuisineType;
use App\Models\Base\FloorType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImmovableDetail extends Model
{
    use HasUuids, HasFactory;
    protected $table = 'immovabledetails';
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'immovable_id',
        'common_zones',
        'internal_features',
        'external_features',
        'bedrooms',
        'bathrooms',
        'hasparkings',
        'useful_parking_room',
        'total_area',
        'gross_building_area',
        'floor_located',
        'stratum',
        'unit_type',
        'floor_type',
        'cuisine_type',
        'floor_type_id',
        'cuisine_type_id',
        'year_construction',
        'tower'
    ];

    public function immovable()
    {
        return $this->belongsTo(Immovable::class);
    }

    public function floor()
    {
        return $this->belongsTo(FloorType::class, 'floor_type_id');
    }

    public function cuisine()
    {
        return $this->belongsTo(CuisineType::class, 'cuisine_type_id');
    }
}
