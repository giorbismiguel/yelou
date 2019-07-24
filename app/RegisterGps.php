<?php

namespace App;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RegisterGps
 * @package App\Models
 * @version June 20, 2019, 10:45 am UTC
 *
 * @property float              lat
 * @property float              lng
 * @property unsignedBigInteger driver_id
 * @property unsignedBigInteger service_id
 * @property datetime           registered_at
 */
class RegisterGps extends Model
{
    use SoftDeletes;

    public $table = 'register_gps';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'lat',
        'lng',
        'driver_id',
        'service_id',
        'registered_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        '"deleted_at',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'            => 'integer',
        'lat'           => 'double',
        'lng'           => 'double',
        'driver_id'     => 'integer',
        'service_id'    => 'integer',
        'registered_at' => 'datetime:d/m/Y H:i:s'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'coordinates' => 'required|array',
    ];

    /* ========================================================================= *\
     * Relations
    \* ========================================================================= */

    /**
     * @return BelongsTo
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    /**
     * @return BelongsTo
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(RequestServices::class, 'service_id');
    }
}
