<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
    ];

    public function handle($request, \Closure $next)
    {
        try {
            $res = parent::handle($request, $next);
            return $res;
        } catch (\Exception $th) {
            $request->session()->flash('alert', [
                'icon' => 'close',
                'message' => 'Sesi habis, Coba lagi!',
                'status' => '2',
             ]);
            return back();
        }
        throw new \Illuminate\Session\TokenMismatchException;
    }
}
