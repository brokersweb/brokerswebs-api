<?php

namespace App\Models\Renting;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Applicant extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $table = 'applicants';
    protected $fillable = [
        'name',
        'lastname',
        'dni',
        'document_type',
        'expedition_place',
        'expedition_date',
        'phone',
        'working_type',
        'birthdate',
        'gender',
        'civil_status',
        'dependent_people',
        'profession',
        'email',
        'address',
        'dni_file'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function cosigners()
    {
        return $this->hasMany(Cosigner::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function references()
    {
        return $this->hasMany(Reference::class);
    }

    // Crear una funcion que me guarde los datos en capitalize y eliminar el espacio
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords(strtolower($value));
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
}
