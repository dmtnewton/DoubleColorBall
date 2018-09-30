<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{

    /**
     * 调起中间件
     *
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/5/2
     **/
    public function __construct()
    {
//        $this->middleware('token');
//        $this->middleware('token')->only('show'); //只对该方法生效
//        $this->middleware('token')->except('show');   //只对该方法以外的方法生效
//        $this->middleware('token')->only(['show', 'pm']);   //只对指定方法生效
//        $this->middleware('token')->except(['show', 'pm']);   //只对指定方法以外的方法生效
    }


    /**
     * 主页
     *
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/15
     **/
    public function index()
    {
        return view('index');
    }

}
