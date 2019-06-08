<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakeTransportationAvailableTrait;
use Tests\ApiTestTrait;

class TransportationAvailableApiTest extends TestCase
{
    use MakeTransportationAvailableTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_transportation_available()
    {
        $transportationAvailable = $this->fakeTransportationAvailableData();
        $this->response = $this->json('POST', '/api/transportationAvailables', $transportationAvailable);

        $this->assertApiResponse($transportationAvailable);
    }

    /**
     * @test
     */
    public function test_read_transportation_available()
    {
        $transportationAvailable = $this->makeTransportationAvailable();
        $this->response = $this->json('GET', '/api/transportationAvailables/'.$transportationAvailable->id);

        $this->assertApiResponse($transportationAvailable->toArray());
    }

    /**
     * @test
     */
    public function test_update_transportation_available()
    {
        $transportationAvailable = $this->makeTransportationAvailable();
        $editedTransportationAvailable = $this->fakeTransportationAvailableData();

        $this->response = $this->json('PUT', '/api/transportationAvailables/'.$transportationAvailable->id, $editedTransportationAvailable);

        $this->assertApiResponse($editedTransportationAvailable);
    }

    /**
     * @test
     */
    public function test_delete_transportation_available()
    {
        $transportationAvailable = $this->makeTransportationAvailable();
        $this->response = $this->json('DELETE', '/api/transportationAvailables/'.$transportationAvailable->id);

        $this->assertApiSuccess();
        $this->response = $this->json('GET', '/api/transportationAvailables/'.$transportationAvailable->id);

        $this->response->assertStatus(404);
    }
}
