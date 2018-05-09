<?php


namespace RandomState\LaravelAuth\Tests\Feature;


use App\User;
use Illuminate\Http\Request;
use RandomState\LaravelAuth\AbstractAuthStrategy;
use RandomState\LaravelAuth\AuthManager;
use RandomState\LaravelAuth\Tests\TestCase;
use Mockery as m;

class StrategyTest extends TestCase
{

    /**
     * @test
     */
    public function strategy_can_convert_strategy_user_to_domain_logic_user()
    {
        $testStrategy = new class extends AbstractAuthStrategy
        {

            public function attempt(Request $request)
            {
                return new class
                {

                    public $name = 'testUser';
                };
            }
        };

        $manager = new AuthManager();
        $manager->register('test', $testStrategy);

        $testStrategy->convertUsing(function($user) {
            $newUser = new User;
            $newUser->name = 'John Doe';

            return $newUser;
        });

        $user = $manager->login('test', m::mock(Request::class));

        $this->assertEquals('John Doe', $user->name);
    }


}