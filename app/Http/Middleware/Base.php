<?php

namespace App\Http\Middleware;

use Closure;

class Base
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
        echo 'input:<pre>' . PHP_EOL;
        print_r($request->input());
        echo '</pre>' . PHP_EOL;


        return $next($request);
    }
}
