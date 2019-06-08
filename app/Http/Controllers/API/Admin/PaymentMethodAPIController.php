<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Requests\API\Admin\CreatePaymentMethodAPIRequest;
use App\Http\Requests\API\Admin\UpdatePaymentMethodAPIRequest;
use App\Models\Admin\PaymentMethod;
use App\Repositories\Admin\PaymentMethodRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class PaymentMethodController
 * @package App\Http\Controllers\API\Admin
 */

class PaymentMethodAPIController extends AppBaseController
{
    /** @var  PaymentMethodRepository */
    private $paymentMethodRepository;

    public function __construct(PaymentMethodRepository $paymentMethodRepo)
    {
        $this->paymentMethodRepository = $paymentMethodRepo;
    }

    /**
     * Display a listing of the PaymentMethod.
     * GET|HEAD /paymentMethods
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $paymentMethods = $this->paymentMethodRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($paymentMethods->toArray(), 'Payment Methods retrieved successfully');
    }

    /**
     * Store a newly created PaymentMethod in storage.
     * POST /paymentMethods
     *
     * @param CreatePaymentMethodAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatePaymentMethodAPIRequest $request)
    {
        $input = $request->all();

        $paymentMethod = $this->paymentMethodRepository->create($input);

        return $this->sendResponse($paymentMethod->toArray(), 'Payment Method saved successfully');
    }

    /**
     * Display the specified PaymentMethod.
     * GET|HEAD /paymentMethods/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var PaymentMethod $paymentMethod */
        $paymentMethod = $this->paymentMethodRepository->find($id);

        if (empty($paymentMethod)) {
            return $this->sendError('Payment Method not found');
        }

        return $this->sendResponse($paymentMethod->toArray(), 'Payment Method retrieved successfully');
    }

    /**
     * Update the specified PaymentMethod in storage.
     * PUT/PATCH /paymentMethods/{id}
     *
     * @param int $id
     * @param UpdatePaymentMethodAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePaymentMethodAPIRequest $request)
    {
        $input = $request->all();

        /** @var PaymentMethod $paymentMethod */
        $paymentMethod = $this->paymentMethodRepository->find($id);

        if (empty($paymentMethod)) {
            return $this->sendError('Payment Method not found');
        }

        $paymentMethod = $this->paymentMethodRepository->update($input, $id);

        return $this->sendResponse($paymentMethod->toArray(), 'PaymentMethod updated successfully');
    }

    /**
     * Remove the specified PaymentMethod from storage.
     * DELETE /paymentMethods/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var PaymentMethod $paymentMethod */
        $paymentMethod = $this->paymentMethodRepository->find($id);

        if (empty($paymentMethod)) {
            return $this->sendError('Payment Method not found');
        }

        $paymentMethod->delete();

        return $this->sendResponse($id, 'Payment Method deleted successfully');
    }
}
