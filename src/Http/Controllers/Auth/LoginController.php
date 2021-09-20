<?php

namespace Shopper\Framework\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Shopper\Framework\Shopper;
use Illuminate\Routing\Pipeline;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Shopper\Framework\Actions\AttemptToAuthenticate;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Shopper\Framework\Actions\RedirectIfTwoFactorAuthenticatable;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    use ValidatesRequests;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('shopper.guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('shopper::auth.login');
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect($this->redirectPath());
    }

    public function redirectPath(): string
    {
        return route('shopper.dashboard');
    }

    /**
     * Handle a login request to the application.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        // Attempt to authenticate a new session.
        return $this->loginPipeline($request)->then(fn ($request) => $this->sendLoginResponse($request));
    }

    /**
     * Get the login username to be used by the controller.
     */
    public function username(): string
    {
        return Shopper::username();
    }

    /**
     * Get the authentication pipeline instance.
     *
     * @return \Illuminate\Pipeline\Pipeline
     */
    protected function loginPipeline(Request $request)
    {
        return (new Pipeline(app()))->send($request)->through(array_filter([
            RedirectIfTwoFactorAuthenticatable::class,
            AttemptToAuthenticate::class,
        ]));
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return $request->wantsJson()
            ? response()->json(['two_factor' => false])
            : redirect()->intended($this->redirectPath());
    }

    protected function guard()
    {
        return Auth::guard(config('shopper.auth.guard'));
    }
}
