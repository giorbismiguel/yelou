<?php namespace Tests\Traits;

use Faker\Factory as Faker;
use App\User;
use App\Repositories\UserRepository;

trait MakeUserTrait
{
    /**
     * Create fake instance of User and save it in database
     *
     * @param array $userFields
     * @return User
     */
    public function makeUser($userFields = [])
    {
        /** @var UserRepository $userRepo */
        $userRepo = \App::make(UserRepository::class);
        $theme = $this->fakeUserData($userFields);
        return $userRepo->create($theme);
    }

    /**
     * Get fake instance of User
     *
     * @param array $userFields
     * @return User
     */
    public function fakeUser($userFields = [])
    {
        return new User($this->fakeUserData($userFields));
    }

    /**
     * Get fake data of User
     *
     * @param array $userFields
     * @return array
     */
    public function fakeUserData($userFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'email' => $fake->word,
            'first_name' => $fake->word,
            'last_name' => $fake->word,
            'phone' => $fake->word,
            'ruc' => $fake->word,
            'direction' => $fake->word,
            'postal_code' => $fake->word,
            'city' => $fake->word,
            'license_types_id' => $fake->randomDigitNotNull,
            'photo' => $fake->word,
            'image_driver_license' => $fake->word,
            'image_permit_circulation' => $fake->word,
            'image_certificate_background' => $fake->word,
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s')
        ], $userFields);
    }
}
