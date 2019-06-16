<?php namespace Tests\Repositories;

use App\RequestedService;
use App\Repositories\RequestedServiceRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakeRequestedServiceTrait;
use Tests\ApiTestTrait;

class RequestedServiceRepositoryTest extends TestCase
{
    use MakeRequestedServiceTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var RequestedServiceRepository
     */
    protected $requestedServiceRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->requestedServiceRepo = \App::make(RequestedServiceRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_requested_service()
    {
        $requestedService = $this->fakeRequestedServiceData();
        $createdRequestedService = $this->requestedServiceRepo->create($requestedService);
        $createdRequestedService = $createdRequestedService->toArray();
        $this->assertArrayHasKey('id', $createdRequestedService);
        $this->assertNotNull($createdRequestedService['id'], 'Created RequestedService must have id specified');
        $this->assertNotNull(RequestedService::find($createdRequestedService['id']), 'RequestedService with given id must be in DB');
        $this->assertModelData($requestedService, $createdRequestedService);
    }

    /**
     * @test read
     */
    public function test_read_requested_service()
    {
        $requestedService = $this->makeRequestedService();
        $dbRequestedService = $this->requestedServiceRepo->find($requestedService->id);
        $dbRequestedService = $dbRequestedService->toArray();
        $this->assertModelData($requestedService->toArray(), $dbRequestedService);
    }

    /**
     * @test update
     */
    public function test_update_requested_service()
    {
        $requestedService = $this->makeRequestedService();
        $fakeRequestedService = $this->fakeRequestedServiceData();
        $updatedRequestedService = $this->requestedServiceRepo->update($fakeRequestedService, $requestedService->id);
        $this->assertModelData($fakeRequestedService, $updatedRequestedService->toArray());
        $dbRequestedService = $this->requestedServiceRepo->find($requestedService->id);
        $this->assertModelData($fakeRequestedService, $dbRequestedService->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_requested_service()
    {
        $requestedService = $this->makeRequestedService();
        $resp = $this->requestedServiceRepo->delete($requestedService->id);
        $this->assertTrue($resp);
        $this->assertNull(RequestedService::find($requestedService->id), 'RequestedService should not exist in DB');
    }
}
