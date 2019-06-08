<?php namespace Tests\Traits;

use Faker\Factory as Faker;
use App\TransportationAvailable;
use App\Repositories\TransportationAvailableRepository;

trait MakeTransportationAvailableTrait
{
    /**
     * Create fake instance of TransportationAvailable and save it in database
     *
     * @param array $transportationAvailableFields
     * @return TransportationAvailable
     */
    public function makeTransportationAvailable($transportationAvailableFields = [])
    {
        /** @var TransportationAvailableRepository $transportationAvailableRepo */
        $transportationAvailableRepo = \App::make(TransportationAvailableRepository::class);
        $theme = $this->fakeTransportationAvailableData($transportationAvailableFields);
        return $transportationAvailableRepo->create($theme);
    }

    /**
     * Get fake instance of TransportationAvailable
     *
     * @param array $transportationAvailableFields
     * @return TransportationAvailable
     */
    public function fakeTransportationAvailable($transportationAvailableFields = [])
    {
        return new TransportationAvailable($this->fakeTransportationAvailableData($transportationAvailableFields));
    }

    /**
     * Get fake data of TransportationAvailable
     *
     * @param array $transportationAvailableFields
     * @return array
     */
    public function fakeTransportationAvailableData($transportationAvailableFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'lat' => $fake->word,
            'lng' => $fake->word,
            'active' => $fake->word,
            'user_id' => $fake->word,
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s')
        ], $transportationAvailableFields);
    }
}
