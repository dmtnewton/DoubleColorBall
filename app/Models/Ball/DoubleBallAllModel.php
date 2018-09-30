<?php

namespace App\Models\Ball;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Base;

class DoubleBallAllModel extends Base
{
    protected static $dbObj;
    protected static $database  = 'db_ball';
    protected static $tableName = 'double_ball_all';

    /**
     * 数据库定义
     *
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/3
     **/
    public function __construct()
    {
        //连接MySQL读库
        self::init();
    }


    /**
     * 初始化数据库对象
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
     * 根据时间查询球号
     *
     * @param string $date
     * @return array
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/7
     **/
    public function getBallInfoByDate($date = '')
    {
        if (empty($date)) {
            return false;
        }
        $res = self::$dbObj->whereDate('date', $date)->first();

        return $res;
    }


    /**
     * 根据指定字段范围查询
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
    public function getBallListByFieldBetween($params = array())
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
     * 根据指定字段范围查询
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
    public function getBallListByFieldIn($params = array())
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
     * 根据条件查询球号列表
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
    public function getBallListByWhere($params = array())
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
     * 获取随机列表
     *
     * @param array $params
     * @return array
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/3
     **/
    public function getBallListByOrderRand($params = array())
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
     * 插入记录(支持多条)
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


    /**
     * 根据极限范围获取随机值
     *
     * <pre>
     * int limit
     * </pre>
     * @param array $params
     * @return array
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/7
     **/
    public function getBallByRandIds($params = array())
    {
        $limit   = empty($params['limit']) ? 5 : intval($params['limit']);
        $where   = self::_getAvgOffset();
        $randIds = self::_getRandIdsByMaxMin($limit);

        $data = $this->init()->select('id', 'number')->whereIn('id', $randIds)->where($where)->get();

        $res = json_decode(json_encode($data), true);

        return $res;
    }


    /**
     * 根据平均偏移量获取随机值
     *
     * <pre>
     * int limit
     * </pre>
     * @param array $params
     * @return array
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/7
     **/
    public function getRandBallByAvg($params = array())
    {
        $limit = empty($params['limit']) ? 5 : intval($params['limit']);
        $where = self::_getAvgOffset();

        $data = $this->init()->selectRaw('id, number')->where($where)->inRandomOrder()->limit($limit)->get();

        $res = json_decode(json_encode($data), true);

        return $res;
    }


    /**
     * 获取平均量
     *
     * @return array
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/7
     **/
    private function _getAvgInfo()
    {
        $sql   = "count(id) AS total,
                sum(`sum`) / count(id) AS avg_sum,
                sum(`avg`) / count(id) AS avg_avg,
                sum(`max`) / count(id) AS avg_max,
                sum(`min`) / count(id) AS avg_min,
                sum(poor)	/ count(id) AS avg_poor,
                sum(weight) / count(id) AS avg_weight,
                sum(weight_num) / count(id) AS avg_weight_num";
        $raw   = DB::raw($sql);
        $where = [
            ['code', '!=', 0],
            ['date', '!=', ''],
        ];
        $data  = self::$dbObj->select($raw)->where($where)->first();

        return json_decode(json_encode($data), true);
    }


    /**
     * 获取平均偏移量查询条件
     *
     * @return array
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/8
     **/
    private function _getAvgOffset()
    {

        $avgInfo = $this->_getAvgInfo();

        $offset = [
            'sum'        => ceil($avgInfo['avg_sum']),
            'avg'        => ceil($avgInfo['avg_avg']),
            'max'        => ceil($avgInfo['avg_max']),
            'min'        => ceil($avgInfo['avg_min']),
            'poor'       => ceil($avgInfo['avg_poor']),
            'weight'     => ceil($avgInfo['avg_weight']),
            'weight_num' => ceil($avgInfo['avg_weight_num']),
        ];

        $where = [
            ['sum', '>=', $offset['sum'] - 10],
            ['sum', '<=', $offset['sum'] + 10],
            ['avg', '>=', $offset['avg'] - 5],
            ['avg', '<=', $offset['avg'] + 5],
            ['max', '>=', $offset['max'] - 5],
            ['max', '<=', $offset['max'] + 5],
            ['min', '>=', $offset['min'] - 5],
            ['min', '<=', $offset['min'] + 5],
            ['poor', '>=', $offset['poor'] - 8],
            ['poor', '<=', $offset['poor'] + 8],
            ['weight', '>=', $offset['weight'] - 3],
            ['weight', '<=', $offset['weight'] + 3],
            ['weight_num', '>=', $offset['weight_num'] - 2],
            ['weight_num', '<=', $offset['weight_num'] + 2],
            ['code', '=', 0],
            ['date', '=', 0],
        ];

        return $where;
    }


    /**
     * 根据极限值获取随机ID
     *
     * @param int $limit
     * @return array
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/8
     **/
    private function _getRandIdsByMaxMin($limit = 5)
    {
        $where = self::_getAvgOffset();

        $fieldMax = DB::raw("MAX(id) AS max_id");
        $fieldMin = DB::raw("MIN(id) AS min_id");
        $area     = $this->init()->select($fieldMax, $fieldMin)->where($where)->first();
        $randIds  = [];
        if (!empty($area)) {
            $maxId = $area->max_id - 1;
            $minId = $area->min_id + 1;
            for ($i = 0; $i < $limit; $i++) {
                $randIds[] = rand($minId, $maxId);
            }
        }

        return $randIds;
    }

}
