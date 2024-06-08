<?php
namespace App\Models\Base;

use App\Models\Immovable;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaseDocContract extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'leasedoc_contracts';
    protected $fillable = [
        'user_id',
        'tenant_id',
        'immovable_id',
        'document_path',
        'status',
        'tenant_name',
        'tenant_dni',
        'tenant_dni_expedition',
        'tenant_dni_expedition_place',
        'immovable_address',
        'immovable_rent_price',
        'immovable_duration_rent',
        'immovable_start_contract',
        'immovable_end_contract',
        'immovable_nmonths_contract',
        'cosigner',
    ];

    protected $hidden =
    [
        'updated_at',
    ];

    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    // public function tenant()
    // {
    //     return $this->belongsTo(Tenant::class);
    // }

    // public function immovable()
    // {
    //     return $this->belongsTo(Immovable::class);
    // }

}
