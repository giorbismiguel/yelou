<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakeLicenseTypesTrait;
use Tests\ApiTestTrait;

class LicenseTypesApiTest extends TestCase
{
    use MakeLicenseTypesTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_license_types()
    {
        $licenseTypes = $this->fakeLicenseTypesData();
        $this->response = $this->json('POST', '/api/licenseTypes', $licenseTypes);

        $this->assertApiResponse($licenseTypes);
    }

    /**
     * @test
     */
    public function test_read_license_types()
    {
        $licenseTypes = $this->makeLicenseTypes();
        $this->response = $this->json('GET', '/api/licenseTypes/'.$licenseTypes->id);

        $this->assertApiResponse($licenseTypes->toArray());
    }

    /**
     * @test
     */
    public function test_update_license_types()
    {
        $licenseTypes = $this->makeLicenseTypes();
        $editedLicenseTypes = $this->fakeLicenseTypesData();

        $this->response = $this->json('PUT', '/api/licenseTypes/'.$licenseTypes->id, $editedLicenseTypes);

        $this->assertApiResponse($editedLicenseTypes);
    }

    /**
     * @test
     */
    public function test_delete_license_types()
    {
        $licenseTypes = $this->makeLicenseTypes();
        $this->response = $this->json('DELETE', '/api/licenseTypes/'.$licenseTypes->id);

        $this->assertApiSuccess();
        $this->response = $this->json('GET', '/api/licenseTypes/'.$licenseTypes->id);

        $this->response->assertStatus(404);
    }
}
