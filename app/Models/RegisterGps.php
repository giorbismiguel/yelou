<?php

namespace App\Models;

use Eloquent as Model;
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
 * @property datetime           start_time
 * @property datetime           end_time
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
        'start_time',
        'end_time'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'         => 'integer',
        'lat'        => 'double',
        'lng'        => 'double',
        'start_time' => 'datetime:d/m/Y H:i:s',
        'end_time'   => 'datetime:d/m/Y H:i:s'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'lat'        => 'required|numeric',
        'lng'        => 'required|numeric',
        'driver_id'  => 'required|integer',
        'service_id' => 'required|integer',
        'start_time' => 'required|date_format:d/m/Y H:i:s',
        'end_time'   => 'required|date_format:d/m/Y H:i:s'
    ];
}
