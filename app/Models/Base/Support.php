<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Support extends Model
{
    use HasUuids, HasFactory;


    protected $fillable = [
        'fullname', 'email', 'cellphone', 'message', 'status'
    ];

    protected $hidden = [
        'updated_at'
    ];

    public function answers()
    {
        return $this->hasMany(SupportAnswer::class);
    }
}
