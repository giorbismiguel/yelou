<?php

namespace App;

use App\Models\Admin\PaymentMethod;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RequestServices
 * @package App\Models
 * @version June 8, 2019, 9:45 pm UTC
 *
 * @property unsignedBigInteger    route_id
 * @property string                name_start
 * @property float                 lat_start
 * @property float                 lng_start
 * @property string                name_end
 * @property float                 lat_end
 * @property float                 lng_end
 * @property string|\Carbon\Carbon start_date
 * @property string|\Carbon\Carbon start_time
 * @property unsignedBigInteger    payment_method_id
 * @property unsignedBigInteger    user_id
 */
class RequestServices extends Model
{
    use SoftDeletes;

    public $table = 'request_services';

    protected $dates = ['deleted_at'];

    protected $with = [
        'route:id,name',
        'paymentMethod:id,name',
        'user:id,first_name,last_name,phone,email',
    ];

    public $fillable = [
        'route_id',
        'name_start',
        'lat_start',
        'lng_start',
        'name_end',
        'lat_end',
        'lng_end',
        'start_date',
        'start_time',
        'payment_method_id',
        'user_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'         => 'integer',
        'name_start' => 'string',
        'lat_start'  => 'double',
        'lng_start'  => 'double',
        'name_end'   => 'string',
        'lat_end'    => 'double',
        'lng_end'    => 'double',
        'start_date' => 'date:d/m/Y',
        'start_time' => 'time',
        'created_at' => 'datetime:d/m/Y H:i:s',
        'updated_at' => 'datetime:d/m/Y H:i:s',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'route_id'          => 'nullable|integer',
        'start_date'        => 'nullable|date_format:d/m/Y',
        'start_time'        => 'nullable|date_format:H:i:s',
        'name_start'        => 'required|min:1|max:191',
        'lat_start'         => 'required|numeric',
        'lng_start'         => 'required|numeric',
        'name_end'          => 'required|min:1|max:191',
        'lat_end'           => 'required|numeric',
        'lng_end'           => 'required|numeric',
        'payment_method_id' => 'required|integer',
    ];

    /* ========================================================================= *\
     * Relations
    \* ========================================================================= */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function requestedServices()
    {
        return $this->hasMany(RequestedService::class, 'service_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
