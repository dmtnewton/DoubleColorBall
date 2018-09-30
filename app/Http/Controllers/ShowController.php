<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShowProfile extends Controller
{

    /**
     * 单动作控制器
     *
     * @param int $id
     * @return array
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/5/2
     **/
    public function __invoke($id = 0)
    {
        return view('user.profile', ['user' => User::findOrFail($id)]);
    }


}
