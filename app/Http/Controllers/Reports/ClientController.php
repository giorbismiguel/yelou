<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Repositories\RequestServicesRepository;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * @var RequestServicesRepository
     */
    private $requestServicesRepo;

    /**
     * ClientController constructor.
     * @param RequestServicesRepository $requestServicesRepo
     */
    public function __construct(RequestServicesRepository $requestServicesRepo)
    {
        $this->requestServicesRepo = $requestServicesRepo;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $id)
    {
        $requestService = $this->requestServicesRepo->find($id);

        return response()->report('reports.client.invoice', compact('requestService'));
    }
}
