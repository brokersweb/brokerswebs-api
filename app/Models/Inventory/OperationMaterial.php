<?php

namespace App\Models\Inventory;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use OwenIt\Auditing\Contracts\Auditable;

class OperationMaterial extends Model implements Auditable
{
    use HasUuids, HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable =
    [
        'code',
        'user_id',
        'info',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
