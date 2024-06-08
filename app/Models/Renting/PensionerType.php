<?php

namespace App\Models\Renting;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PensionerType extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'pensioners_type';

    protected $fillable = [
        'certificate', 'payment_stubs',
        'cosigner_id',
        'entity_type',
        'entity_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function entity()
    {
        return $this->morphTo();
    }
}
