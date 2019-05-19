<?php


namespace RandomState\LaravelAuth\Http\Middleware;


use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use RandomState\LaravelAuth\AuthManager;
use RandomState\LaravelAuth\Exceptions\UnknownStrategyException;

class Authenticate
{

    /**
     * @var AuthManager
     */
    protected $manager;

    public function __construct(AuthManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param $request
     * @param Closure $next
     * @param null $strategy
     *
     * @param bool $sessions
     * @return mixed
     * @throws AuthenticationException
     * @throws UnknownStrategyException
     */
    public function handle($request, Closure $next, $strategy = null, $sessions = true)
    {
        if ( ! ($user = $this->manager->login($strategy, $request))) {
            throw new AuthenticationException('Unauthenticated.');
        };

        if($sessions === true) {
            Auth::login($user);
        } else {
            Auth::setUser($user);
        }

        return $next($request);
    }
}