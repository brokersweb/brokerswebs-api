<?php

namespace App\Models\AccountsStatus;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AccountStatusDetail extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'accountstatu_details';
    protected $fillable = [
        'accountstatus_id',
        'qty',
        'concept',
        'value_unit',
        'amount',
        'immovable_code',
        'owner_dni',
    ];

    public function accountStatus()
    {
        return $this->belongsTo(AccountStatus::class, 'accountstatus_id', 'id');
    }
}
