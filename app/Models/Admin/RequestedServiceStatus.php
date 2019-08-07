<?php

namespace App\Models\Admin;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RequestedServiceStatus
 * @package App\Models\Admin
 * @version June 16, 2019, 7:09 pm UTC
 *
 * @property string name
 * @property string description
 */
class RequestedServiceStatus extends Model
{
    use SoftDeletes;

    public $table = 'requested_service_statuses';

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
        'id'          => 'integer',
        'name'        => 'string',
        'description' => 'string',
        'created_at'  => 'datetime:d/m/Y H:i:s',
        'updated_at'  => 'datetime:d/m/Y H:i:s',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name'        => 'required|min:1|max:191',
        'description' => 'required|min:1'
    ];

}
