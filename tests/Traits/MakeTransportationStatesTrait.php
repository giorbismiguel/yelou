<?php namespace Tests\Traits;

use Faker\Factory as Faker;
use App\Models\Admin\TransportationStates;
use App\Repositories\Admin\TransportationStatesRepository;

trait MakeTransportationStatesTrait
{
    /**
     * Create fake instance of TransportationStates and save it in database
     *
     * @param array $transportationStatesFields
     * @return TransportationStates
     */
    public function makeTransportationStates($transportationStatesFields = [])
    {
        /** @var TransportationStatesRepository $transportationStatesRepo */
        $transportationStatesRepo = \App::make(TransportationStatesRepository::class);
        $theme = $this->fakeTransportationStatesData($transportationStatesFields);
        return $transportationStatesRepo->create($theme);
    }

    /**
     * Get fake instance of TransportationStates
     *
     * @param array $transportationStatesFields
     * @return TransportationStates
     */
    public function fakeTransportationStates($transportationStatesFields = [])
    {
        return new TransportationStates($this->fakeTransportationStatesData($transportationStatesFields));
    }

    /**
     * Get fake data of TransportationStates
     *
     * @param array $transportationStatesFields
     * @return array
     */
    public function fakeTransportationStatesData($transportationStatesFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'description' => $fake->text,
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s')
        ], $transportationStatesFields);
    }
}
