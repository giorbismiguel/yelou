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

    protected $table = 'requested_services';

    protected $dates = [
        'deleted_at',
        'start_at',
        'end_at',
    ];

    protected $with = [
        'client:id,name,phone',
        'service:id,name_start,name_end,start_date,start_time',
        'status:id,name',
        'transporter:id,first_name,last_name,phone,photo',
    ];

    protected $fillable = [
        'client_id',
        'transporter_id',
        'service_id',
        'status_id',
        'created_at',
        'updated_at',
        'start_at',
        'end_at',
    ];

    protected $hidden = [
        'updated_at',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'         => 'integer',
        'start_at'   => 'datetime:d/m/Y H:i:s',
        'end_at'     => 'datetime:d/m/Y H:i:s',
        'created_at' => 'datetime:d/m/Y H:i:s',
        'updated_at' => 'datetime:d/m/Y H:i:s',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'client_id'      => 'nullable|integer',
        'transporter_id' => 'nullable|integer',
        'service_id'     => 'nullable|integer',
        'status_id'      => 'nullable|integer',
        'start_at'       => 'nullable|datetime:d/m/Y H:i:s',
        'end_at'         => 'nullable|datetime:d/m/Y H:i:s',
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

    /* ========================================================================= *\
     * Methods
    \* ========================================================================= */

    public function setStatusStarted()
    {
        $this->update([
            'status_id' => 4,
        ]);
    }

    public function setStatusCompleted()
    {
        $this->update([
            'status_id' => 5,
        ]);
    }
}
