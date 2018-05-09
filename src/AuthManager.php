<?php


namespace RandomState\LaravelAuth;


use Illuminate\Support\Facades\Auth;
use RandomState\LaravelAuth\Exceptions\StrategyAlreadyRegisteredException;
use RandomState\LaravelAuth\Exceptions\UnknownStrategyException;
use Illuminate\Http\Request;

class AuthManager
{

    /**
     * @var AuthStrategy[]
     */
    protected $strategies;

    /**
     * @var AuthStrategy | null
     */
    protected $defaultStrategy;

    public function __construct()
    {
    }

    /**
     * @param $name
     * @param AuthStrategy $strategy
     *
     * @return $this
     * @throws StrategyAlreadyRegisteredException
     */
    public function register($name, AuthStrategy $strategy)
    {
        if($this->getOrNull($name)) {
            throw new StrategyAlreadyRegisteredException;
        };

        $this->strategies[$name] = $strategy;

        return $this;
    }

    public function forget($strategy)
    {
        if($this->getOrNull($strategy)) {
            unset($this->strategies[$strategy]);
        }

        return $this;
    }

    /**
     * @param $strategy
     * @param Request $request
     *
     * @return mixed
     * @throws UnknownStrategyException
     */
    public function login($strategy, Request $request)
    {
        $strategy = $this->get($strategy);
        $user = $strategy->convert($strategy->attempt($request));

        return $user;
    }

    /**
     * @param $strategy
     *
     * @return bool|AuthStrategy
     * @throws UnknownStrategyException
     */
    public function get($strategy = null)
    {
        if(!$strategy) {
            return $this->defaultStrategy;
        }

        $strategy = $this->getOrNull($strategy);

        if(!$strategy) {
            throw new UnknownStrategyException;
        }

        return $strategy;
    }

    protected function getOrNull($strategy)
    {
        return $this->strategies[$strategy] ?? null;
    }

    public function setDefault($strategy)
    {
        if(is_string($strategy)) {
            $this->defaultStrategy = $this->get($strategy);
        } else {
            $this->defaultStrategy = $strategy;
        }

        return $this;
    }
}