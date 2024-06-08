<?php

namespace App\Models\Base;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'favorites';

    protected $fillable = [
        'user_id',
        'modelable_id',
        'modelable_type'
    ];

    public function modelable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
