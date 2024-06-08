<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuildingCompany extends Model
{
    use HasUuids, HasFactory;
    protected $table = 'building_companies';

    protected $fillable = [
        'name',
        'nit',
        'phone',
        'cellphone',
        'email',
        'url_website',
    ];

    protected $hidden =
    [
        'created_at',
        'updated_at',
    ];

    public function immovable()
    {
        return $this->hasMany(Immovable::class);
    }

    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }

}
