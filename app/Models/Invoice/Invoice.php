<?php

namespace App\Models\Invoice;

use App\Models\Base\SeveralLog;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Invoice extends Model implements Auditable
{
    use HasFactory, HasUuids, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'xml',
        'doc_file',
        'doc_name',
        'entityable_id',
        'entityable_type',
        'type',
        'sequential',
        'user_id',
        'status',
        'status_dian'
    ];

    protected $auditExclude = [
        'doc_file',
        'xml'
    ];

    // protected $auditInclude = [

    // ];


    public function entityable()
    {
        return $this->morphTo();
    }

    public function logs()
    {
        return $this->morphMany(SeveralLog::class, 'auditable');
    }
}
