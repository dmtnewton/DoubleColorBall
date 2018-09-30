<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class BallController extends Controller
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
        $config = config('cache.stores.redis');
        echo 'org-config:<pre>' . PHP_EOL;
        print_r($config);
        echo '</pre>' . PHP_EOL;

//        $cacheObj = Redis::set();
        Redis::set('name', 'Taylor');
        echo 'User-get:<pre>' . PHP_EOL;
        print_r(Redis::get('name'));
        echo '</pre>' . PHP_EOL;

        $values = Redis::lrange('names', 5, 10);
        echo 'user-values:<pre>' . PHP_EOL;
        print_r($values);
        echo '</pre>' . PHP_EOL;

        $cacheKey = 'abc';
        $obj = Cache::store('redis');
        $obj->put($cacheKey, 'ABC', 10);
        $val = $obj->get($cacheKey);
        echo 'val:<pre>' . PHP_EOL;
        print_r($val);
        echo '</pre>' . PHP_EOL;

        exit;
        return true;
    }


    /**
     * show user info
     *
     * @param int $id
     * @return array
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/4/24
     **/
    public function show(Request $request, $id = 0)
    {
        echo 'name:<pre>' . PHP_EOL;
        print_r($request->input('name'));
        echo '</pre>' . PHP_EOL;

        echo 'query:<pre>' . PHP_EOL;
        print_r($request->query('age'));
        echo '</pre>' . PHP_EOL;
        exit;
        return view('user.profile', ['user' => User::findOrFail($id)]);
    }

    
    /**
     * 将Request作为参数注入
     *
     * @param int $id
     * @return array
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/4/25
     * @run http://wx.dmtnewton.com/pm/1?token=wx.dmtnewton.com&a=b
     **/
    public function pm(Request $request, $id = 0)
    {
        //验证是否存在有效的上传文件
        if ($request->hasFile('new_file') && $request->file('new_file')->isValid()) {

            //文件对象
            $fileObj = $request->file('new_file');
            //扩展名
            $extension = $fileObj->extension();
            //保存文件
            $uploadFile = $fileObj->store("public/{$extension}");
            echo 'file:<pre>' . PHP_EOL;
            print_r($uploadFile);
            echo '</pre>' . PHP_EOL;

            //将文件另存为
            $saveDir    = "public/{$extension}";
            $saveFile   = "other.{$extension}";
            $saveAsFile = $fileObj->storeAs($saveDir, $saveFile);
            echo 'saveAsFile:<pre>' . PHP_EOL;
            print_r($saveAsFile);
            echo '</pre>' . PHP_EOL;

        }
        exit;

        //接收文件信息：方法二
        echo 'new_file:<pre>' . PHP_EOL;
        print_r($request->new_file);
        echo '</pre>' . PHP_EOL;

        //检查是否存在文件
        $checkExist = $request->hasFile('new_file');
        echo 'checkExist:<pre>' . PHP_EOL;
        var_dump($checkExist);
        echo '</pre>' . PHP_EOL;

        //检查是否上传成功
        $isSuccess = $request->file('new_file')->isValid();
        echo 'isSuccess:<pre>' . PHP_EOL;
        var_dump($isSuccess);
        echo '</pre>' . PHP_EOL;

        //获取文件路径
        $filePath = $request->new_file->path();
        echo 'filePath:<pre>' . PHP_EOL;
        print_r($filePath);
        echo '</pre>' . PHP_EOL;

        //文件扩展名
        //extension 方法可以基于文件内容判断文件扩展名，该扩展名可能会和客户端提供的扩展名不一致：
        $extension = $request->file('new_file')->extension();
        echo 'extension:<pre>' . PHP_EOL;
        print_r($extension);
        echo '</pre>' . PHP_EOL;
        exit;


        $name = Cookie::get('name');
        echo 'cookie-name:<pre>' . PHP_EOL;
        print_r($name);
        echo '</pre>' . PHP_EOL;

        echo 'cookie-name:<pre>' . PHP_EOL;
        print_r($request->cookie('name'));
        echo '</pre>' . PHP_EOL;
        exit;

        $request->flashOnly(['name', '_token']);
        echo 'session:<pre>' . PHP_EOL;
        print_r($request->session());
        echo '</pre>' . PHP_EOL;
        exit;

        //判断参数是否存在并且参数值不为空
        $param = $request->filled('name');
        echo 'filled - param:<pre>' . PHP_EOL;
        var_dump($param);
        echo '</pre>' . PHP_EOL;


        //检查是否存在指定请求参数
        $address = $request->has('address');
        echo 'address:<pre>' . PHP_EOL;
        var_dump($address);
        echo '</pre>' . PHP_EOL;

        //获取指定参数值
        echo 'input-only:<pre>' . PHP_EOL;
        print_r($request->only(['name', 'age']));
        echo '</pre>' . PHP_EOL;

        //获取指定参数以外的参数值
        echo 'input-except:<pre>' . PHP_EOL;
        print_r($request->except('name', 'age'));
        echo '</pre>' . PHP_EOL;
        exit;

        echo 'name:<pre>' . PHP_EOL;
        print_r($request->input('name'));
        echo '</pre>' . PHP_EOL;

        echo 'query:<pre>' . PHP_EOL;
        print_r($request->query('age'));
        echo '</pre>' . PHP_EOL;

        echo 'query-product:<pre>' . PHP_EOL;
        print_r($request->query('product'));
        echo '</pre>' . PHP_EOL;

        echo '->name:<pre>' . PHP_EOL;
        print_r($request->product);
        echo '</pre>' . PHP_EOL;

        //可以使用“.”来访问数组输入
        $input = $request->input('product.0');
        $names = $request->input('product.*');

        echo 'input:<pre>' . PHP_EOL;
        print_r($input);
        echo '</pre>' . PHP_EOL;

        echo 'names:<pre>' . PHP_EOL;
        print_r($names);
        echo '</pre>' . PHP_EOL;
        exit;

        //检查请求路径是否符合规范
        $checkPath = $request->is('pm/*');
        echo 'checkPath:<pre>' . PHP_EOL;
        var_dump($checkPath);
        echo '</pre>' . PHP_EOL;

        //可以直接用$request调用请求的参数
        echo 'token::<pre>' . PHP_EOL;
        print_r($request->token);
        echo '</pre>' . PHP_EOL;

        //路由中的参数
        echo 'id:<pre>' . PHP_EOL;
        print_r($id);
        echo '</pre>' . PHP_EOL;

        //请求的路径
        //此处$request使用必须放在Request::capture()->request方法之前，因为->request会覆盖$request
        $_path = $request->path();
        echo '_path:<pre>' . PHP_EOL;
        print_r($_path);
        echo '</pre>' . PHP_EOL;

        /************** 以下为测试部分，建议使用依赖注入 *************/

        //请求的路径
        $path = Request::capture()->path();
        echo 'path:<pre>' . PHP_EOL;
        print_r($path);
        echo '</pre>' . PHP_EOL;

        //获取所有的HTTP请求参数
        $request = Request::capture()->request->all();
        echo 'request:<pre>' . PHP_EOL;
        print_r($request);
        echo '</pre>' . PHP_EOL;

        //获取请求参数的另一种方式，摸索出来的，比较累赘，不过在没有依赖注入的时候可以用
        $token = Request::capture()->request->get('token');
        echo 'token:<pre>' . PHP_EOL;
        print_r($token);
        echo '</pre>' . PHP_EOL;

        //当前请求的URL
        $url = Request::capture()->url();
        echo 'url:<pre>' . PHP_EOL;
        print_r($url);
        echo '</pre>' . PHP_EOL;

        //当前请求的全路径URL
        $fullUrl = Request::capture()->fullUrl();
        echo 'fullUrl:<pre>' . PHP_EOL;
        print_r($fullUrl);
        echo '</pre>' . PHP_EOL;

        //获取请求的方法
        $method = Request::capture()->method();
        echo 'method:<pre>' . PHP_EOL;
        print_r($method);
        echo '</pre>' . PHP_EOL;

        //检查请求方式是否GET
        $check = Request::capture()->isMethod('GET');
        echo 'check-method:<pre>' . PHP_EOL;
        var_dump($check);
        echo '</pre>' . PHP_EOL;

        exit;
    }
}
