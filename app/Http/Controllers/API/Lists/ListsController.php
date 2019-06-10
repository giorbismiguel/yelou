<?php

namespace App\Http\Controllers\API\Lists;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\PaymentMethodRepository;
use App\Repositories\LicenseTypesRepository;
use App\Repositories\RouteRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ListsController extends Controller
{
    private $licenseTypesRepository;
    private $paymentMethodRepository;
    private $routeRepository;

    public function __construct(
        LicenseTypesRepository $licenseTypesRepository,
        PaymentMethodRepository $paymentMethodRepository,
        RouteRepository $routeRepository
    ) {
        $this->licenseTypesRepository = $licenseTypesRepository;
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->routeRepository = $routeRepository;
    }

    public function __invoke(Request $request)
    {
        $nomenclators = Cache::remember('home-lists', 300, function () {
            $data['licenseTypes'] = $this->licenseTypesRepository->all()->pluck('type', 'id');
            $data['paymentMethods'] = $this->paymentMethodRepository->all()->pluck('name', 'id');
            $data['userRoutes'] = $this->routeRepository->all(['user_id' => \Auth::id()], null, null,
                ['id', 'name'])->pluck('name', 'id');

            return $data;
        });

        return response()->success([
            'data' => $nomenclators,
        ]);
    }

    public function requestServices()
    {
        $query = [];
        $select = ['id', 'name'];
        $data['paymentMethods'] = $this->paymentMethodRepository
            ->all($query, null, null, $select, 'name', 'asc')
            ->toArray();

        $query = ['user_id' => \Auth::id()];
        $select = [
            'id',
            'name',
            'lat_start',
            'lng_start',
            'formatted_address_start',
            'lat_end',
            'lng_end',
            'formatted_address_end'
        ];
        $data['userRoutes'] = $this->routeRepository
            ->all($query, null, null, $select, 'name', 'asc')
            ->toArray();


        return response()->success([
            'data' => $data,
        ]);
    }

}
