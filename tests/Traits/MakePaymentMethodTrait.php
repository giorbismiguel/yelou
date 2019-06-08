<?php namespace Tests\Traits;

use Faker\Factory as Faker;
use App\Models\Admin\PaymentMethod;
use App\Repositories\Admin\PaymentMethodRepository;

trait MakePaymentMethodTrait
{
    /**
     * Create fake instance of PaymentMethod and save it in database
     *
     * @param array $paymentMethodFields
     * @return PaymentMethod
     */
    public function makePaymentMethod($paymentMethodFields = [])
    {
        /** @var PaymentMethodRepository $paymentMethodRepo */
        $paymentMethodRepo = \App::make(PaymentMethodRepository::class);
        $theme = $this->fakePaymentMethodData($paymentMethodFields);
        return $paymentMethodRepo->create($theme);
    }

    /**
     * Get fake instance of PaymentMethod
     *
     * @param array $paymentMethodFields
     * @return PaymentMethod
     */
    public function fakePaymentMethod($paymentMethodFields = [])
    {
        return new PaymentMethod($this->fakePaymentMethodData($paymentMethodFields));
    }

    /**
     * Get fake data of PaymentMethod
     *
     * @param array $paymentMethodFields
     * @return array
     */
    public function fakePaymentMethodData($paymentMethodFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s')
        ], $paymentMethodFields);
    }
}
