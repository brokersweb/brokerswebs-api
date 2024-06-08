<?php

namespace App\Models\Renting;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    use HasUuids, HasFactory;
    protected $table = 'references';

    protected $fillable = [
        'name',
        'lastname',
        'birthdate',
        'residence_address',
        'residence_country',
        'residence_department',
        'residence_city',
        'kinship',
        'type',
        'phone',
        'referencable_type',
        'referencable_id',
        'email',
        'dni'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    public function referencable()
    {
        return $this->morphTo();
    }
}
