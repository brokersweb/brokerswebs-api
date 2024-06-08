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
        'name', 'lastname',
        'dni',
        'birthdate',
        'document_type',
        'expedition_country',
        'expedition_department',
        'expedition_city',
        'expedition_date',
        'working_type',
        'working_type_id',
        'cosigner_type',
        'freedom_tradition',
        'lease_contract',
        'phone',
        'cosignerable_type',
        'cosignerable_id',
        'dni_file'
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
