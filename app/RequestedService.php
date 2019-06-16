<?php

namespace App;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RequestedService
 * @package App\Models
 * @version June 16, 2019, 6:32 pm UTC
 *
 * @property unsignedBigInteger client_id
 * @property unsignedBigInteger transporter_id
 * @property unsignedBigInteger route_id
 */
class RequestedService extends Model
{
    use SoftDeletes;

    public $table = 'requested_services';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'client_id',
        'transporter_id',
        'service_id',
        'status_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'client_id'      => 'required|integer',
        'transporter_id' => 'required|integer',
        'service_id'     => 'required|integer',
        'status_id'      => 'required|integer',
    ];
}
