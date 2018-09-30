<?php
/**
 * 微信公用方法
 *
 * @author newton <yushuainiu@51talk.com>
 * @date 2018/9/6
 **/

namespace App\Models\Wechat;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use App\Models\Base;
use App\Tool\ToolBase;
use App\Models\Wechat\WxBizDataCrypt;

//include_once "wxBizDataCrypt.php";

class ServiceModel extends Base
{

    public static $apiUrlCfg;
    public static $appInfo;
    public static $cacheExpire = 5;


    /**
     * 区分APPID
     *
     * @param string $type
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/9/6
     **/
    public function __construct($type = 'wx_ball')
    {
        self::$apiUrlCfg = config("api.wx.wx_api_url");
        self::$appInfo   = config("api.wx.{$type}");
        if (empty(self::$appInfo)) {
            $error = __CLASS__ . ' . ' . __FUNCTION__ . " : not find config '{$type}'." . PHP_EOL;
            exit($error);
        }
    }


    /**
     * 获取登录检验信息（session-key）
     *
     * @param string $jsCode
     * @param array $options
     * @return array
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/9/6
     **/
    public function getWxSessionKey($jsCode = '', $options = [])
    {
        if (empty($jsCode)) {
            return false;
        }
        $cacheKey = "WX_MINIAPP_GetWxSessionKey_APPID_" . self::$appInfo['appid'] . "CODE{$jsCode}";
        $cacheObj = Cache::store('redis');
        $data     = $cacheObj->get($cacheKey);
        if (!empty($data)) {
            return json_decode($data, true);
        }
        $curlUrl = sprintf(self::$apiUrlCfg['get_code2session'], self::$appInfo['appid'], self::$appInfo['secret'], $jsCode);
        $curlRes = ToolBase::curlHttp($curlUrl, $options);

        $cacheObj->put($cacheKey, $curlRes, self::$cacheExpire);

        return json_decode($curlRes, true);
    }


    /**
     * 解密微信小程序隐私数据
     *
     * @param string $appid
     * @param string $sessionKey
     * @param string $encryptedData
     * @param string $iv
     * @param array $data
     * @return array
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/9/6
     **/
    public function wxDecryData($appid = '', $sessionKey = '', $encryptedData = '', $iv = '', $data = [])
    {
        $pc      = new WxBizDataCrypt($appid, $sessionKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $data);

        if ($errCode == 0) {
            return $data;
        } else {
            return $errCode;
        }

    }
}

