<?php namespace Tests\Traits;

use Faker\Factory as Faker;
use App\Models\RequestServices;
use App\Repositories\RequestServicesRepository;

trait MakeRequestServicesTrait
{
    /**
     * Create fake instance of RequestServices and save it in database
     *
     * @param array $requestServicesFields
     * @return RequestServices
     */
    public function makeRequestServices($requestServicesFields = [])
    {
        /** @var RequestServicesRepository $requestServicesRepo */
        $requestServicesRepo = \App::make(RequestServicesRepository::class);
        $theme = $this->fakeRequestServicesData($requestServicesFields);
        return $requestServicesRepo->create($theme);
    }

    /**
     * Get fake instance of RequestServices
     *
     * @param array $requestServicesFields
     * @return RequestServices
     */
    public function fakeRequestServices($requestServicesFields = [])
    {
        return new RequestServices($this->fakeRequestServicesData($requestServicesFields));
    }

    /**
     * Get fake data of RequestServices
     *
     * @param array $requestServicesFields
     * @return array
     */
    public function fakeRequestServicesData($requestServicesFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'route_id' => $fake->word,
            'name_start' => $fake->word,
            'lat_start' => $fake->word,
            'lng_start' => $fake->word,
            'name_end' => $fake->word,
            'lat_end' => $fake->word,
            'lng_end' => $fake->word,
            'start_time' => $fake->date('Y-m-d H:i:s'),
            'payment_method_id' => $fake->word,
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s')
        ], $requestServicesFields);
    }
}
