<?php

namespace App\Models\Renting;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cosigner extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $table = 'cosigners';

    protected $fillable = [
        'cosignerable_type',
        'cosignerable_id',
        'cosigner_type',
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


    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function employee()
    {
        return $this->morphOne(EmployeeType::class, 'entity');
    }

    public function independent()
    {
        return $this->morphOne(IndependentType::class, 'entity');
    }

    public function freelancerProfessional()
    {
        return $this->morphOne(FreelancerProfessionalType::class, 'entity');
    }

    public function pensioner()
    {
        return $this->morphOne(PensionerType::class, 'entity');
    }

    public function capitalrentier()
    {
        return $this->morphOne(CapitalRentierType::class, 'entity');
    }

    public function cosignerable()
    {
        return $this->morphTo();
    }
}
