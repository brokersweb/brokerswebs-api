<?php

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Model;
use App\Models\Immovable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdminContractRequest extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'admincontract_requirements';
    protected $fillable = [
        'immovable_id',
        'owner_dni',
        'certificate',
        'utility_bills',
        'invoice',
        'status'
    ];

    public function immovable()
    {
        return $this->belongsTo(Immovable::class);
    }
}
