<?php

namespace App;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Route
 * @package App\Models
 * @version May 21, 2019, 8:52 pm UTC
 *
 * @property string name
 * @property float  lat
 * @property float  lng
 * @property string formatted_address
 */
class Route extends Model
{
    use SoftDeletes;

    public $table = 'routes';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'lat_start',
        'lng_start',
        'formatted_address_start',
        'lat_end',
        'lng_end',
        'formatted_address_end',
        'user_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                      => 'integer',
        'name'                    => 'string',
        'lat_start'               => 'double',
        'lng_start'               => 'double',
        'formatted_address_start' => 'string',
        'lat_end'                 => 'double',
        'lng_end'                 => 'double',
        'formatted_address_end'   => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name'                    => 'required|max:191|min:1',
        'lat_start'               => 'required|numeric',
        'lng_start'               => 'required|numeric',
        'formatted_address_start' => 'required|max:191|min:1',
        'lat_end'                 => 'required|numeric',
        'lng_end'                 => 'required|numeric',
        'formatted_address_end'   => 'required|max:191|min:1'
    ];

}
