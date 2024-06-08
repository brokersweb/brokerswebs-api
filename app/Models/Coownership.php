<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coownership extends Model
{
    use HasUuids, HasFactory;
    protected $table = 'coownerships';
    protected $fillable = [
        'name',
        'nit',
        'phone',
        'cellphone',
        'email',
    ];

    protected $hidden =
    [
        'created_at',
        'updated_at',
    ];

    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function detail()
    {
        return $this->hasOne(CoownershipDetail::class);
    }

    public function immovables()
    {
        return $this->hasMany(Immovable::class, 'co_ownership_id');
    }
}
