<?php

namespace App\Models\Renting;

use App\Models\Immovable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $table = 'applications';

    protected $fillable = [
        'root_number',
        'immovable_id',
        'applicant_id',
        'status',
        'comment',
        'priority'
    ];

    protected $hidden = [
        'updated_at'
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    public function immovable()
    {
        return $this->belongsTo(Immovable::class);
    }
}
