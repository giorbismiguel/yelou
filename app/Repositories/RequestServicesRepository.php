<?php

namespace App\Repositories;

use App\RequestServices;
use App\Repositories\BaseRepository;

/**
 * Class RequestServicesRepository
 * @package App\Repositories
 * @version June 8, 2019, 9:45 pm UTC
 */
class RequestServicesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'route_id',
        'name_start',
        'lat_start',
        'lng_start',
        'name_end',
        'lat_end',
        'lng_end',
        'start_time',
        'payment_method_id'
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
        return RequestServices::class;
    }
}
