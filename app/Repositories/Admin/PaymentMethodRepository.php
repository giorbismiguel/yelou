<?php

namespace App\Repositories\Admin;

use App\Models\Admin\PaymentMethod;
use App\Repositories\BaseRepository;

/**
 * Class PaymentMethodRepository
 * @package App\Repositories\Admin
 * @version June 8, 2019, 8:23 pm UTC
*/

class PaymentMethodRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PaymentMethod::class;
    }
}
