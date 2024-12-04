<?php

namespace App\Models\Renting;

use Illuminate\Database\Eloquent\Model;

class EmploymentInformation extends Model
{
    protected $table = 'employments_information';

    protected $fillable = [
        'ownerable_type',
        'ownerable_id',
        'name',
        'phone',
        'address',
        'neighborhood',
        'country',
        'department',
        'email',
        'position',
        'ext',
        'fax',
        'cellphone',
        'city',
    ];


    public function ownerable()
    {
        return $this->morphTo();
    }
}
