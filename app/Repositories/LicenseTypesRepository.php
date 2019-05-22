<?php

namespace App\Repositories;

use App\Models\LicenseTypes;
use App\Repositories\BaseRepository;

/**
 * Class LicenseTypesRepository
 * @package App\Repositories
 * @version May 22, 2019, 2:19 am UTC
*/

class LicenseTypesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'type',
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
        return LicenseTypes::class;
    }
}
