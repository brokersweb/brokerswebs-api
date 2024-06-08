<?php

namespace App\Models;

use App\Models\AccountsStatus\AccountStatus;
use App\Models\Base\Favorite;
use App\Models\Base\Gallery;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Immovable extends Model
{
    use HasUuids, HasFactory;
    protected $table = 'immovables';
    protected $hidden = [
        'created_at',
    ];

    protected $fillable = [
        'title',
        'code',
        'main_image',
        'description',
        'sale_price',
        'rent_price',
        'enrollment',
        'video_url',
        'immovabletype_id',
        'owner_id',
        'category',
        'co_ownership',
        'immonumber',
        'co_ownership_id',
        'status',
        'building_company_id',
        'co_adminvalue',
        'image_status',
        'video_status',
        'terms',
        'slug'
    ];


    public function immovableType()
    {
        return $this->belongsTo(ImmovableType::class, 'immovabletype_id');
    }

    public function details()
    {
        return $this->hasOne(ImmovableDetail::class);
    }

    public function address()
    {
        return $this->morphOne(Address::class, 'addressable')->withDefault();
    }

    public function comformts()
    {
        return $this->hasOne(Comformt::class);
    }

    public function buildingCompany()
    {
        return $this->belongsTo(BuildingCompany::class, 'building_company_id');
    }

    public function coownership()
    {
        return $this->belongsTo(Coownership::class, 'co_ownership_id');
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    public function requests()
    {
        return $this->hasMany(ImmovableRequest::class);
    }

    // public function owners()
    // {
    //     return $this->belongsToMany(Owner::class);
    // }

    public function owner()
    {
        return $this->belongsTo(Owner::class, 'owner_id');
    }


    public function tenants()
    {
        return $this->belongsToMany(Tenant::class, 'immovable_tenant');
    }


    public function hasTenant(): bool
    {
        return $this->tenants()->exists();
    }

    public function accountstatus()
    {
        return $this->hasMany(AccountStatus::class, 'immovable_id');
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'modelable');
    }
}
