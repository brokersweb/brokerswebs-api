<?php

namespace App\Models\AccountsStatus;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreviousBalance extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'previous_balances';
    protected $fillable = [
        'accountable_id',
        'accountable_type',
        'balance'
    ];


    public function accountable()
    {
        return $this->morphTo();
    }
}
