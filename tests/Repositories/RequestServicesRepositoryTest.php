<?php namespace Tests\Repositories;

use App\RequestServices;
use App\Repositories\RequestServicesRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakeRequestServicesTrait;
use Tests\ApiTestTrait;

class RequestServicesRepositoryTest extends TestCase
{
    use MakeRequestServicesTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var RequestServicesRepository
     */
    protected $requestServicesRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->requestServicesRepo = \App::make(RequestServicesRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_request_services()
    {
        $requestServices = $this->fakeRequestServicesData();
        $createdRequestServices = $this->requestServicesRepo->create($requestServices);
        $createdRequestServices = $createdRequestServices->toArray();
        $this->assertArrayHasKey('id', $createdRequestServices);
        $this->assertNotNull($createdRequestServices['id'], 'Created RequestServices must have id specified');
        $this->assertNotNull(RequestServices::find($createdRequestServices['id']), 'RequestServices with given id must be in DB');
        $this->assertModelData($requestServices, $createdRequestServices);
    }

    /**
     * @test read
     */
    public function test_read_request_services()
    {
        $requestServices = $this->makeRequestServices();
        $dbRequestServices = $this->requestServicesRepo->find($requestServices->id);
        $dbRequestServices = $dbRequestServices->toArray();
        $this->assertModelData($requestServices->toArray(), $dbRequestServices);
    }

    /**
     * @test update
     */
    public function test_update_request_services()
    {
        $requestServices = $this->makeRequestServices();
        $fakeRequestServices = $this->fakeRequestServicesData();
        $updatedRequestServices = $this->requestServicesRepo->update($fakeRequestServices, $requestServices->id);
        $this->assertModelData($fakeRequestServices, $updatedRequestServices->toArray());
        $dbRequestServices = $this->requestServicesRepo->find($requestServices->id);
        $this->assertModelData($fakeRequestServices, $dbRequestServices->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_request_services()
    {
        $requestServices = $this->makeRequestServices();
        $resp = $this->requestServicesRepo->delete($requestServices->id);
        $this->assertTrue($resp);
        $this->assertNull(RequestServices::find($requestServices->id), 'RequestServices should not exist in DB');
    }
}
