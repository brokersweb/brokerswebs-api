<?php

namespace App\Models\Invoice;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConfigurationInvoice extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'configuration_invoices';

    protected $fillable = [
        'authorization_number',
        'authorization_date',
        'date_issue',
        'prefix',
        'start_number',
        'end_number',
        'validity',
        'cufe',
        'vat',
        'retention'
    ];

    public function invoice()
    {
        return $this->hasMany(Invoice::class);
    }
}
