<?php

namespace App\Http\Controllers\Api\WxMiniApp;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use App\Models\Ball\DoubleColorBallModel;
use App\Models\Ball\DoubleBallAllModel;
use Illuminate\Support\Facades\Log;
use App\Tool\ToolBase;

class BallController extends Controller
{
    protected      $ballObj;
    private static $cacheExpire = 720; //minute

    /**
     * 实例化对象
     *
     * @author newton
     * @date 2018/7/5
     **/
    public function __construct()
    {
        $this->ballObj = new DoubleColorBallModel;
    }


    /**
     * 获取球号列表
     *
     * @author newton
     * @date 2018/7/5
     * @run http://wx.dmtnewton.com/api/wx/getBallList
     **/
    public function getBallList(Request $request)
    {
        $page     = intval($request->input('page', 1));
        $pageSize = intval($request->input('pageSize', 7));

        $cacheKey = "DOUBLE_COLOR_BALL_GetBallList_PAGE{$page}_SIZE{$pageSize}";
        $cacheObj = Cache::store('redis');
        $data     = $cacheObj->get($cacheKey);
        if (!empty($data)) {
            return response()->make($data);
        }

        $total = $this->ballObj->getTotal();
        $list  = [];
        if (!empty($total)) {
            $param = [
                'orderKey' => 'add_time',
                'orderVal' => 'DESC',
                'offset'   => ($page - 1) * $pageSize,
                'limit'    => $pageSize
            ];
            $list  = $this->ballObj->getBallListByWhere($param);
            $list  = self::reData($list);
        }
        $pageSum = ceil($total / $pageSize);

        $data = [
            'total'    => $total,
            'pageSum'  => $pageSum,
            'pageSize' => $pageSize,
            'list'     => $list
        ];

        $cacheObj->put($cacheKey, json_encode($data), self::$cacheExpire);

        return response()->json($data);
    }


    /**
     * 获取近一月球号列表
     *
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/30
     **/
    public function getBallListMonth(Request $request)
    {
        $endDate = trim(addslashes($request->input('date')));
        empty($endDate) && $endDate = date('Y-m-d');
        $startDate = date('Y-m-d', strtotime('-1 month', strtotime($endDate)));

        $cacheKey = "DOUBLE_COLOR_BALL_GetBallListMonth_DATE{$endDate}";
        $cacheObj = Cache::store('redis');
        $data     = $cacheObj->get($cacheKey);
        if (!empty($data)) {
            return response()->make($data);
        }

        $param    = [
            'where'    => [
                ['date', '>=', $startDate],
                ['date', '<=', $endDate],
            ],
            'orderKey' => 'date',
            'orderVal' => 'ASC'
        ];
        $ballList = $this->ballObj->getBallListByWhere($param);
        $data     = [
            'r1'   => array_column($ballList, 'r1', null),
            'r2'   => array_column($ballList, 'r2', null),
            'r3'   => array_column($ballList, 'r3', null),
            'r4'   => array_column($ballList, 'r4', null),
            'r5'   => array_column($ballList, 'r5', null),
            'r6'   => array_column($ballList, 'r6', null),
            'b1'   => array_column($ballList, 'b1', null),
            'code' => array_column($ballList, 'code', null),
            'date' => array_column($ballList, 'date', null),
        ];

        $cacheObj->put($cacheKey, json_encode($data), self::$cacheExpire);

        return response()->json($data);
    }


