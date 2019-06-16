<?php namespace Tests\Traits;

use Faker\Factory as Faker;
use App\Models\RequestedService;
use App\Repositories\RequestedServiceRepository;

trait MakeRequestedServiceTrait
{
    /**
     * Create fake instance of RequestedService and save it in database
     *
     * @param array $requestedServiceFields
     * @return RequestedService
     */
    public function makeRequestedService($requestedServiceFields = [])
    {
        /** @var RequestedServiceRepository $requestedServiceRepo */
        $requestedServiceRepo = \App::make(RequestedServiceRepository::class);
        $theme = $this->fakeRequestedServiceData($requestedServiceFields);
        return $requestedServiceRepo->create($theme);
    }

    /**
     * Get fake instance of RequestedService
     *
     * @param array $requestedServiceFields
     * @return RequestedService
     */
    public function fakeRequestedService($requestedServiceFields = [])
    {
        return new RequestedService($this->fakeRequestedServiceData($requestedServiceFields));
    }

    /**
     * Get fake data of RequestedService
     *
     * @param array $requestedServiceFields
     * @return array
     */
    public function fakeRequestedServiceData($requestedServiceFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'client_id' => $fake->word,
            'transporter_id' => $fake->word,
            'route_id' => $fake->word,
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s')
        ], $requestedServiceFields);
    }
}
