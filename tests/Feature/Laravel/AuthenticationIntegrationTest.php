<?php


namespace RandomState\LaravelAuth\Tests\Feature\Laravel;


use App\User;
use Illuminate\Http\Request;
use RandomState\LaravelAuth\AuthManager;
use RandomState\LaravelAuth\AuthStrategy;
use RandomState\LaravelAuth\LaravelAuthServiceProvider;
use RandomState\LaravelAuth\Tests\TestCase;
use Mockery as m;

class AuthenticationIntegrationTest extends TestCase
{

    /**
     * @test
     */
    public function manager_is_singleton()
    {
        $this->app->register(LaravelAuthServiceProvider::class);
        $this->assertSame($this->app->make(AuthManager::class), $this->app->make(AuthManager::class));
    }

    /**
     * @test
     */
    public function sets_user_in_laravel_when_authentication_success()
    {
        $successfulUser = new User();

        $strategy = m::mock(AuthStrategy::class);
        $strategy->shouldReceive('attempt')
                 ->andReturnNull();

        $strategy->shouldReceive('convert')
                 ->andReturn($successfulUser);

        $manager = new AuthManager();
        $manager->register('strat', $strategy);

        $user = $manager->login('strat', m::mock(Request::class));
        $this->assertEquals($successfulUser, $user);
    }
}