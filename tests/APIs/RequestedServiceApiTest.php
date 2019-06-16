<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakeRequestedServiceTrait;
use Tests\ApiTestTrait;

class RequestedServiceApiTest extends TestCase
{
    use MakeRequestedServiceTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_requested_service()
    {
        $requestedService = $this->fakeRequestedServiceData();
        $this->response = $this->json('POST', '/api/requestedServices', $requestedService);

        $this->assertApiResponse($requestedService);
    }

    /**
     * @test
     */
    public function test_read_requested_service()
    {
        $requestedService = $this->makeRequestedService();
        $this->response = $this->json('GET', '/api/requestedServices/'.$requestedService->id);

        $this->assertApiResponse($requestedService->toArray());
    }

    /**
     * @test
     */
    public function test_update_requested_service()
    {
        $requestedService = $this->makeRequestedService();
        $editedRequestedService = $this->fakeRequestedServiceData();

        $this->response = $this->json('PUT', '/api/requestedServices/'.$requestedService->id, $editedRequestedService);

        $this->assertApiResponse($editedRequestedService);
    }

    /**
     * @test
     */
    public function test_delete_requested_service()
    {
        $requestedService = $this->makeRequestedService();
        $this->response = $this->json('DELETE', '/api/requestedServices/'.$requestedService->id);

        $this->assertApiSuccess();
        $this->response = $this->json('GET', '/api/requestedServices/'.$requestedService->id);

        $this->response->assertStatus(404);
    }
}
