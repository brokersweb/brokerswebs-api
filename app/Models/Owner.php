<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'owners';
    protected $fillable = [
        'name',
        'lastname',
        'document_type',
        'dni',
        'expedition_place',
        'expedition_date',
        'email',
        'cellphone',
        'phone',
        'address',
        'bank_account',
        'birthdate',
        'gender',
        'rut',
        'nit',
        'type',
        'civil_status',
        'dependent_people',
        'profession',
        'dni_file',
        'photo',
        'comment',
    ];

    protected $hidden = [
        'updated_at',
    ];

    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->lastname;
    }

    public function getCreatedAt()
    {
        return $this->created_at->format('d/m/Y');
    }
    public function immovables()
    {
        return $this->belongsToMany(Immovable::class, 'immovable_owner', 'immovable_id', 'owner_id');
    }
}
