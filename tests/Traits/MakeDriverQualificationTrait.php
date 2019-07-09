<?php namespace Tests\Traits;

use Faker\Factory as Faker;
use App\DriverQualification;
use App\Repositories\DriverQualificationRepository;

trait MakeDriverQualificationTrait
{
    /**
     * Create fake instance of DriverQualification and save it in database
     *
     * @param array $driverQualificationFields
     * @return DriverQualification
     */
    public function makeDriverQualification($driverQualificationFields = [])
    {
        /** @var DriverQualificationRepository $driverQualificationRepo */
        $driverQualificationRepo = \App::make(DriverQualificationRepository::class);
        $theme = $this->fakeDriverQualificationData($driverQualificationFields);
        return $driverQualificationRepo->create($theme);
    }

    /**
     * Get fake instance of DriverQualification
     *
     * @param array $driverQualificationFields
     * @return DriverQualification
     */
    public function fakeDriverQualification($driverQualificationFields = [])
    {
        return new DriverQualification($this->fakeDriverQualificationData($driverQualificationFields));
    }

    /**
     * Get fake data of DriverQualification
     *
     * @param array $driverQualificationFields
     * @return array
     */
    public function fakeDriverQualificationData($driverQualificationFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'driver_id' => $fake->word,
            'qualification' => $fake->randomDigitNotNull,
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s')
        ], $driverQualificationFields);
    }
}
