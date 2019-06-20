<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakeRegisterGpsTrait;
use Tests\ApiTestTrait;

class RegisterGpsApiTest extends TestCase
{
    use MakeRegisterGpsTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_register_gps()
    {
        $registerGps = $this->fakeRegisterGpsData();
        $this->response = $this->json('POST', '/api/registerGps', $registerGps);

        $this->assertApiResponse($registerGps);
    }

    /**
     * @test
     */
    public function test_read_register_gps()
    {
        $registerGps = $this->makeRegisterGps();
        $this->response = $this->json('GET', '/api/registerGps/'.$registerGps->id);

        $this->assertApiResponse($registerGps->toArray());
    }

    /**
     * @test
     */
    public function test_update_register_gps()
    {
        $registerGps = $this->makeRegisterGps();
        $editedRegisterGps = $this->fakeRegisterGpsData();

        $this->response = $this->json('PUT', '/api/registerGps/'.$registerGps->id, $editedRegisterGps);

        $this->assertApiResponse($editedRegisterGps);
    }

    /**
     * @test
     */
    public function test_delete_register_gps()
    {
        $registerGps = $this->makeRegisterGps();
        $this->response = $this->json('DELETE', '/api/registerGps/'.$registerGps->id);

        $this->assertApiSuccess();
        $this->response = $this->json('GET', '/api/registerGps/'.$registerGps->id);

        $this->response->assertStatus(404);
    }
}
