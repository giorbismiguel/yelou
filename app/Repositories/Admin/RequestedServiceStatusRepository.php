<?php

namespace App\Repositories\Admin;

use App\Models\Admin\RequestedServiceStatus;
use App\Repositories\BaseRepository;

/**
 * Class RequestedServiceStatusRepository
 * @package App\Repositories\Admin
 * @version June 16, 2019, 7:09 pm UTC
 */
class RequestedServiceStatusRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description'
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
        return RequestedServiceStatus::class;
    }
}
