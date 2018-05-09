<?php


namespace RandomState\LaravelAuth;


use Illuminate\Http\Request;

interface AuthStrategy
{
    public function attempt(Request $request);

    /**
     * Convert Strategy-specific user to a local domain logic user.
     *
     * @param $user
     *
     * @return mixed
     */
    public function convert($user);
}