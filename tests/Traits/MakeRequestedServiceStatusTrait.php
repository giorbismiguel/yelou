<?php namespace Tests\Traits;

use Faker\Factory as Faker;
use App\Models\Admin\RequestedServiceStatus;
use App\Repositories\Admin\RequestedServiceStatusRepository;

trait MakeRequestedServiceStatusTrait
{
    /**
     * Create fake instance of RequestedServiceStatus and save it in database
     *
     * @param array $requestedServiceStatusFields
     * @return RequestedServiceStatus
     */
    public function makeRequestedServiceStatus($requestedServiceStatusFields = [])
    {
        /** @var RequestedServiceStatusRepository $requestedServiceStatusRepo */
        $requestedServiceStatusRepo = \App::make(RequestedServiceStatusRepository::class);
        $theme = $this->fakeRequestedServiceStatusData($requestedServiceStatusFields);
        return $requestedServiceStatusRepo->create($theme);
    }

    /**
     * Get fake instance of RequestedServiceStatus
     *
     * @param array $requestedServiceStatusFields
     * @return RequestedServiceStatus
     */
    public function fakeRequestedServiceStatus($requestedServiceStatusFields = [])
    {
        return new RequestedServiceStatus($this->fakeRequestedServiceStatusData($requestedServiceStatusFields));
    }

    /**
     * Get fake data of RequestedServiceStatus
     *
     * @param array $requestedServiceStatusFields
     * @return array
     */
    public function fakeRequestedServiceStatusData($requestedServiceStatusFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name'        => $fake->word,
            'description' => $fake->text,
            'created_at'  => $fake->date('Y-m-d H:i:s'),
            'updated_at'  => $fake->date('Y-m-d H:i:s')
        ], $requestedServiceStatusFields);
    }
}
