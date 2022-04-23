<?php

namespace RandomState\LaravelAuth\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use RandomState\LaravelAuth\Exceptions\UnknownStrategyException;

/**
 * Perform Authenticate middleware without any exceptions.
 * This is useful if you want to attach the user to the current session if available, but otherwise continue unimpeded.
 */
class PassiveAuthenticate
{
    /**
     * @var Authenticate
     */
    protected $authenticate;

    public function __construct(Authenticate $authenticate)
    {
        $this->authenticate = $authenticate;
    }

    /**
     * @param $request
     * @param Closure $next
     * @throws UnknownStrategyException
     */
    public function handle($request, Closure $next, ...$params)
    {
        try {
            $this->authenticate->handle($request, $next, ...$params);
        } catch(AuthenticationException $e) {
        }

        return $next($request);
    }
}