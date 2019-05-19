<?php


namespace RandomState\LaravelAuth\Tests\Feature\Laravel;


use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RandomState\LaravelAuth\AbstractAuthStrategy;
use RandomState\LaravelAuth\AuthManager;
use RandomState\LaravelAuth\Http\Middleware\Authenticate;
use RandomState\LaravelAuth\Tests\TestCase;
use Mockery as m;

class AuthenticateMiddlewareTest extends TestCase
{

    /**
     * @var AuthManager
     */
    protected $manager;

    protected function setUp()
    {
        parent::setUp();
        /** @var AuthManager $manager */
        $this->manager = $this->app->make(AuthManager::class);
    }

    /**
     * @test
     * @throws \Illuminate\Auth\AuthenticationException
     * @throws \RandomState\LaravelAuth\Exceptions\StrategyAlreadyRegisteredException
     * @throws \RandomState\LaravelAuth\Exceptions\UnknownStrategyException
     */
    public function authentication_middleware_sets_guard_user_on_success()
    {
        $this->manager->register('success', $success = new class extends AbstractAuthStrategy
        {

            public function attempt(Request $request)
            {
                return new User();
            }

        });

        $success->convertUsing(function($user) {return $user;});


        $middleware = new Authenticate($this->manager);
        $middleware->handle(m::mock(Request::class), function () {
        }, 'success');

        $this->assertInstanceOf(User::class, Auth::user());
    }

    /**
     * @test
     */
    public function authentication_middleware_throws_exception_on_failure()
    {
        $this->expectException(AuthenticationException::class);

        $this->manager->register('failure', $failure = new class extends AbstractAuthStrategy
        {

            public function attempt(Request $request)
            {
                return null;
            }

        });

        $failure->convertUsing(function($user) {return $user;});


        $middleware = new Authenticate($this->manager);
        $middleware->handle(m::mock(Request::class), function () {
        }, 'failure');

        $this->assertNull(Auth::user());
    }

    /**
     * @test
     */
    public function can_disable_sessions()
    {
        Auth::spy();

        /** @var m\Mock $spy */
        $spy = Auth::getFacadeRoot();

        $this->manager->register('success', $success = new class extends AbstractAuthStrategy
        {

            public function attempt(Request $request)
            {
                return new User();
            }

        });

        $success->convertUsing(function($user) {return $user;});

        $middleware = new Authenticate($this->manager);
        $middleware->handle(m::mock(Request::class), function () {
        }, 'success', 'false');

        $spy->shouldNotHaveReceived('login');
        $spy->shouldHaveReceived('setUser')->once();
    }
}