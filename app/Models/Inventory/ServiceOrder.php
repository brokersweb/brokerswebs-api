<?php

namespace App\Models\Inventory;

use App\Models\Immovable;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ServiceOrder extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'code',
        'user_id',
        'assigned_id',
        'client_id',
        'client_type',
        'type',
        'status',
        'tenant_id',
        'request',
        'progress',
        'notes',
        'start_date',
        'start_time',
    ];

    public function services()
    {
        return $this->hasMany(ServiceOrderDetail::class);
    }

    public function status()
    {
        switch ($this->status) {
            case 'in_progess':
                return 'En proceso';
                break;

            case 'completed':
                return 'Completado';
                break;
            default:
                return 'Abierto';
                break;
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function staff()
    {
        return $this->belongsTo(User::class, 'assigned_id');
    }

    public function client()
    {
        return $this->belongsTo(InventoryClient::class, 'client_id');
    }

    public function immovable()
    {
        return $this->belongsTo(Immovable::class, 'client_id');
    }
    public function tenant()
    {
        return $this->hasOne(Tenant::class, 'tenant_id');
    }

    public function evidences()
    {
        return $this->morphMany(InventoryImage::class, 'entityable');
    }


    public function consumes()
    {
        return $this->hasMany(InventoryConsumableMaterial::class, 'service_order_id');
    }
}
