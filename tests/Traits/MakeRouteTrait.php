<?php namespace Tests\Traits;

use Faker\Factory as Faker;
use App\Route;
use App\Repositories\RouteRepository;

trait MakeRouteTrait
{
    /**
     * Create fake instance of Route and save it in database
     *
     * @param array $routeFields
     * @return Route
     */
    public function makeRoute($routeFields = [])
    {
        /** @var RouteRepository $routeRepo */
        $routeRepo = \App::make(RouteRepository::class);
        $theme = $this->fakeRouteData($routeFields);
        return $routeRepo->create($theme);
    }

    /**
     * Get fake instance of Route
     *
     * @param array $routeFields
     * @return Route
     */
    public function fakeRoute($routeFields = [])
    {
        return new Route($this->fakeRouteData($routeFields));
    }

    /**
     * Get fake data of Route
     *
     * @param array $routeFields
     * @return array
     */
    public function fakeRouteData($routeFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'lat' => $fake->word,
            'lng' => $fake->word,
            'formatted_address' => $fake->word,
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s')
        ], $routeFields);
    }
}
