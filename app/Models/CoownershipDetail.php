<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoownershipDetail extends Model
{
    use HasUuids, HasFactory;
    protected $table = 'coownership_details';
    protected $fillable = [
        'coownership_id',
        'elevator',
        'intercom',
        'garbage_shut',
        'visitor_parking',
        'social_room',
        'sports_court',
        'bbq_area',
        'childish_games',
        'parkland',
        'jogging_track',
        'jacuzzi',
        'turkish',
        'gym',
        'closed_circuit_tv',
        'climatized_pool',
        'goal',
        'goal_hours',
        'petfriendly_zone',
    ];

    protected $hidden =
    [
        'created_at',
        'updated_at',
    ];

    public function coownership()
    {
        return $this->belongsTo(Coownership::class, 'coownership_id');
    }
}
