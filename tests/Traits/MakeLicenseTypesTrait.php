<?php namespace Tests\Traits;

use Faker\Factory as Faker;
use App\Models\LicenseTypes;
use App\Repositories\LicenseTypesRepository;

trait MakeLicenseTypesTrait
{
    /**
     * Create fake instance of LicenseTypes and save it in database
     *
     * @param array $licenseTypesFields
     * @return LicenseTypes
     */
    public function makeLicenseTypes($licenseTypesFields = [])
    {
        /** @var LicenseTypesRepository $licenseTypesRepo */
        $licenseTypesRepo = \App::make(LicenseTypesRepository::class);
        $theme = $this->fakeLicenseTypesData($licenseTypesFields);
        return $licenseTypesRepo->create($theme);
    }

    /**
     * Get fake instance of LicenseTypes
     *
     * @param array $licenseTypesFields
     * @return LicenseTypes
     */
    public function fakeLicenseTypes($licenseTypesFields = [])
    {
        return new LicenseTypes($this->fakeLicenseTypesData($licenseTypesFields));
    }

    /**
     * Get fake data of LicenseTypes
     *
     * @param array $licenseTypesFields
     * @return array
     */
    public function fakeLicenseTypesData($licenseTypesFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'type' => $fake->word,
            'description' => $fake->word,
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s')
        ], $licenseTypesFields);
    }
}
