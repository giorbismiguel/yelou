<?php namespace Tests\Repositories;

use App\Models\Admin\TransportationStates;
use App\Repositories\Admin\TransportationStatesRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakeTransportationStatesTrait;
use Tests\ApiTestTrait;

class TransportationStatesRepositoryTest extends TestCase
{
    use MakeTransportationStatesTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var TransportationStatesRepository
     */
    protected $transportationStatesRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->transportationStatesRepo = \App::make(TransportationStatesRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_transportation_states()
    {
        $transportationStates = $this->fakeTransportationStatesData();
        $createdTransportationStates = $this->transportationStatesRepo->create($transportationStates);
        $createdTransportationStates = $createdTransportationStates->toArray();
        $this->assertArrayHasKey('id', $createdTransportationStates);
        $this->assertNotNull($createdTransportationStates['id'], 'Created TransportationStates must have id specified');
        $this->assertNotNull(TransportationStates::find($createdTransportationStates['id']), 'TransportationStates with given id must be in DB');
        $this->assertModelData($transportationStates, $createdTransportationStates);
    }

    /**
     * @test read
     */
    public function test_read_transportation_states()
    {
        $transportationStates = $this->makeTransportationStates();
        $dbTransportationStates = $this->transportationStatesRepo->find($transportationStates->id);
        $dbTransportationStates = $dbTransportationStates->toArray();
        $this->assertModelData($transportationStates->toArray(), $dbTransportationStates);
    }

    /**
     * @test update
     */
    public function test_update_transportation_states()
    {
        $transportationStates = $this->makeTransportationStates();
        $fakeTransportationStates = $this->fakeTransportationStatesData();
        $updatedTransportationStates = $this->transportationStatesRepo->update($fakeTransportationStates, $transportationStates->id);
        $this->assertModelData($fakeTransportationStates, $updatedTransportationStates->toArray());
        $dbTransportationStates = $this->transportationStatesRepo->find($transportationStates->id);
        $this->assertModelData($fakeTransportationStates, $dbTransportationStates->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_transportation_states()
    {
        $transportationStates = $this->makeTransportationStates();
        $resp = $this->transportationStatesRepo->delete($transportationStates->id);
        $this->assertTrue($resp);
        $this->assertNull(TransportationStates::find($transportationStates->id), 'TransportationStates should not exist in DB');
    }
}
