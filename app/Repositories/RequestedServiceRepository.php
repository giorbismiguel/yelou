<?php

namespace App\Repositories;

use App\Models\RequestedService;
use App\Repositories\BaseRepository;

/**
 * Class RequestedServiceRepository
 * @package App\Repositories
 * @version June 16, 2019, 6:32 pm UTC
*/

class RequestedServiceRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'client_id',
        'transporter_id',
        'route_id'
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
        return RequestedService::class;
    }
}