    /**
     * 获取近一周球号列表
     *
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/30
     **/
    public function getBallListWeek(Request $request)
    {
        $endDate = trim(addslashes($request->input('date')));
        empty($endDate) && $endDate = date('Y-m-d');
        $startDate = date('Y-m-d', strtotime('-1 week', strtotime($endDate)));

        $cacheKey = "DOUBLE_COLOR_BALL_GetBallListWeek_DATE{$endDate}";
        $cacheObj = Cache::store('redis');
        $data     = $cacheObj->get($cacheKey);
        if (!empty($data)) {
            return response()->make($data);
        }

        $param    = [
            'where'    => [
                ['date', '>=', $startDate],
                ['date', '<=', $endDate],
            ],
            'orderKey' => 'date',
            'orderVal' => 'ASC'
        ];
        $ballList = $this->ballObj->getBallListByWhere($param);
        $data     = [];
        if (!empty($ballList)) {
            foreach ($ballList as $list) {
                $tmp         = [];
                $tmp['name'] = "第{$list['code']}期";
                $tmp['data'] = [
                    $list['r1'],
                    $list['r2'],
                    $list['r3'],
                    $list['r4'],
                    $list['r5'],
                    $list['r6'],
                    $list['b1']
                ];
                $data[]      = $tmp;
            }
        }

        $cacheObj->put($cacheKey, json_encode($data), self::$cacheExpire);

        return response()->json($data);
    }


    /**
     * 获取某一天的球号
     *
     * @author newton
     * @date 2018/7/5
     **/
    public function getBallByDate(Request $request)
    {
        $date = trim(addslashes($request->input('date', '')));
        empty($date) && $date = date('Y-m-d');
        $cacheKey = "DOUBLE_COLOR_BALL_GetBallByDate_DATE{$date}";
        $cacheObj = Cache::store('redis');
        $data     = $cacheObj->get($cacheKey);
        if (!empty($data)) {
            return response()->make($data);
        }

        $info = [];
        if (!empty($date)) {
            $info = $this->ballObj->getBallInfoByDate($date);
            $info = self::reData($info);
        }

        $cacheObj->put($cacheKey, json_encode($info), self::$cacheExpire);

        return response()->json($info);
    }


    /**
     * 按照日期查询球号
     *
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/7
     **/
    public function getBallByDateArea(Request $request)
    {
        $page      = intval($request->input('page', 1));
        $pageSize  = intval($request->input('pageSize', 10));
        $startDate = trim(addslashes($request->input('startDate', '')));
        $endDate   = trim(addslashes($request->input('endDate', '')));
        if (empty($startDate) || empty($endDate)) {
            return response()->json([]);
        }

        $cacheKey = "DOUBLE_COLOR_BALL_GetBallByDateArea_PAGE{$page}_SIZE{$pageSize}_SD{$startDate}_ED{$endDate}";
        $cacheObj = Cache::store('redis');
        $data     = $cacheObj->get($cacheKey);
        if (!empty($data)) {
            return response()->make($data);
        }

        $param = [
            'where'    => [
                ['date', '>=', $startDate],
                ['date', '<=', $endDate],
            ],
            'orderKey' => 'date',
            'orderVal' => 'DESC',
            'offset'   => $page,
            'limit'    => $pageSize
        ];
        $total = $this->ballObj->getTotal($param['where']);
        if (empty($total)) {
            $data = [];
        } else {
            $res  = $this->ballObj->getBallListByWhere($param);
            $data = self::reData($res);
        }
        $pageSum = ceil($total / $pageSize);
        $data    = [
            'total'    => $total,
            'pageSum'  => $pageSum,
            'pageSize' => $pageSize,
            'list'     => $data
        ];
        $cacheObj->put($cacheKey, json_encode($data), self::$cacheExpire);

        return response()->json($data);
    }


    /**
     * 获取随机球号
     *
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/7
     **/
    public function getRandBall(Request $request)
    {
        $limit   = intval($request->input('limit'));
        $avgInfo = (new DoubleBallAllModel())->getRandBallByAvg($limit);
        $data    = json_decode(json_encode($avgInfo), true);

        return response()->json($data);
    }


    /**
     * 扩充返回数据
     *
     * @param array $data
     * @return array
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/13
     **/
    private static function reData($data = array())
    {

        if (!empty($data)) {
            $weekCfg = config('common.date.week');
            if (is_array($data) && isset($data[0]['date'])) {
                foreach ($data as $k => $v) {
                    $data[$k]['week'] = $weekCfg[date('D', strtotime($v['date']))];
                }
            } else {
                $data['week'] = $weekCfg[date('D', strtotime($data['date']))];
            }
        }

        return $data;
    }
}
