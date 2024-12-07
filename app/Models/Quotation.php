<?php

namespace App\Models;

use App\Models\Inventory\ServiceOrder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasUuids;

    protected $fillable = [
        'order_service_id',
        'status',
        'created_by',
        'service_vat',
        'total_service',
        'material_vat',
        'total_material',
        'tax_name',
        'tax_value',
        'general_housekeeping',
        'labor',
        'operating_expenses',
        'extra_helpers',
        'transportation',
        'discount',
        'total_discount',
        'total',
        'subtotal',
        'notes'
    ];


    public function order()
    {
        return $this->belongsTo(ServiceOrder::class, 'order_service_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
