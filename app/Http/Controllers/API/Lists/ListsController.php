<?php

namespace App\Http\Controllers\API\Lists;

use App\Http\Controllers\Controller;
use App\Repositories\LicenseTypesRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ListsController extends Controller
{
    private $licenseTypesRepository;

    public function __construct(LicenseTypesRepository $licenseTypesRepository)
    {
        $this->licenseTypesRepository = $licenseTypesRepository;
    }

    public function __invoke(Request $request)
    {
        $nomenclators = Cache::remember('home-lists', 300, function () {
            $data['licenseTypes'] = $this->licenseTypesRepository->all()->pluck('type', 'id');

            return $data;
        });

        return response()->success([
            'data' => $nomenclators,
        ]);
    }
}
