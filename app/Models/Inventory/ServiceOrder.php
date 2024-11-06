<?php

namespace App\Models\Inventory;

use App\Models\Immovable;
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
        'client_type',
        'client_id',
        'exter_client',
        'status',
        'comment',
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
        return $this->belongsTo(Immovable::class, 'client_id');
    }


    public function evidences()
    {
        return $this->morphMany(InventoryImage::class, 'entityable');
    }
}
