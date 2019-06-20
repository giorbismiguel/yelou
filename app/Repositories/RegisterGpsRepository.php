<?php

namespace App\Repositories;

use App\Models\RegisterGps;
use App\Repositories\BaseRepository;

/**
 * Class RegisterGpsRepository
 * @package App\Repositories
 * @version June 20, 2019, 10:45 am UTC
 */
class RegisterGpsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'lat',
        'lng',
        'driver_id',
        'service_id',
        'start_time',
        'end_time',
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
        return RegisterGps::class;
    }
}
