<?php namespace Tests\Repositories;

use App\RegisterGps;
use App\Repositories\RegisterGpsRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakeRegisterGpsTrait;
use Tests\ApiTestTrait;

class RegisterGpsRepositoryTest extends TestCase
{
    use MakeRegisterGpsTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var RegisterGpsRepository
     */
    protected $registerGpsRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->registerGpsRepo = \App::make(RegisterGpsRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_register_gps()
    {
        $registerGps = $this->fakeRegisterGpsData();
        $createdRegisterGps = $this->registerGpsRepo->create($registerGps);
        $createdRegisterGps = $createdRegisterGps->toArray();
        $this->assertArrayHasKey('id', $createdRegisterGps);
        $this->assertNotNull($createdRegisterGps['id'], 'Created RegisterGps must have id specified');
        $this->assertNotNull(RegisterGps::find($createdRegisterGps['id']), 'RegisterGps with given id must be in DB');
        $this->assertModelData($registerGps, $createdRegisterGps);
    }

    /**
     * @test read
     */
    public function test_read_register_gps()
    {
        $registerGps = $this->makeRegisterGps();
        $dbRegisterGps = $this->registerGpsRepo->find($registerGps->id);
        $dbRegisterGps = $dbRegisterGps->toArray();
        $this->assertModelData($registerGps->toArray(), $dbRegisterGps);
    }

    /**
     * @test update
     */
    public function test_update_register_gps()
    {
        $registerGps = $this->makeRegisterGps();
        $fakeRegisterGps = $this->fakeRegisterGpsData();
        $updatedRegisterGps = $this->registerGpsRepo->update($fakeRegisterGps, $registerGps->id);
        $this->assertModelData($fakeRegisterGps, $updatedRegisterGps->toArray());
        $dbRegisterGps = $this->registerGpsRepo->find($registerGps->id);
        $this->assertModelData($fakeRegisterGps, $dbRegisterGps->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_register_gps()
    {
        $registerGps = $this->makeRegisterGps();
        $resp = $this->registerGpsRepo->delete($registerGps->id);
        $this->assertTrue($resp);
        $this->assertNull(RegisterGps::find($registerGps->id), 'RegisterGps should not exist in DB');
    }
}
