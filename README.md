# laravel-auth

A strategy-based auth component for Laravel.

# Setup with Laravel

Add `RandomState\LaravelAuth\LaravelAuthServiceProvider::class` to your app.php 'providers' config.

# Strategies

In a service provider:

```php
public function register() {
    $this->app->resolving(AuthManager::class, function($manager) {
        $manager->register('jwt', $this->app->make(JwtStrategy::class));
    });
    
    $this->app->resolving(JwtStrategy::class, function($strategy) {
        $strategy->convertUsing(function(JwtUser $jwtUser) {
            return new MyUser($jwtUser);
        });
    });
}
```

## Writing a Custom Strategy

Strategies are relatively easy to implement, though you need a firm grasp of how your authentication mechanism works before
you should write a strategy for it. This library doesn't protect you from any security holes you implement by accident.

Your strategy should implement the interface `RandomState\LaravelAuth\AuthStrategy`. A convenient `RandomState\LaravelAuth\AbstractAuthStrategy` class is provided to help with this.
There are two methods in this interface that must be implemented and are explained below.

```php

/*
 * This method is responsible for authenticating a given Laravel HTTP Request.
 * You have access to the entire request and can choose to manipulate the request's response property to your liking.
 * For more complex flows such as OAuth2, you can still implement these in a single strategy by
 * checking for access tokens in the URL and performing different redirect logic. Only when everything is in the URL for a final
 * step should you then authenticate and allow a user through. See some of the Random State strategies for examples on how to do this.
 */
public function attempt(Request $request);

/*
 * If you use the AbstractAuthStrategy class to help you, you can ignore this method.
 *
 * The strategy system works well when it is isolated to its own domain. You should usually expose strategy specific objects
 * to represent the client or user that is authenticating/authenticated. This might be a FirebaseUser, a StripeUser,
 * a GitHub user etc.
 * 
 * This function should take the (possibly-null) authenticated object returned by your attempt method and convert it into a
 * user object you can use in your application.
 */
public function convert($user);

/*
 * This method is only present in the AbstractAuthStrategy class, if you are extending that class.
 * 
 * Strategies made for open source or third party use will benefit greatly from this API.
 * It allows a consumer to pass a function to convert the domain-specific user (e.g. FirebaseUser) to their own domain.
 * 
 * This is especially useful for third-party consumers simply because you don't need to know the structure of their application
 * ahead of creating the strategy implementation.
 */
public function convertUsing(Closure $converter);
```