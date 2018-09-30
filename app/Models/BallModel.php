<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BallModel extends Model
{
    protected $dbObj;

    /**
     * ���ݿⶨ��
     *
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/3
     **/
    public function __construct()
    {
        //����MySQL����
//        $this->dbObj = DB::connection('mysql')->table('double_ball_all');
        $this->dbObj = DB::connection('mysql')->table('all_ball');
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
//        $res = $this->dbObj->where('nickname', 'newton')->get();

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
        $this->dbObj->setRelations($data);
        $this->dbObj->push();

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
        $res = $this->dbObj->insert($data);

        return $res;
    }

}
