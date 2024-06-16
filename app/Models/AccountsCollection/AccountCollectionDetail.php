<?php

namespace App\Models\AccountsCollection;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountCollectionDetail extends Model
{

    use HasUuids, HasFactory;

    protected $table = 'accountscollection_details';
    protected $fillable = [
        'accountscollection_id',
        'qty',
        'concept',
        'value_unit',
        'amount',
        'immovable_code',
        'tenant_dni',
    ];

    public function accountscollection()
    {
        return $this->belongsTo(AccountCollection::class, 'accountscollection_id', 'id');
    }
}
