<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalFeature extends Model
{
    use HasUuids, HasFactory;
    protected $table = 'externalfeatures';
    protected $fillable = [
        'description'
    ];
}
