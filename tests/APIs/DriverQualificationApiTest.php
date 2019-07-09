<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakeDriverQualificationTrait;
use Tests\ApiTestTrait;

class DriverQualificationApiTest extends TestCase
{
    use MakeDriverQualificationTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_driver_qualification()
    {
        $driverQualification = $this->fakeDriverQualificationData();
        $this->response = $this->json('POST', '/api/driverQualifications', $driverQualification);

        $this->assertApiResponse($driverQualification);
    }

    /**
     * @test
     */
    public function test_read_driver_qualification()
    {
        $driverQualification = $this->makeDriverQualification();
        $this->response = $this->json('GET', '/api/driverQualifications/'.$driverQualification->id);

        $this->assertApiResponse($driverQualification->toArray());
    }

    /**
     * @test
     */
    public function test_update_driver_qualification()
    {
        $driverQualification = $this->makeDriverQualification();
        $editedDriverQualification = $this->fakeDriverQualificationData();

        $this->response = $this->json('PUT', '/api/driverQualifications/'.$driverQualification->id, $editedDriverQualification);

        $this->assertApiResponse($editedDriverQualification);
    }

    /**
     * @test
     */
    public function test_delete_driver_qualification()
    {
        $driverQualification = $this->makeDriverQualification();
        $this->response = $this->json('DELETE', '/api/driverQualifications/'.$driverQualification->id);

        $this->assertApiSuccess();
        $this->response = $this->json('GET', '/api/driverQualifications/'.$driverQualification->id);

        $this->response->assertStatus(404);
    }
}
