<?php


namespace RandomState\LaravelAuth\Tests\Feature;


use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RandomState\LaravelAuth\AuthManager;
use RandomState\LaravelAuth\AuthStrategy;
use RandomState\LaravelAuth\Tests\TestCase;
use Mockery as m;

class LaravelAuthenticationIntegrationTest extends TestCase
{

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

        $manager->login('strat', m::mock(Request::class));
        $this->assertEquals($successfulUser, Auth::user());
    }
}