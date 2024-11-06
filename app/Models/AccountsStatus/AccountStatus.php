<?php

namespace App\Models\AccountsStatus;

use App\Models\Base\SeveralLog;
use App\Models\Immovable;
use App\Models\Owner;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class AccountStatus extends Model implements Auditable
{
    use HasUuids, HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'account_status';
    protected $fillable = [
        'immovable_id',
        'owner_id',
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
        'status'
    ];

    public function immovable()
    {
        return $this->belongsTo(Immovable::class);
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function details()
    {
        return $this->hasMany(AccountStatusDetail::class, 'accountstatus_id');
    }

    public function balance()
    {
        return $this->belongsTo(PreviousBalance::class);
    }

    public function logs()
    {
        return $this->morphMany(SeveralLog::class, 'auditable');
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


}
