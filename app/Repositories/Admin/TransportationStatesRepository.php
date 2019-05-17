<?php

namespace App\Repositories\Admin;

use App\Models\Admin\TransportationStates;
use App\Repositories\BaseRepository;

/**
 * Class TransportationStatesRepository
 * @package App\Repositories\Admin
 * @version May 17, 2019, 4:50 pm UTC
*/

class TransportationStatesRepository extends BaseRepository
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
        return TransportationStates::class;
    }
}
