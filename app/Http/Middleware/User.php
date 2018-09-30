<?php

namespace App\Http\Middleware;

use Closure;

class User extends Base
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
//        echo 'input:<pre>' . PHP_EOL;
//        print_r($request->input());
//        echo '</pre>' . PHP_EOL;

        //路由重定向
//        if ($request->input('token') != 'wx.dmtnewton.com') {
//            return redirect()->to('/user/1');
//        }
        return $next($request);
    }
}
