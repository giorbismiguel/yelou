<?php namespace Tests\Repositories;

use App\Models\TransportationAvailable;
use App\Repositories\TransportationAvailableRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakeTransportationAvailableTrait;
use Tests\ApiTestTrait;

class TransportationAvailableRepositoryTest extends TestCase
{
    use MakeTransportationAvailableTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var TransportationAvailableRepository
     */
    protected $transportationAvailableRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->transportationAvailableRepo = \App::make(TransportationAvailableRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_transportation_available()
    {
        $transportationAvailable = $this->fakeTransportationAvailableData();
        $createdTransportationAvailable = $this->transportationAvailableRepo->create($transportationAvailable);
        $createdTransportationAvailable = $createdTransportationAvailable->toArray();
        $this->assertArrayHasKey('id', $createdTransportationAvailable);
        $this->assertNotNull($createdTransportationAvailable['id'], 'Created TransportationAvailable must have id specified');
        $this->assertNotNull(TransportationAvailable::find($createdTransportationAvailable['id']), 'TransportationAvailable with given id must be in DB');
        $this->assertModelData($transportationAvailable, $createdTransportationAvailable);
    }

    /**
     * @test read
     */
    public function test_read_transportation_available()
    {
        $transportationAvailable = $this->makeTransportationAvailable();
        $dbTransportationAvailable = $this->transportationAvailableRepo->find($transportationAvailable->id);
        $dbTransportationAvailable = $dbTransportationAvailable->toArray();
        $this->assertModelData($transportationAvailable->toArray(), $dbTransportationAvailable);
    }

    /**
     * @test update
     */
    public function test_update_transportation_available()
    {
        $transportationAvailable = $this->makeTransportationAvailable();
        $fakeTransportationAvailable = $this->fakeTransportationAvailableData();
        $updatedTransportationAvailable = $this->transportationAvailableRepo->update($fakeTransportationAvailable, $transportationAvailable->id);
        $this->assertModelData($fakeTransportationAvailable, $updatedTransportationAvailable->toArray());
        $dbTransportationAvailable = $this->transportationAvailableRepo->find($transportationAvailable->id);
        $this->assertModelData($fakeTransportationAvailable, $dbTransportationAvailable->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_transportation_available()
    {
        $transportationAvailable = $this->makeTransportationAvailable();
        $resp = $this->transportationAvailableRepo->delete($transportationAvailable->id);
        $this->assertTrue($resp);
        $this->assertNull(TransportationAvailable::find($transportationAvailable->id), 'TransportationAvailable should not exist in DB');
    }
}
