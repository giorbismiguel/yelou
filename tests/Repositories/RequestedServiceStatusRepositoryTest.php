<?php namespace Tests\Repositories;

use App\Models\Admin\RequestedServiceStatus;
use App\Repositories\Admin\RequestedServiceStatusRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakeRequestedServiceStatusTrait;
use Tests\ApiTestTrait;

class RequestedServiceStatusRepositoryTest extends TestCase
{
    use MakeRequestedServiceStatusTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var RequestedServiceStatusRepository
     */
    protected $requestedServiceStatusRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->requestedServiceStatusRepo = \App::make(RequestedServiceStatusRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_requested_service_status()
    {
        $requestedServiceStatus = $this->fakeRequestedServiceStatusData();
        $createdRequestedServiceStatus = $this->requestedServiceStatusRepo->create($requestedServiceStatus);
        $createdRequestedServiceStatus = $createdRequestedServiceStatus->toArray();
        $this->assertArrayHasKey('id', $createdRequestedServiceStatus);
        $this->assertNotNull($createdRequestedServiceStatus['id'], 'Created RequestedServiceStatus must have id specified');
        $this->assertNotNull(RequestedServiceStatus::find($createdRequestedServiceStatus['id']), 'RequestedServiceStatus with given id must be in DB');
        $this->assertModelData($requestedServiceStatus, $createdRequestedServiceStatus);
    }

    /**
     * @test read
     */
    public function test_read_requested_service_status()
    {
        $requestedServiceStatus = $this->makeRequestedServiceStatus();
        $dbRequestedServiceStatus = $this->requestedServiceStatusRepo->find($requestedServiceStatus->id);
        $dbRequestedServiceStatus = $dbRequestedServiceStatus->toArray();
        $this->assertModelData($requestedServiceStatus->toArray(), $dbRequestedServiceStatus);
    }

    /**
     * @test update
     */
    public function test_update_requested_service_status()
    {
        $requestedServiceStatus = $this->makeRequestedServiceStatus();
        $fakeRequestedServiceStatus = $this->fakeRequestedServiceStatusData();
        $updatedRequestedServiceStatus = $this->requestedServiceStatusRepo->update($fakeRequestedServiceStatus, $requestedServiceStatus->id);
        $this->assertModelData($fakeRequestedServiceStatus, $updatedRequestedServiceStatus->toArray());
        $dbRequestedServiceStatus = $this->requestedServiceStatusRepo->find($requestedServiceStatus->id);
        $this->assertModelData($fakeRequestedServiceStatus, $dbRequestedServiceStatus->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_requested_service_status()
    {
        $requestedServiceStatus = $this->makeRequestedServiceStatus();
        $resp = $this->requestedServiceStatusRepo->delete($requestedServiceStatus->id);
        $this->assertTrue($resp);
        $this->assertNull(RequestedServiceStatus::find($requestedServiceStatus->id), 'RequestedServiceStatus should not exist in DB');
    }
}
