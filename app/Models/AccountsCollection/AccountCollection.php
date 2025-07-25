<?php

namespace App\Models\AccountsCollection;

use App\Models\AccountsStatus\PreviousBalance;
use App\Models\Immovable;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountCollection extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $table = 'accounts_collection';
    protected $fillable = [
        'immovable_id',
        'tenant_id',
        'contract_number',
        'month',
        'year',
        'expiration_date',
        'amount',
        'amount_vat',
        'amount_retention',
        'items',
        'amount_in_letters',
        'terms_payment',
        'payment_observation',
        'observation',
        'amount_paid',
        'status',
        'voucher',
        'vouchernum'
    ];

    public function immovable()
    {
        return $this->belongsTo(Immovable::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function details()
    {
        return $this->hasMany(AccountCollectionDetail::class, 'accountscollection_id');
    }

    public function getStatus($statu)
    {
        $sta = '';

        switch ($statu) {
            case "created":
                $sta = 'Creado';
                break;
            case "send":
                $sta = 'Enviado';
                break;
            case "pending_payment":
                $sta = 'Pendiente de pago';
                break;
            case "paid":
                $sta = 'Pagado';
                break;
            case "partially_paid":
                $sta = 'Pago parcial';
                break;
            case "overdue":
                $sta = 'Vencido';
                break;
            case "cancelled":
                $sta = 'Cancelada';
                break;
        }

        return $sta;
    }

    public function previousbalance()
    {
        return $this->belongsTo(PreviousBalance::class, 'accountable');
    }
}
