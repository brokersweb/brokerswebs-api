<?php

namespace App\Models\Inventory;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ToolLoan extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'service_order_id',
        'user_id',
        'assigned_id',
        'loan_date',
        'expected_return_date',
        'actual_return_date',
        'status',
        'code',
        'notes',
    ];

    public function details()
    {
        return $this->hasOne(ToolLoanDetail::class);
    }

    public function serviceOrder()
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assigned()
    {
        return $this->belongsTo(User::class, 'assigned_id');
    }
}


class ToolLoanDetail extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'tool_loan_details';

    protected $fillable = [
        'tool_loan_id',
        'tool_id',
        'qty'
    ];

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }

    public function evidences()
    {
        return $this->morphMany(InventoryImage::class, 'entityable');
    }
}
