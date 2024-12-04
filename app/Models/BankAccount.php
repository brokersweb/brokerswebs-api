<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'ownerable_id',
        'ownerable_type',
        'entity',
        'holder_name',
        'bank',
        'type',
        'account_number',
    ];


    public function ownerable()
    {
        return $this->morphTo();
    }
}
