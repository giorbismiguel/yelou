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
 * @property float lat
 * @property float lng
 * @property string formatted_address
 */
class Route extends Model
{
    use SoftDeletes;

    public $table = 'routes';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'lat',
        'lng',
        'formatted_address'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'lat' => 'float',
        'lng' => 'float',
        'formatted_address' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|max:191|min:1',
        'lat' => 'required|numeric',
        'lng' => 'required|numeric'
    ];

    
}
