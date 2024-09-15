<?php

namespace App\Models\Inventory;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class InventoryPurchase extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'inventory_purchases';

    protected $fillable = [
        'user_id',
        'user_approved_id',
        'supplier_id',
        'serial',
        'observation',
        'invoice',
        'total',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userApproved()
    {
        return $this->belongsTo(User::class, 'user_approved_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function status()
    {
        switch ($this->status) {

            case 'approved':
                return 'Abrobado';
                break;

            case 'rejected':
                return 'Rechazado';
                break;

            default:
                return 'Pendiente';
                break;
        }
    }

    public function details()
    {
        return $this->hasMany(InventoryPurchaseDetail::class);
    }
}
