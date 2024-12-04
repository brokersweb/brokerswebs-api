<?php

namespace App\Models\Renting;

use Illuminate\Database\Eloquent\Model;

class Taker extends Model
{

    protected $fillable = [
        'name',
        'lastname',
        'document_type',
        'dni',
        'expedition_place',
        'expedition_date',
        'cellphone',
        'phone',
        'working_type',
        'birthdate',
        'gender',
        'civil_status',
        'profession',
        'email',
        'address',
        'city_birth',
        'nationality',
        'neighborhood',
        'city_municipality',
        'department',
        'country',
        'professional_title',
        'occupation',
        'main_economic_activity',
        'detail_economic_activity',
        'facebook',
        'twitter',
        'has_realestate',
        'property_address',
        'property_city',
        'has_vehicles',
        'brand',
        'line',
        'model',
        'has_pledge',
        'dni_file',
    ];

}
