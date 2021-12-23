<?php

namespace App\Http\Middleware;

use Closure;

class StrictReguler
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $slug=$request->get('tipe')->slug;
        $myroles = explode(', ', $request->user()->role);
        foreach ($myroles as $role) {
            if ( substr($role, 0, 7) === "Reguler" and substr($role, 8)!==$slug) {        
                return back()->with('msg', 'Anda tidak berhak mengakses halaman tersebut!');
            }
        }

        return $next($request);
    }
}
