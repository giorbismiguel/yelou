<?php

namespace App;

use App\Models\Admin\RequestedServiceStatus;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    protected $with = [
        'client:id,name,phone',
        'service:id,name_start,name_end',
        'status:id,name',
        'transporter:id,name',
    ];

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
        'id'         => 'integer',
        'created_at' => 'datetime: d/m/Y H:i:s',
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

    /* ========================================================================= *\
     * Relations
    \* ========================================================================= */

    /**
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * @return BelongsTo
     */
    public function transporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'transporter_id');
    }

    /**
     * @return BelongsTo
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(RequestServices::class, 'service_id');
    }

    /**
     * @return BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(RequestedServiceStatus::class, 'status_id');
    }
}
