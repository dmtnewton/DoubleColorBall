<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BallModel extends Model
{
    protected $dbObj;

    /**
     * 数据库定义
     *
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/3
     **/
    public function __construct()
    {
        //连接MySQL读库
//        $this->dbObj = DB::connection('mysql')->table('double_ball_all');
        $this->dbObj = DB::connection('mysql')->table('all_ball');
    }


    /**
     * 根据条件查询球号列表
     *
     * @param array $params
     * @return array
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/3
     **/
    public function getBallListByWhere($params = array())
    {
//        $field = $params['field'];
//        $res = $this->dbObj->where('nickname', 'newton')->get();

        return $res;
    }


    /**
     * 插入记录
     *
     * @param array $data
     * @return array
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/3
     **/
    public function insertData($data = array())
    {
        $this->dbObj->setRelations($data);
        $this->dbObj->push();

        return true;
    }


    /**
     * 插入多条记录
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
        $res = $this->dbObj->insert($data);

        return $res;
    }

}
