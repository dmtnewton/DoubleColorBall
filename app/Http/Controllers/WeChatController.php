<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WeChatController extends Controller
{
    /**
     * 微信接收消息验证
     *
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/5/2
     **/
    public function checkAccept()
    {
        echo '<pre>' . PHP_EOL;
        print_r('working');
        echo '</pre>' . PHP_EOL;
        exit;
    }
}
