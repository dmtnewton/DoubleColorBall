<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DBController extends Controller
{

    /**
     * 调起中间件
     *
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/5/2
     **/
    public function __construct()
    {
//        $this->middleware('token');
//        $this->middleware('token')->only('show'); //只对该方法生效
//        $this->middleware('token')->except('show');   //只对该方法以外的方法生效
//        $this->middleware('token')->only(['show', 'pm']);   //只对指定方法生效
//        $this->middleware('token')->except(['show', 'pm']);   //只对指定方法以外的方法生效
        //匿名中间件
//        $this->middleware(function ($request, $next){
//            if (!is_numeric($request->input('id'))) {
//                throw new NotFoundHttpException();
//            }
//            return $next($request);
//        });
    }


    /**
     * 主页
     *
     * @param int $uid
     * @return bool
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/6/26
     **/
    public function index($uid = 0)
    {
        echo 'uid:<pre>' . PHP_EOL;
        print_r($uid);
        echo '</pre>' . PHP_EOL;

        //连接MySQL读库
        $dbObj = DB::connection('mysql::read');

        //原生SQL查询-占位符
        $val = $dbObj->select('select * from `user` WHERE `uid` =?', [$uid]);

        echo 'val:<pre>' . PHP_EOL;
        print_r($val);
        echo '</pre>' . PHP_EOL;

        //原生SQL查询-命名绑定
        $val2 = DB::select('select * from `user` WHERE `uid`= :uid', ['uid' => $uid]);
        echo 'val2:<pre>' . PHP_EOL;
        print_r($val2);
        echo '</pre>' . PHP_EOL;

        //原生SQL插入
        $val3 = DB::insert('INSERT INTO `user`(`uid`, `name`,`mobile`) VALUES (?, ?, ?)', ['A03', 'tom', 13301010210]);
        echo 'val3:<pre>' . PHP_EOL;
        print_r($val3);
        echo '</pre>' . PHP_EOL;

        //原生SQL-没有返回值的SQL
        $val4 = DB::statement('drop table wx_user');
        var_dump($val4);

        //查询构建器
        $res = DB::table('user')->where('nickname', 'newton')->value('email');
        echo 'res:<pre>' . PHP_EOL;
        var_dump($res);
        echo '</pre>' . PHP_EOL;

        //获取数据列值列表
        $values = DB::table('user')->pluck('name');
        echo 'values:<pre>' . PHP_EOL;
        print_r($values);
        echo '</pre>' . PHP_EOL;

        exit;
        return true;
    }


    /**
     * PDO
     *
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/6/29
     **/
    public function pdo()
    {

        $pdo = DB::connection('mysql::read')->getPdo();
        echo 'db-pdo:<pre>' . PHP_EOL;
        print_r($pdo);
        echo '</pre>' . PHP_EOL;

        return true;
    }

}
