<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class LicenseTypes
 * @package App\Models
 * @version May 22, 2019, 2:19 am UTC
 *
 * @property string type
 * @property string description
 */
class LicenseTypes extends Model
{
    use SoftDeletes;

    public $table = 'license_types';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'type',
        'description'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'type' => 'string',
        'description' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'type' => 'required|max:5',
        'description' => 'required|max:191'
    ];

}
