<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakeRequestServicesTrait;
use Tests\ApiTestTrait;

class RequestServicesApiTest extends TestCase
{
    use MakeRequestServicesTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_request_services()
    {
        $requestServices = $this->fakeRequestServicesData();
        $this->response = $this->json('POST', '/api/requestServices', $requestServices);

        $this->assertApiResponse($requestServices);
    }

    /**
     * @test
     */
    public function test_read_request_services()
    {
        $requestServices = $this->makeRequestServices();
        $this->response = $this->json('GET', '/api/requestServices/'.$requestServices->id);

        $this->assertApiResponse($requestServices->toArray());
    }

    /**
     * @test
     */
    public function test_update_request_services()
    {
        $requestServices = $this->makeRequestServices();
        $editedRequestServices = $this->fakeRequestServicesData();

        $this->response = $this->json('PUT', '/api/requestServices/'.$requestServices->id, $editedRequestServices);

        $this->assertApiResponse($editedRequestServices);
    }

    /**
     * @test
     */
    public function test_delete_request_services()
    {
        $requestServices = $this->makeRequestServices();
        $this->response = $this->json('DELETE', '/api/requestServices/'.$requestServices->id);

        $this->assertApiSuccess();
        $this->response = $this->json('GET', '/api/requestServices/'.$requestServices->id);

        $this->response->assertStatus(404);
    }
}
