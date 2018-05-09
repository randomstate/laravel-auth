# laravel-auth

A strategy-based auth component for Laravel (and PHP too, I guess!)

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