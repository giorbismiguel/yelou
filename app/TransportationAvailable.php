<?php

namespace App;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TransportationAvailable
 * @package App\Models
 * @version June 8, 2019, 1:38 pm UTC
 *
 * @property float   lat
 * @property float   lng
 * @property boolean active
 * @property integer user_id
 */
class TransportationAvailable extends Model
{

    public $table = 'transportation_availables';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'lat',
        'lng',
        'active',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'      => 'integer',
        'lat'     => 'double',
        'lng'     => 'double',
        'active'  => 'boolean',
        'user_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'lat'     => 'required|numeric',
        'lng'     => 'required|numeric',
        'active'  => 'required|boolean',
        'user_id' => 'required|integer|unique:transportation_availables'
    ];

    /* ========================================================================= *\
     * Relations
    \* ========================================================================= */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
