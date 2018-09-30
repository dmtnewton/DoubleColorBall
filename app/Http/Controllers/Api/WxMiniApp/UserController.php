<?php

namespace App\Http\Controllers\Api\WxMiniApp;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use App\Models\User\UserDBModel;
use App\Models\Wechat\ServiceModel;
use App\Tool\ToolBase;

class UserController extends Controller
{
    protected        $serviceObj;
    protected        $userModel;
    protected static $tool;
    private static   $cacheExpire = 720; //minute

    /**
     * 实例化对象
     *
     * @author newton
     * @date 2018/7/5
     **/
    public function __construct()
    {
        $this->userModel  = new UserDBModel();
        $this->serviceObj = new ServiceModel();
    }


    /**
     * 获取用户信息
     *
     * @author newton
     * @date 2018/7/5
     * @run http://wx.dmtnewton.com/api/wx/user/getLoginCode
     **/
    public function getLoginCode(Request $request)
    {
        $code = trim($request->input('code'));
        if (empty($code)) {
            return (new ToolBase())->returnJson(1, 'void param: code is empty.');
        }
        $codeRes = $this->serviceObj->getWxSessionKey($code);
        if (empty($codeRes['session_key']) || empty($codeRes['openid'])) {
            ToolBase::writeLog([
                'class'    => __CLASS__,
                'function' => __FUNCTION__,
                'message'  => 'get session_key by code failed.'
            ]);
            return (new ToolBase())->returnJson(0, 'failed', $codeRes);
        }
        //记录用户openid
        $data   = ['openid' => $codeRes['openid']];
        $addRes = $this->userModel->insertUserData($data);
        if (empty($addRes)) {
            ToolBase::writeLog([
                'class'    => __CLASS__,
                'function' => __FUNCTION__,
                'message'  => 'add user info failed: ' . json_encode($data)
            ]);
        }

        session(['openid' => $codeRes['openid']]);
        session()->save();

        return (new ToolBase())->returnJson(1, 'success', $codeRes);
    }


    /**
     * 获取用户信息
     *
     * @author newton
     * @date 2018/7/5
     * @run http://wx.dmtnewton.com/api/wx/user/saveUserData
     **/
    public function saveUserData(Request $request)
    {
        $params                  = [];
        $params['encryptedData'] = trim($request->input('encryptedData'));
        $params['sessionKey']    = trim($request->input('sessionKey'));
        $params['iv']            = trim($request->input('iv'));
        $params['appid']         = ServiceModel::$appInfo['appid'];

//        $openid = session()->get('openid');   //todo void

        $openid = trim($request->input('openid'));
        if (empty($openid)) {
            return (new ToolBase())->returnJson(1, 'has no openid', []);
        }

        $data = $this->userModel->getUserPrivacyData($params);
        //记录用户信息
        $updateRes = $this->userModel->updateUserData($data, ['openid' => $openid]);
        if (empty($updateRes)) {
            ToolBase::writeLog([
                'class'    => __CLASS__,
                'function' => __FUNCTION__,
                'message'  => 'update user info failed: data->' . json_encode($data)
            ]);
        }

        return (new ToolBase())->returnJson(1, 'success', $updateRes);
    }


    /**
     * 添加用户信息
     *
     * @author newton
     * @date 2018/7/5
     * @run http://wx.dmtnewton.com/api/wx/user/addUser
     **/
    public function addUser(Request $request)
    {
        $params                  = [];
        $params['encryptedData'] = trim($request->input('encryptedData'));
        $params['sessionKey']    = trim($request->input('sessionKey'));
        $params['iv']            = trim($request->input('iv'));
        $code                    = trim($request->input('code'));
        $openid                  = trim($request->input('openid'));

        $params['appid'] = ServiceModel::$appInfo['appid'];
        $data            = UserModel::getUseDBrPrivacyData($params);
        //记录用户信息

        return (new ToolBase())->returnJson(1, 'success', $data);
    }

}
