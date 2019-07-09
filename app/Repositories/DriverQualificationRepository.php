<?php

namespace App\Repositories;

use App\Models\DriverQualification;
use App\Repositories\BaseRepository;

/**
 * Class DriverQualificationRepository
 * @package App\Repositories
 * @version July 9, 2019, 2:02 am UTC
*/

class DriverQualificationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'driver_id',
        'qualification'
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
        return DriverQualification::class;
    }
}
