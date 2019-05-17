<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakeTransportationStatesTrait;
use Tests\ApiTestTrait;

class TransportationStatesApiTest extends TestCase
{
    use MakeTransportationStatesTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_transportation_states()
    {
        $transportationStates = $this->fakeTransportationStatesData();
        $this->response = $this->json('POST', '/api/admin/transportationStates', $transportationStates);

        $this->assertApiResponse($transportationStates);
    }

    /**
     * @test
     */
    public function test_read_transportation_states()
    {
        $transportationStates = $this->makeTransportationStates();
        $this->response = $this->json('GET', '/api/admin/transportationStates/'.$transportationStates->id);

        $this->assertApiResponse($transportationStates->toArray());
    }

    /**
     * @test
     */
    public function test_update_transportation_states()
    {
        $transportationStates = $this->makeTransportationStates();
        $editedTransportationStates = $this->fakeTransportationStatesData();

        $this->response = $this->json('PUT', '/api/admin/transportationStates/'.$transportationStates->id, $editedTransportationStates);

        $this->assertApiResponse($editedTransportationStates);
    }

    /**
     * @test
     */
    public function test_delete_transportation_states()
    {
        $transportationStates = $this->makeTransportationStates();
        $this->response = $this->json('DELETE', '/api/admin/transportationStates/'.$transportationStates->id);

        $this->assertApiSuccess();
        $this->response = $this->json('GET', '/api/admin/transportationStates/'.$transportationStates->id);

        $this->response->assertStatus(404);
    }
}
