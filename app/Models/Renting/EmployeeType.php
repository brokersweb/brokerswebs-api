<?php

namespace App\Models\Renting;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeType extends Model
{
    use HasUuids, HasFactory;


    protected $table = 'employees_type';

    protected $fillable = [
        'name', 'phone',
        'address',
        'country',
        'department',
        'city',
        'market',
        'salary',
        'expense',
        'entry_date',
        'cosigner_id',
        'working_letter',
        'payment_stubs',
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
