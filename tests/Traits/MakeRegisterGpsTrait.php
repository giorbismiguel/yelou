<?php namespace Tests\Traits;

use Faker\Factory as Faker;
use App\Models\RegisterGps;
use App\Repositories\RegisterGpsRepository;

trait MakeRegisterGpsTrait
{
    /**
     * Create fake instance of RegisterGps and save it in database
     *
     * @param array $registerGpsFields
     * @return RegisterGps
     */
    public function makeRegisterGps($registerGpsFields = [])
    {
        /** @var RegisterGpsRepository $registerGpsRepo */
        $registerGpsRepo = \App::make(RegisterGpsRepository::class);
        $theme = $this->fakeRegisterGpsData($registerGpsFields);
        return $registerGpsRepo->create($theme);
    }

    /**
     * Get fake instance of RegisterGps
     *
     * @param array $registerGpsFields
     * @return RegisterGps
     */
    public function fakeRegisterGps($registerGpsFields = [])
    {
        return new RegisterGps($this->fakeRegisterGpsData($registerGpsFields));
    }

    /**
     * Get fake data of RegisterGps
     *
     * @param array $registerGpsFields
     * @return array
     */
    public function fakeRegisterGpsData($registerGpsFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'lat' => $fake->word,
            'lng' => $fake->word,
            'driver_id' => $fake->word,
            'service_id' => $fake->word,
            'required|date_format:d/m/Y' => $fake->word,
            'required|date_format:d/m/Y' => $fake->word,
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s')
        ], $registerGpsFields);
    }
}
