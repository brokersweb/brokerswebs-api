<?php

namespace App\Models\Renting;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndependentType extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'independents_type';
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
        'chamber_commerce',
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
