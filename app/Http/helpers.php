<?php

if (! function_exists('route')) {
    /**
     * Generate the URL to a named route.
     *
     * @param  array|string  $name
     * @param  mixed  $parameters
     * @param  bool  $absolute
     * @return string
     */
    function route($name, $parameters = [], $absolute = true)   
    {
        $request = Illuminate\Http\Request::capture();
        $year = date("Y");
        if ($request->cookie('tahun')) {
            $year = $request->cookie('tahun');
        }
        $parameters = array_merge($parameters, ['tahun' => $year]);
        return app('url')->route($name, $parameters, $absolute);
    }
}

if (! function_exists('indo_num_format')) {
    function indo_num_format($number)
    {
        return number_format($number,2,",",".");
    }
}