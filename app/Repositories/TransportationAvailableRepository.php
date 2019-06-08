<?php

namespace App\Repositories;

use App\Models\TransportationAvailable;

/**
 * Class TransportationAvailableRepository
 * @package App\Repositories
 * @version June 8, 2019, 1:38 pm UTC
*/

class TransportationAvailableRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'lat',
        'lng',
        'active',
        'user_id'
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
        return TransportationAvailable::class;
    }
}
