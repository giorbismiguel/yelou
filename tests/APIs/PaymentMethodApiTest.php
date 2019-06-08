<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakePaymentMethodTrait;
use Tests\ApiTestTrait;

class PaymentMethodApiTest extends TestCase
{
    use MakePaymentMethodTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_payment_method()
    {
        $paymentMethod = $this->fakePaymentMethodData();
        $this->response = $this->json('POST', '/api/admin/paymentMethods', $paymentMethod);

        $this->assertApiResponse($paymentMethod);
    }

    /**
     * @test
     */
    public function test_read_payment_method()
    {
        $paymentMethod = $this->makePaymentMethod();
        $this->response = $this->json('GET', '/api/admin/paymentMethods/'.$paymentMethod->id);

        $this->assertApiResponse($paymentMethod->toArray());
    }

    /**
     * @test
     */
    public function test_update_payment_method()
    {
        $paymentMethod = $this->makePaymentMethod();
        $editedPaymentMethod = $this->fakePaymentMethodData();

        $this->response = $this->json('PUT', '/api/admin/paymentMethods/'.$paymentMethod->id, $editedPaymentMethod);

        $this->assertApiResponse($editedPaymentMethod);
    }

    /**
     * @test
     */
    public function test_delete_payment_method()
    {
        $paymentMethod = $this->makePaymentMethod();
        $this->response = $this->json('DELETE', '/api/admin/paymentMethods/'.$paymentMethod->id);

        $this->assertApiSuccess();
        $this->response = $this->json('GET', '/api/admin/paymentMethods/'.$paymentMethod->id);

        $this->response->assertStatus(404);
    }
}
