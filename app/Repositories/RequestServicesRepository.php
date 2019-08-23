<?php

namespace App\Repositories;

use App\RequestServices;
use Illuminate\Database\Eloquent\Builder;

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
            $query->latest('request_services.created_at');
        }

        if (isset($search['date_init']) && $search['date_init'] && isset($search['date_end']) && $search['date_end']) {
            $query->whereBetween('request_services.created_at', [$search['date_init'], $search['date_end']]);
        }

        $query->whereDoesntHave('requestedServices', function (Builder $query) {
            $query->where('transporter_id', '=', \Auth::id())
                ->orWhere('status_id', '=', 2)// 2- Accepted
                ->orWhere('status_id', '=', 4)// 4- Initialized
                ->orWhere('status_id', '=', 5); // 5- Ended
        });

        return $query->get($columns);
    }
}
