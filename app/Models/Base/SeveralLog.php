<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeveralLog extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'event',
        'auditable_id',
        'auditable_type',
        'user_id',
        'description',
        'url',
        'ip_address',
        'user_agent'
    ];

    public function auditable()
    {
        return $this->morphTo();
    }
}
