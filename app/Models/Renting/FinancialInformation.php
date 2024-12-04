<?php

namespace App\Models\Renting;

use Illuminate\Database\Eloquent\Model;

class FinancialInformation extends Model
{
    protected $fillable = [
        'ownerable_type',
        'ownerable_id',
        'income',
        'total_expenses',
        'other_income',
        'total_assets',
        'which',
        'total_liabilities',
        'total_income',
        'is_declarant',
        'withholding_agent',
        'vat_agent',
        'taxpayer',
        'chamber_commerce',
        'has_bankaccounts',
    ];

    public function ownerable()
    {
        return $this->morphTo();
    }

}
