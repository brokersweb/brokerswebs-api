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
        'account_status_id',
        'balance'
    ];

    public function accountStatu()
    {
        return $this->hasOne(AccountStatus::class, 'id', 'account_status_id');
    }
}
