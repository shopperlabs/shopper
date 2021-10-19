<?php

namespace Shopper\Framework\Services\TwoFactor;

use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Shopper\Framework\Shopper;

class LoginRateLimiter
{
    /**
     * The login rate limiter instance.
     *
     * @var \Illuminate\Cache\RateLimiter
     */
    protected $limiter;

    /**
     * Create a new login rate limiter instance.
     */
    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Determine if the user has too many failed login attempts.
     *
     * @return bool
     */
    public function tooManyAttempts(Request $request)
    {
        return $this->limiter->tooManyAttempts($this->throttleKey($request), 5);
    }

    /**
     * Increment the login attempts for the user.
     */
    public function increment(Request $request)
    {
        $this->limiter->hit($this->throttleKey($request), 60);
    }

    /**
     * Determine the number of seconds until logging in is available again.
     *
     * @return int
     */
    public function availableIn(Request $request)
    {
        return $this->limiter->availableIn($this->throttleKey($request));
    }

    /**
     * Clear the login locks for the given user credentials.
     */
    public function clear(Request $request)
    {
        $this->limiter->clear($this->throttleKey($request));
    }

    /**
     * Get the throttle key for the given request.
     *
     * @return string
     */
    protected function throttleKey(Request $request)
    {
        return Str::lower($request->input(Shopper::username())) . '|' . $request->ip();
    }
}
