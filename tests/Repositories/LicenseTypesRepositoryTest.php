<?php namespace Tests\Repositories;

use App\Models\LicenseTypes;
use App\Repositories\LicenseTypesRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakeLicenseTypesTrait;
use Tests\ApiTestTrait;

class LicenseTypesRepositoryTest extends TestCase
{
    use MakeLicenseTypesTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var LicenseTypesRepository
     */
    protected $licenseTypesRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->licenseTypesRepo = \App::make(LicenseTypesRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_license_types()
    {
        $licenseTypes = $this->fakeLicenseTypesData();
        $createdLicenseTypes = $this->licenseTypesRepo->create($licenseTypes);
        $createdLicenseTypes = $createdLicenseTypes->toArray();
        $this->assertArrayHasKey('id', $createdLicenseTypes);
        $this->assertNotNull($createdLicenseTypes['id'], 'Created LicenseTypes must have id specified');
        $this->assertNotNull(LicenseTypes::find($createdLicenseTypes['id']), 'LicenseTypes with given id must be in DB');
        $this->assertModelData($licenseTypes, $createdLicenseTypes);
    }

    /**
     * @test read
     */
    public function test_read_license_types()
    {
        $licenseTypes = $this->makeLicenseTypes();
        $dbLicenseTypes = $this->licenseTypesRepo->find($licenseTypes->id);
        $dbLicenseTypes = $dbLicenseTypes->toArray();
        $this->assertModelData($licenseTypes->toArray(), $dbLicenseTypes);
    }

    /**
     * @test update
     */
    public function test_update_license_types()
    {
        $licenseTypes = $this->makeLicenseTypes();
        $fakeLicenseTypes = $this->fakeLicenseTypesData();
        $updatedLicenseTypes = $this->licenseTypesRepo->update($fakeLicenseTypes, $licenseTypes->id);
        $this->assertModelData($fakeLicenseTypes, $updatedLicenseTypes->toArray());
        $dbLicenseTypes = $this->licenseTypesRepo->find($licenseTypes->id);
        $this->assertModelData($fakeLicenseTypes, $dbLicenseTypes->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_license_types()
    {
        $licenseTypes = $this->makeLicenseTypes();
        $resp = $this->licenseTypesRepo->delete($licenseTypes->id);
        $this->assertTrue($resp);
        $this->assertNull(LicenseTypes::find($licenseTypes->id), 'LicenseTypes should not exist in DB');
    }
}
