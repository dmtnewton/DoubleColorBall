<?php

namespace App\Models\Wechat;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AllBallModel extends Model
{
    protected static $dbObj;
    protected static $database  = 'mysql';
    protected static $tableName = 'all_ball';

    /**
     * ���ݿⶨ��
     *
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/3
     **/
    public function __construct()
    {
        self::init();
    }


    /**
     * ��ʼ�����ݿ����
     *
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/3
     **/
    public function init()
    {
        self::$dbObj = DB::connection(self::$database)->table(self::$tableName);

        return self::$dbObj;
    }


    /**
     * ����������ѯ����б�
     *
     * @param array $params
     * @return array
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/3
     **/
    public function getBallListByWhere($params = array())
    {
//        $field = $params['field'];
//        $res = self::$dbObj->where('nickname', 'newton')->get();

        return $res;
    }


    /**
     * �����¼
     *
     * @param array $data
     * @return array
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/3
     **/
    public function insertData($data = array())
    {
        self::$dbObj->setRelations($data);
        self::$dbObj->push();

        return true;
    }


    /**
     * ���������¼
     *
     * @param array $data
     * @return bool
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/3
     **/
    public function multiInsertData($data = array())
    {
        if (empty($data[0])) {
            echo ">>> ERROR: multiInsertData : empty data." . PHP_EOL;
            return false;
        }
        $res = self::$dbObj->insert($data);

        return $res;
    }

}
