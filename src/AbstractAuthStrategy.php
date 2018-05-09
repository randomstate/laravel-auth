<?php


namespace RandomState\LaravelAuth;


use Closure;
use RandomState\LaravelAuth\Exceptions\ConversionBetweenStrategyUserAndDomainUserNotDefinedException;

abstract class AbstractAuthStrategy implements AuthStrategy
{

    /**
     * @var Closure
     */
    protected $converter;

    public function convertUsing(Closure $converter)
    {
        $this->converter = $converter;

        return $this;
    }


    /**
     * @param $user
     *
     * @return mixed
     * @throws ConversionBetweenStrategyUserAndDomainUserNotDefinedException
     */
    public function convert($user)
    {
        if ( ! $this->converter) {
            throw new ConversionBetweenStrategyUserAndDomainUserNotDefinedException;
        }

        return ($this->converter)($user);
    }

}