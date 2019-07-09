<?php

namespace App;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DriverQualification
 * @package App\Models
 * @version July 9, 2019, 2:02 am UTC
 *
 * @property unsignedBigInteger driver_id
 * @property integer            qualification
 */
class DriverQualification extends Model
{
    use SoftDeletes;

    public $table = 'driver_qualifications';

    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public $fillable = [
        'driver_id',
        'qualification'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'            => 'integer',
        'qualification' => 'integer',
        'created_at'    => 'date_format:d/m/Y H:i:s',
        'updated_at'    => 'date_format:d/m/Y H:i:s',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'driver_id'     => 'required|integer',
        'qualification' => 'required|integer|min:1|max:5'
    ];

    /* ========================================================================= *\
     * Relations
    \* ========================================================================= */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}
