<?php

namespace App\Http\Controllers\Ball;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;


class BaseController extends Controller
{
    /**
     * TEST
     *
     * @param int $uid
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/4/10
     **/
    public static function test($uid = 0, Request $request)
    {
        echo 'uid:<pre>' . PHP_EOL;
        print_r($uid);
        echo '</pre>' . PHP_EOL;

        $cacheObj = Cache::store('redis');
        $cacheKey = 'abc';
        $cacheObj->put($cacheKey, 123, 10); //10min
        $val = $cacheObj->get($cacheKey);
        echo 'val:<pre>' . PHP_EOL;
        print_r($val);
        echo '</pre>' . PHP_EOL;
        exit;
        return true;
    }

    /**
     * WeChat api verify (需要同时支持HTTP和HTTPS)
     *
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/3/16
     * @route WeChat\WXBaseController@verify
     **/
    public static function verify(Request $request)
    {
        $param   = $request->input();
//        if (!empty($param['echoStr'])) {
            $echoStr = trim(addslashes($param['echoStr']));
            if (self::checkSignature($param)) {
                echo $echoStr;
            }
//        }
        exit;
    }

    /**
     * 和微信接口进行验证
     *
     * @param array $param
     * @return bool
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/4/10
     **/
    private static function checkSignature($param = [])
    {
        $signature = trim(addslashes($param['signature']));
        $timestamp = trim(addslashes($param['timestamp']));
        $nonce     = trim(addslashes($param['nonce']));

        $token  = WX_TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }
}
