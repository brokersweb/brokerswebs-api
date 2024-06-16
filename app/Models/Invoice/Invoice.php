<?php

namespace App\Models\Invoice;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'xml',
        'doc_file',
        'doc_name',
        'entityable_id',
        'entityable_type',
        'type',
        'sequential',
        'user_id',
        'status'
    ];

    public function entityable()
    {
        return $this->morphTo();
    }
}
