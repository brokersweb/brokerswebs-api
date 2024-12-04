<?php

namespace App\Models\Renting;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'ownerable_type',
        'ownerable_id',
        'letter_employment',
        'tradition_freedom',
        'rut',
        'chamber_commerce',
        'last_taxreturn',
        'financial_statements',
    ];


    public function ownerable()
    {
        return $this->morphTo();
    }
}
