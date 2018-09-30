<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Base;
use App\Models\Wechat\ServiceModel;
use App\Tool\ToolBase;

class UserDBModel extends Base
{
    protected static $dbObj;
    protected static $database  = 'db_wx';
    protected static $tableName = 'user';
    protected static $prefix    = 'wx';

    /**
     * 初始化
     *
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/3
     **/
    public function __construct()
    {
        //初始化链接
        self::init();
    }


    /**
     * 初始化数据库链接
     *
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/3
     **/
    public static function init()
    {
        self::$dbObj = DB::connection(self::$database)->table(self::$tableName);
        if (empty(self::$dbObj)) {
            $error = __CLASS__ . ' . ' . __FUNCTION__ . " : can't connect MySQL database:" . self::$database . ", table:" . self::$tableName . PHP_EOL;
            throw new InvalidArgumentException($error);
        }

        return self::$dbObj;
    }


    /**
     * 查询用户ID
     *
     * @param array $data
     * @return array
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/7
     **/
    public function getUserInfoByData($data = [])
    {
        if (empty($data)) {
            return false;
        }
        $res = self::$dbObj->where($data)->first();

        return ToolBase::ObjToArray($res);
    }


    /**
     * 根据昵称查询用户ID
     *
     * @param string $uid
     * @return array
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/7
     **/
    public function getUserInfoByUid($uid = '')
    {
        if (empty($uid)) {
            return false;
        }
        $res = self::$dbObj->where('uid', '=', $uid)->first();

        return ToolBase::objToArray($res);
    }


    /**
     * 查询用户列表
     *
     * <pre>
     * array    where
     * string   orderKey
     * string   orderVal
     * int      offset
     * int      pageSize
     * </pre>
     * @param array $params
     * @return array
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/3
     **/
    public function getUserListByWhere($params = array())
    {
        if (empty($params) || !is_array($params)) {
            return false;
        }
        $where   = (array)$params['where'];
        $objLink = self::$dbObj->where($where);

        if (!empty($params['orderKey']) && !empty($params['orderVal'])) {
            $orderKey = (string)$params['orderKey'];
            $orderVal = (string)$params['orderVal'];
            $objLink->orderBy($orderKey, $orderVal);
        }

        if (isset($params['offset']) && !empty($params['limit'])) {
            $offset = intval($params['offset']);
            $limit  = intval($params['limit']);

            $objLink->skip($offset)->take($limit);
        }

        $res = $objLink->get();

        return $res;
    }


    /**
     * 插入用户数据
     *
     * @param array $data
     * @return bool
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/3
     **/
    public function insertUserData($data = array())
    {
        if (empty($data) || !is_array($data)) {
            return false;
        }

        $checkExist = $this->getUserInfoByData($data);
        if (empty($checkExist)) {
            $data['add_time'] = time();
            $data['uid']      = self::getUserUid();
            $res              = self::$dbObj->insert($data);
        } else {
            $res = true;
        }

        return $res;
    }


    /**
     * 更新用户数据
     *
     * @param array $data
     * @param array $where
     * @return bool
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/3
     **/
    public function updateUserData($data = array(), $where = array())
    {
        if (empty($data) || empty($where)) {
            return false;
        }
        $checkExist = $this->getUserInfoByData($where);
        if (empty($checkExist)) {
            $data = array_merge($data, $where);
            $res  = $this->insertUserData($data);
        } else {
            $res = self::$dbObj->update($data, $where);
        }

        return $res;
    }


    /**
     * 获取用户ID
     *
     * @return string
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/9/6
     **/
    public static function getUserUid()
    {
        return uniqid(self::$prefix);
    }


    /**
     * 获取用户隐私信息
     *
     * @param array $params
     * @return array
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/9/6
     **/
    public function getUserPrivacyData($params = array())
    {
        if (empty($params['appid']) || empty($params['sessionKey']) || empty($params['encryptedData']) || empty($params['iv'])) {
            ToolBase::writeLog([
                'class'    => __CLASS__,
                'function' => __FUNCTION__,
                'message'  => 'decry user data failed : there is empty in (appid, sessionKey, encryptedData, iv).'
            ]);
            return false;
        }
        $data      = [];
        $decryData = (new ServiceModel())->wxDecryData($params['appid'], $params['sessionKey'], $params['encryptedData'], $params['iv'], $data);
        if (0 != $decryData) {
            ToolBase::writeLog([
                'class'    => __CLASS__,
                'function' => __FUNCTION__,
                'message'  => 'decry user data failed: ServiceModel::wxDecryData().'
            ]);
        }

        $resData = self::_formatWxData(json_decode($decryData, true));

        return $resData;
    }


    /**************************** PRIVATE FUNCTION ****************************/


    /**
     * 格式化微信返回数据
     *
     * @param array $data
     * @return array
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/9/26
     **/
    public function _formatWxData($data = array())
    {
        if (empty($data) || !is_array($data)) {
            return [];
        }

        $res = [];
        foreach ($data as $key => $val) {
            $res['openid']   = $data['openId'];
            $res['nickname'] = $data['nickName'];
            $res['gender']   = $data['gender'];
            $res['language'] = $data['language'];
            $res['city']     = $data['city'];
            $res['province'] = $data['province'];
            $res['country']  = $data['country'];
            $res['avatar']   = $data['avatarUrl'];
        }

        return $res;
    }

}
