<?php

namespace App\Repositories;

use App\Route;
use App\Repositories\BaseRepository;

/**
 * Class RouteRepository
 * @package App\Repositories
 * @version May 21, 2019, 8:52 pm UTC
 */
class RouteRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'lat_start',
        'lng_start',
        'formatted_address_start',
        'lat_end',
        'lng_end',
        'formatted_address_end',
        'user_id',
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
        return Route::class;
    }
}
