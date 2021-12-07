<?php

namespace App\Http\Middleware;

use Closure;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $myroles = explode(',', $request->user()->role);
        foreach ($myroles as $role) {
            if ( in_array($role, $roles)) {
                return $next($request);
            }
        }

        return back()->with('msg', 'Anda tidak berhak mengakses halaman tersebut!');
    }
}
