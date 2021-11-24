<?php

namespace App\Http\Middleware;

use Closure;

class CustomizeParameter
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
        //variabel Tipe dipindah dari parameter pada url ke attribute request
        $tipe = $request->route()->parameters()['tipe'];

        $request->attributes->add(['tipe' => $tipe]);
        $request->route()->forgetParameter('tipe'); //dihapus dari parameter
        return $next($request);
    }
}
