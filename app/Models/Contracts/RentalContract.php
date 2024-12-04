<?php

namespace App\Models\Contracts;

use App\Models\Immovable;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class RentalContract extends Model
{
    use HasUuids;

    protected $fillable =  [
        'rentalnum',
        'immovable_id',
        'tenant_id',
        'start_date',
        'end_date',
        'status',
        'rent_price',
        'cutoff_day',
        'path_file',
        'cosigner_id',
        'cosignerii_id',
        'reference_id',
        'referenceii_id'
    ];

    public function immovable()
    {
        return $this->belongsTo(Immovable::class);
    }
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

}
