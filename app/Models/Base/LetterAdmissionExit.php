<?php
namespace App\Models\Base;

use App\Models\Immovable;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterAdmissionExit extends Model
{

    use HasUuids, HasFactory;

    protected $table = 'letter_admissions_exits';
    protected $fillable = [
        'user_id',
        'tenant_id',
        'immovable_id',
        'file_path',
        'status',
        'type',
    ];

    protected $hidden =
    [
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function immovable()
    {
        return $this->belongsTo(Immovable::class);
    }

}
