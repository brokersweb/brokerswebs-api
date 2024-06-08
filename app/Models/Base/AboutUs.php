<?php

namespace App\Models\Base;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'about_us';

    protected $fillable = [
        'fullname',
        'position',
        'profession',
        'email',
        'phone',
        'description',
        'avatar',
    ];
}
