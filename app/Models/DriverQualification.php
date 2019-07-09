<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DriverQualification
 * @package App\Models
 * @version July 9, 2019, 2:02 am UTC
 *
 * @property unsignedBigInteger driver_id
 * @property integer qualification
 */
class DriverQualification extends Model
{
    use SoftDeletes;

    public $table = 'driver_qualifications';
    

    protected $dates = ['deleted_at'];


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
        'id' => 'integer',
        'qualification' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'driver_id' => 'required|integer',
        'qualification' => 'required|integer|min:1|max:5'
    ];

    
}
