<?php

namespace App\Repositories;

use App\RequestServices;

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
        'start_date',
        'payment_method_id',
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
        return RequestServices::class;
    }

    public function allRequestServices(
        $search = [],
        $skip = null,
        $limit = null,
        $columns = ['*'],
        $orderBy = null,
        $sortBy = null
    ) {
        /**@var $query \Illuminate\Database\Eloquent\Builder */
        $query = $this->allQuery($search, $skip, $limit);

        if ($orderBy) {
            $query->orderBy($orderBy, $sortBy);
        } else {
            $query->latest();
        }

        return $query->doesntHave('requestedServices')->get($columns);
    }
}
