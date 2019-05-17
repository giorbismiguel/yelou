<?php

namespace App\Models\Admin;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TransportationStates
 * @package App\Models\Admin
 * @version May 17, 2019, 4:50 pm UTC
 *
 * @property string name
 * @property string description
 */
class TransportationStates extends Model
{
    use SoftDeletes;

    public $table = 'transportation_states';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'description'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'description' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|max:191',
        'description' => 'required'
    ];

    
}
