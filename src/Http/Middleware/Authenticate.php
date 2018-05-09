<?php


namespace RandomState\LaravelAuth\Http\Middleware;


use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use RandomState\LaravelAuth\AuthManager;

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
     * @return mixed
     * @throws AuthenticationException
     * @throws \RandomState\LaravelAuth\Exceptions\UnknownStrategyException
     */
    public function handle($request, Closure $next, $strategy = null)
    {
        if ( ! ($user = $this->manager->login($strategy, $request))) {
            throw new AuthenticationException('Unauthenticated.');
        };

        Auth::setUser($user);

        return $next($request);
    }
}