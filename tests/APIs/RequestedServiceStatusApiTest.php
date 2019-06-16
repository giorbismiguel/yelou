<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakeRequestedServiceStatusTrait;
use Tests\ApiTestTrait;

class RequestedServiceStatusApiTest extends TestCase
{
    use MakeRequestedServiceStatusTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_requested_service_status()
    {
        $requestedServiceStatus = $this->fakeRequestedServiceStatusData();
        $this->response = $this->json('POST', '/api/admin/requestedServiceStatuses', $requestedServiceStatus);

        $this->assertApiResponse($requestedServiceStatus);
    }

    /**
     * @test
     */
    public function test_read_requested_service_status()
    {
        $requestedServiceStatus = $this->makeRequestedServiceStatus();
        $this->response = $this->json('GET', '/api/admin/requestedServiceStatuses/'.$requestedServiceStatus->id);

        $this->assertApiResponse($requestedServiceStatus->toArray());
    }

    /**
     * @test
     */
    public function test_update_requested_service_status()
    {
        $requestedServiceStatus = $this->makeRequestedServiceStatus();
        $editedRequestedServiceStatus = $this->fakeRequestedServiceStatusData();

        $this->response = $this->json('PUT', '/api/admin/requestedServiceStatuses/'.$requestedServiceStatus->id, $editedRequestedServiceStatus);

        $this->assertApiResponse($editedRequestedServiceStatus);
    }

    /**
     * @test
     */
    public function test_delete_requested_service_status()
    {
        $requestedServiceStatus = $this->makeRequestedServiceStatus();
        $this->response = $this->json('DELETE', '/api/admin/requestedServiceStatuses/'.$requestedServiceStatus->id);

        $this->assertApiSuccess();
        $this->response = $this->json('GET', '/api/admin/requestedServiceStatuses/'.$requestedServiceStatus->id);

        $this->response->assertStatus(404);
    }
}
