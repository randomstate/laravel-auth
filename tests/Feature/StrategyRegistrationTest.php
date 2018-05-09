<?php


namespace RandomState\LaravelAuth\Tests\Feature;


use RandomState\LaravelAuth\AuthManager;
use RandomState\LaravelAuth\AuthStrategy;
use RandomState\LaravelAuth\Exceptions\StrategyAlreadyRegisteredException;
use RandomState\LaravelAuth\Tests\TestCase;
use Mockery as m;

class StrategyRegistrationTest extends TestCase
{

    /**
     * @test
     */
    public function can_register_a_strategy()
    {
        $manager = new AuthManager;
        $manager->register('jwt', $firstStrategy = m::mock(AuthStrategy::class));

        $this->assertEquals($manager->get('jwt'), $firstStrategy);
    }

    /**
     * @test
     */
    public function can_forget_a_strategy()
    {
        $manager = new AuthManager;
        $manager->register('jwt', $firstStrategy = m::mock(AuthStrategy::class));
        $manager->forget('jwt');

        $manager->register('jwt', $secondStrategy = m::mock(AuthStrategy::class));

        $this->assertEquals($manager->get('jwt'), $secondStrategy);
    }

    /**
     * @test
     */
    public function strategy_name_must_be_unique()
    {
        $this->expectException(StrategyAlreadyRegisteredException::class);

        $manager = new AuthManager;
        $manager->register('jwt', $firstStrategy = m::mock(AuthStrategy::class));
        $manager->register('jwt', $secondStrategy = m::mock(AuthStrategy::class));
    }

    /**
     * @test
     */
    public function can_register_default_strategy()
    {
        $manager = new AuthManager();
        $manager->setDefault($strat = m::mock(AuthStrategy::class));
        $default = $manager->get(null);

        $this->assertEquals($strat, $default);
    }
}