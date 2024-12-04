<?php

namespace App\Models;

use App\Models\Renting\CapitalRentierType;
use App\Models\Renting\Cosigner;
use App\Models\Renting\EmployeeType;
use App\Models\Renting\FreelancerProfessionalType;
use App\Models\Renting\IndependentType;
use App\Models\Renting\PensionerType;
use App\Models\Renting\Reference;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasUuids, HasFactory, SoftDeletes;
    protected $table = 'tenants';

    protected $fillable = [
        'dependent_people',
        'photo',
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


    public function references()
    {
        return $this->morphMany(Reference::class, 'referencable');
    }

    public function cosigners()
    {
        return $this->morphMany(Cosigner::class, 'cosignerable');
    }

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

    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->lastname;
    }

    public function immovables()
    {
        return $this->belongsToMany(Immovable::class, 'immovable_tenant');
    }

    public function immovable(): HasOne
    {
        return $this->hasOne(Immovable::class);
    }
}
