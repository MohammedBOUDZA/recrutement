<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;

class LoginController extends Controller
{
    use AuthenticatesUsers, LogsActivity;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('verified')->except(['logout']);
    }

    protected function attemptLogin(Request $request)
    {
        $this->ensureIsNotRateLimited($request);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // Check if user is active
            if (!$user->is_active) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => ['Your account has been deactivated. Please contact support.'],
                ]);
            }

            // Check if email is verified
            if (!$user->hasVerifiedEmail()) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => ['Please verify your email address before logging in.'],
                ]);
            }

            // Clear rate limiter on successful login
            RateLimiter::clear($this->throttleKey($request));

            return true;
        }

        // Increment failed attempts
        RateLimiter::hit($this->throttleKey($request));

        return false;
    }

    protected function ensureIsNotRateLimited(Request $request)
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    protected function throttleKey(Request $request)
    {
        return Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    protected function authenticated(Request $request, $user)
    {
        // Log successful login
        activity()
            ->causedBy($user)
            ->performedOn($user)
            ->withProperties(['ip' => $request->ip()])
            ->log('User logged in');

        // Redirect based on role
        if ($user->role === 'chercheur') {
            return redirect()->route('chercheur.dashboard');
        } else {
            return redirect()->route('recruteur.dashboard');
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        // Log logout
        if ($user) {
            activity()
                ->causedBy($user)
                ->performedOn($user)
                ->withProperties(['ip' => $request->ip()])
                ->log('User logged out');
        }

        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}