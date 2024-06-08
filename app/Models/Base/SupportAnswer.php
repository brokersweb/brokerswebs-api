<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportAnswer extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'support_id', 'user_id', 'comment', 'evidence'
    ];

    public function support()
    {
        return $this->belongsTo(Support::class);
    }
}
