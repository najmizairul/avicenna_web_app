<?php

namespace App\Http\Middleware;

use App\Actions\RedirectAuthenticatedUser;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RedirectIfAuthenticated
{
    private RedirectAuthenticatedUser $redirectAuthenticatedUser;

    public function __construct()
    {
        $this->redirectAuthenticatedUser = new RedirectAuthenticatedUser();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if(Str::startsWith($request->path(),  'api/')) {
            return $next($request);
        }

        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return $this->redirectAuthenticatedUser->handle();
            }
        }

        return $next($request);
    }
}
