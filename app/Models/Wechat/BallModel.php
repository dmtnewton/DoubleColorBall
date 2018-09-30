<?php

namespace App\Models\Ball;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Tool\ToolBase;

class BallModel extends Model
{
    protected static $dbObj;

    /**
     * ���ݿⶨ��
     *
     * @param string $tableName
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/3
     **/
    public function __construct($tableName = '')
    {
        $tableName   = empty($tableName) ? 'ball' : $tableName;
        self::$dbObj = DB::connection('db_wx')->table($tableName);
    }


    /**
     * ����ʱ���ѯ���
     *
     * @param string $date
     * @return array
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/7
     **/
    public function getInfoByDate($date = '')
    {
        if (empty($date)) {
            return false;
        }
        $res = self::$dbObj->whereDate('date', $date)->frist();

        return $res;
    }


    /**
     * ����ָ���ֶη�Χ��ѯ
     *
     * <pre>
     * string field
     * array  between
     * </pre>
     * @param array $params
     * @return array
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/7
     **/
    public function getListByFieldBetween($params = array())
    {
        if (empty($params['field']) || empty($params['between'])) {
            return false;
        }
        $field   = (string)$params['field'];
        $between = (array)$params['between'];
        $res     = self::$dbObj->whereBetween($field, $between)->get();

        return $res;
    }


    /**
     * ����ָ���ֶη�Χ��ѯ
     *
     * <pre>
     * string field
     * array  in
     * </pre>
     * @param array $params
     * @return array
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/7
     **/
    public function getListByFieldIn($params = array())
    {
        if (empty($params['field']) || empty($params['in'])) {
            return false;
        }
        $field = (string)$params['field'];
        $in    = (array)$params['in'];
        $res   = self::$dbObj->whereIn($field, $in)->get();

        return $res;
    }


    /**
     * ����������ѯ����б�
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
    public function getListByWhere($params = array())
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
     * ��ȡ����б�
     *
     * <pre>
     * array where
     * int   limit
     * </pre>
     * @param array $params
     * @return array
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/3
     **/
    public function getListByOrderRand($params = array())
    {
        if (empty($params['where']) || !is_array($params['where'])) {
            return false;
        }
        $where = (array)$params['where'];
        $limit = (int)$params['limit'];
        $res   = self::$dbObj->where($where)->inRandomOrder()->limit($limit);

        return $res;
    }


    /**
     * ����������ȡ����
     *
     * <pre>
     * array where
     * </pre>
     * @param array $params
     * @return array
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/7
     **/
    public function getTotalByWhere($params = array())
    {
        if (empty($params['where']) || !is_array($params['where'])) {
            return false;
        }
        $where = (array)$params['where'];
        $res   = self::$dbObj->where($where)->count();

        return $res;
    }


    /**
     * �����¼(֧�ֶ���)
     *
     * @param array $data
     * @return bool
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/3
     **/
    public function multiInsertData($data = array())
    {
        if (empty($data) || !is_array($data)) {
            return false;
        }
        $res = self::$dbObj->insert($data);

        return $res;
    }


}
