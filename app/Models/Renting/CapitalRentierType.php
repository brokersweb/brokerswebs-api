<?php

namespace App\Models\Renting;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapitalRentierType extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'capitalsrentier_type';

    protected $fillable = [
        'certificate',
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
