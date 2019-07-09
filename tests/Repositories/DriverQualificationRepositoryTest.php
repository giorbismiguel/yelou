<?php namespace Tests\Repositories;

use App\DriverQualification;
use App\Repositories\DriverQualificationRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakeDriverQualificationTrait;
use Tests\ApiTestTrait;

class DriverQualificationRepositoryTest extends TestCase
{
    use MakeDriverQualificationTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var DriverQualificationRepository
     */
    protected $driverQualificationRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->driverQualificationRepo = \App::make(DriverQualificationRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_driver_qualification()
    {
        $driverQualification = $this->fakeDriverQualificationData();
        $createdDriverQualification = $this->driverQualificationRepo->create($driverQualification);
        $createdDriverQualification = $createdDriverQualification->toArray();
        $this->assertArrayHasKey('id', $createdDriverQualification);
        $this->assertNotNull($createdDriverQualification['id'], 'Created DriverQualification must have id specified');
        $this->assertNotNull(DriverQualification::find($createdDriverQualification['id']), 'DriverQualification with given id must be in DB');
        $this->assertModelData($driverQualification, $createdDriverQualification);
    }

    /**
     * @test read
     */
    public function test_read_driver_qualification()
    {
        $driverQualification = $this->makeDriverQualification();
        $dbDriverQualification = $this->driverQualificationRepo->find($driverQualification->id);
        $dbDriverQualification = $dbDriverQualification->toArray();
        $this->assertModelData($driverQualification->toArray(), $dbDriverQualification);
    }

    /**
     * @test update
     */
    public function test_update_driver_qualification()
    {
        $driverQualification = $this->makeDriverQualification();
        $fakeDriverQualification = $this->fakeDriverQualificationData();
        $updatedDriverQualification = $this->driverQualificationRepo->update($fakeDriverQualification, $driverQualification->id);
        $this->assertModelData($fakeDriverQualification, $updatedDriverQualification->toArray());
        $dbDriverQualification = $this->driverQualificationRepo->find($driverQualification->id);
        $this->assertModelData($fakeDriverQualification, $dbDriverQualification->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_driver_qualification()
    {
        $driverQualification = $this->makeDriverQualification();
        $resp = $this->driverQualificationRepo->delete($driverQualification->id);
        $this->assertTrue($resp);
        $this->assertNull(DriverQualification::find($driverQualification->id), 'DriverQualification should not exist in DB');
    }
}
