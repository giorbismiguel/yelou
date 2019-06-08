<?php namespace Tests\Repositories;

use App\Models\Admin\PaymentMethod;
use App\Repositories\Admin\PaymentMethodRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakePaymentMethodTrait;
use Tests\ApiTestTrait;

class PaymentMethodRepositoryTest extends TestCase
{
    use MakePaymentMethodTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var PaymentMethodRepository
     */
    protected $paymentMethodRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->paymentMethodRepo = \App::make(PaymentMethodRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_payment_method()
    {
        $paymentMethod = $this->fakePaymentMethodData();
        $createdPaymentMethod = $this->paymentMethodRepo->create($paymentMethod);
        $createdPaymentMethod = $createdPaymentMethod->toArray();
        $this->assertArrayHasKey('id', $createdPaymentMethod);
        $this->assertNotNull($createdPaymentMethod['id'], 'Created PaymentMethod must have id specified');
        $this->assertNotNull(PaymentMethod::find($createdPaymentMethod['id']), 'PaymentMethod with given id must be in DB');
        $this->assertModelData($paymentMethod, $createdPaymentMethod);
    }

    /**
     * @test read
     */
    public function test_read_payment_method()
    {
        $paymentMethod = $this->makePaymentMethod();
        $dbPaymentMethod = $this->paymentMethodRepo->find($paymentMethod->id);
        $dbPaymentMethod = $dbPaymentMethod->toArray();
        $this->assertModelData($paymentMethod->toArray(), $dbPaymentMethod);
    }

    /**
     * @test update
     */
    public function test_update_payment_method()
    {
        $paymentMethod = $this->makePaymentMethod();
        $fakePaymentMethod = $this->fakePaymentMethodData();
        $updatedPaymentMethod = $this->paymentMethodRepo->update($fakePaymentMethod, $paymentMethod->id);
        $this->assertModelData($fakePaymentMethod, $updatedPaymentMethod->toArray());
        $dbPaymentMethod = $this->paymentMethodRepo->find($paymentMethod->id);
        $this->assertModelData($fakePaymentMethod, $dbPaymentMethod->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_payment_method()
    {
        $paymentMethod = $this->makePaymentMethod();
        $resp = $this->paymentMethodRepo->delete($paymentMethod->id);
        $this->assertTrue($resp);
        $this->assertNull(PaymentMethod::find($paymentMethod->id), 'PaymentMethod should not exist in DB');
    }
}
