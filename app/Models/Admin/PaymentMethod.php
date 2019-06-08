<?php

namespace App\Models\Admin;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PaymentMethod
 * @package App\Models\Admin
 * @version June 8, 2019, 8:23 pm UTC
 *
 * @property string name
 */
class PaymentMethod extends Model
{
    use SoftDeletes;

    public $table = 'payment_methods';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|min:1|max:191'
    ];

    
}
