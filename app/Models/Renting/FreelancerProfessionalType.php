<?php

namespace App\Models\Renting;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class FreelancerProfessionalType extends Model
{
    use HasUuids, HasFactory;
    protected $table = 'freelancers_professional_type';

    protected $fillable = [
        'name', 'phone',
        'address',
        'country',
        'department',
        'city',
        'description',
        'income',
        'expense',
        'cosigner_id',
        'caccount_public',
        'rut_file',
        'bank_statement',
        'income_statement',
        'entity_type',
        'entity_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function entity()
    {
        return $this->morphTo();
    }
}
