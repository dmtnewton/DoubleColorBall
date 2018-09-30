<?php

namespace App\Http\Controllers\WeChat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

define('WX_TOKEN', 'newton');

class WXBaseController extends Controller
{
    /**
     * TEST
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/4/10
     **/
    public static function test(Request $request)
    {
        echo 'test:<pre>' . PHP_EOL;
        print_r($request->input());
        echo '</pre>' . PHP_EOL;
        exit;
        return true;
    }


    /**
     * 微信服务器配置检验
     * WeChat api verify (需要同时支持HTTP和HTTPS)
     *
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/6/26
     **/
    public static function check()
    {
        $echoStr = $_GET["echostr"];        //随机字符串

        if(self::checkSignatureWithCheck()){
            echo $echoStr;
            exit;
        }
        return true;
    }


    /**
     * 微信端验证签名
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/6/26
     **/
    private static function checkSignatureWithCheck()
    {
        $signature = $_GET["signature"];    //微信加密签名
        $timestamp = $_GET["timestamp"];    //时间戳
        $nonce = $_GET["nonce"];            //随机数
        $token = WX_TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);      //进行字典序排序
        //sha1加密后与签名对比
        if( sha1(implode($tmpArr)) == $signature ){
            return true;
        }else{
            return false;
        }
    }
}
