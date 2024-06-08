<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Pet
 *
 * @package Petstore30
 *
 * @author  Donii Sergii <doniysa@gmail.com>
 *
 * @OA\Schema(
 *     description="InternalFeature model",
 *     title="InternalFeature model",
 *     required={"description", ""},
 *     @OA\Xml(
 *         name="InternalFeature"
 *     )
 * )
 */
class InternalFeature extends Model
{
    use HasUuids, HasFactory;
    protected $table = 'internalfeatures';
    /**
     * @OA\Property(
     *     description="InternalFeature description",
     *     title="InternalFeature description",
     *     @OA\Xml(
     *         name="InternalFeature",
     *         wrapped=true
     *     ),
     * )
     *
     * @var \InternalFeature\Description[]
     */

    private $description;

    protected $fillable = [
        'description'
    ];
}
