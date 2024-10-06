<?php

namespace App\Models\Inventory;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryEntrance extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'code',
        'user_id',
        'supplier_id',
        'invoice',
        'status',
        'confirmed_at',
        'confirmed_by',
        'cancelled_at',
        'cancelled_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function status()
    {
        switch ($this->status) {

            case 'confirmed':
                return 'Confirmada';
                break;

            case 'cancelled':
                return 'Cancelada';
                break;

            default:
                return 'Por confirmar';
                break;
        }
    }
}
