<?php namespace Tests\Repositories;

use App\Route;
use App\Repositories\RouteRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakeRouteTrait;
use Tests\ApiTestTrait;

class RouteRepositoryTest extends TestCase
{
    use MakeRouteTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var RouteRepository
     */
    protected $routeRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->routeRepo = \App::make(RouteRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_route()
    {
        $route = $this->fakeRouteData();
        $createdRoute = $this->routeRepo->create($route);
        $createdRoute = $createdRoute->toArray();
        $this->assertArrayHasKey('id', $createdRoute);
        $this->assertNotNull($createdRoute['id'], 'Created Route must have id specified');
        $this->assertNotNull(Route::find($createdRoute['id']), 'Route with given id must be in DB');
        $this->assertModelData($route, $createdRoute);
    }

    /**
     * @test read
     */
    public function test_read_route()
    {
        $route = $this->makeRoute();
        $dbRoute = $this->routeRepo->find($route->id);
        $dbRoute = $dbRoute->toArray();
        $this->assertModelData($route->toArray(), $dbRoute);
    }

    /**
     * @test update
     */
    public function test_update_route()
    {
        $route = $this->makeRoute();
        $fakeRoute = $this->fakeRouteData();
        $updatedRoute = $this->routeRepo->update($fakeRoute, $route->id);
        $this->assertModelData($fakeRoute, $updatedRoute->toArray());
        $dbRoute = $this->routeRepo->find($route->id);
        $this->assertModelData($fakeRoute, $dbRoute->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_route()
    {
        $route = $this->makeRoute();
        $resp = $this->routeRepo->delete($route->id);
        $this->assertTrue($resp);
        $this->assertNull(Route::find($route->id), 'Route should not exist in DB');
    }
}
