<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
ini_set("display_errors", true);
error_reporting(E_ALL);
//Route::get('user/{id}', 'UserController@show')->middleware('token');
//Route::match(['get', 'post'], 'list', function(){
//    return ['a' => 1, 'b' => 2, 'c' => ['A','B']];
//});
Route::any('user/{id}', 'UserController@index')->middleware('token');
Route::get('test', 'Ball\BaseController@test');
Route::get('db/{id}', 'DBController@index');
/*
Route::match(['get', 'post'], 'form/goto', function (){
    return '<form method="POST" action="/form/back" enctype="multipart/form-data">' . csrf_field() . '
    <p><input type="text" name="name" value="Tom"></p>
    <p><input type="text" name="age" value="18"></p>
    <p><button type="submit">提交</button></p>
</form>';
});

Route::match(['get', 'post'], 'form/back', function (){
    //回到提交前的表单
    return back()->withInput();
});*/

/*
Route::match(['get', 'post'], '/cookie', function(){
    Cookie::queue('laravel', '5.5');

    return response('cookie queue')->cookie('user', 'Newton', 10);
});
*/

/*
use Illuminate\Http\Request;
Route::get('/{id}', function (Request $request, $id) {
    echo 'id:<pre>' . PHP_EOL;
    print_r($id);
    echo '</pre>' . PHP_EOL;

    echo 'input:<pre>' . PHP_EOL;
    print_r($request->input());
    echo '</pre>' . PHP_EOL;
});
*/

/*
Route::match(['get', 'post'], 'list', function(){
    return ['a' => 1, 'b' => 2, 'c' => ['A','B']];
});
*/
/*
Route::match(['get', 'post'], 'list', function (){
    return response(['a' => 1, 'b' => 2, 'c' => ['A','B']], 200)
        ->header('Content-Type', 'text/plain')
        ->header('x-header-info', 'header-value')
        ->cookie('uid', 'Tom', 60);
});
*/
/*
Route::match(['get', 'post'], 'list', function (){
    $resultEcho   = 'echo Cookie info';
    $resultStatus = 200;

    $cookieName   = 'userName';
    $cookieVal    = 'Tom';
    $cookieDuring = 60; //min

    $name = 'password';
    $value = '123';
    $minutes = 10;
    $path = '/';
    $domain = 'wx.dmtnewton.com';
    $secure = true;
    $httpOnly = false;
    return response($resultEcho, $resultStatus)
        ->header('Content-Type', 'text/plain')
        ->header('x-header-info', 'header-value')
        ->cookie($cookieName, $cookieVal, $cookieDuring)
        ->cookie($name, $value, $minutes, $path, $domain, $secure, $httpOnly);
});
*/

/*
//VISIT: http://wx.dmtnewton.com/pm/1?token=wx.dmtnewton.com&a=b
Route::match(['get', 'post'], 'pm/{id}', 'UserController@pm');

Route::match(['get', 'post'], 'show/{id}', function () {
    return '<form method="POST" action="/pm/123" enctype="multipart/form-data">' . csrf_field() . '
    <p><input type="text" name="name" value="Tom"></p>
    <p><input type="text" name="age" value="18"></p>
    <p><input type="text" name="product[]" value="A"></p>
    <p><input type="text" name="product[]" value="B"></p>
    <p><input type="file" name="new_file" /></p>
    <p><button type="submit">提交</button></p>
</form>';
});
*/

/*
Cookie::queue(Cookie::make('name', 'value', 60));




Route::get('cookie/add', function(){
    $cookie = cookie('B-name', 'tom', 60);
    return response('Hello Newton')->cookie($cookie);
});

Route::get('cookie/get', function(\Illuminate\Http\Request $request){
    $cookie = $request->cookie('name');

    echo 'B-name:<pre>' . PHP_EOL;
    print_r($request->cookie('B-name'));
    echo '</pre>' . PHP_EOL;

    dd($cookie);
});*/

//Route::get('show/{id}', 'ShowProfile');

//Route::resource('posts', 'PostController');
//Route::resource('posts', 'PostController', ['only' => ['index', 'store']]);
//Route::resource('posts', 'PostController', ['names' => ['create' => 'posts.build']]);
//Route::resource('posts', 'PostController', ['parameters' => ['posts' => 'admin_user']]);

//Route::resource([
//    'photo' => 'PhotoController',
//    'posts' => 'PostController'
//]);

/*
Route::get('wechat', 'WeChatController@checkAccept');
//VISIT: http://wx.dmtnewton.com/user/1
Route::get('user/{id}', 'UserController@show')->middleware('token');

Route::get('show/{id}', 'ShowProfile');

Route::get('wx/check', 'WeChat\WXBaseController@test');*/

/*
Route::get('check_csrf_token', function () {
    return '<form method="POST" action="/accept_from">' . csrf_field() . '<button type="submit">提交</button></form>';
})->middleware('csrf');

Route::get('/wechat', function (){
    return 'hello laravel!';
});
*/

/*
//路由前缀
//VISIT: http://wx.dmtnewton.com/admin/menu
Route::prefix('admin')->group(function(){
    Route::get('menu', function(){
        return 'The Url is belong of Admin. ';
    });
});

//分组-命名空间
//Route::namespace('Admin')->group(function(){
//    echo '<pre>' . PHP_EOL;
//    print_r('Controllers');
//    echo '</pre>' . PHP_EOL;
//});

//使用中间件分组
Route::middleware(['first', 'second'])->group(function (){
    Route::get('/', function (){
        return 'middleware: one';
    });
    Route::get('/user', function (){
        return 'middleware: two';
    });
});

//子域名路由
//VISIT: http://wx.dmtnewton.com/user_/2
Route::domain('{account}.dmtnewton.com')->group(function(){
    Route::get('user_/{id}', function($account, $id){
        return "There is {$account} page of user {$id}";
    });
});

//命名路由
Route::get('user/profile', function(){
    return 'URL: ' . route('profile');
})->name('profile');

//重定向到命名的路由
VISIT: http://wx.dmtnewton.com/redirect -> http://wx.dmtnewton.com/user/profile
Route::get('redirect', function(){
    //生成重定向
    return redirect()->route('profile');
});

//VISIT: http://wx.dmtnewton.com/user/123/prof
Route::get('user/{id}/prof', function ($id = 0){
    $url = route('prof', ['id' => $id]);
    return $url;
})->name('prof');

//VISIT: http://wx.dmtnewton.com/tom
Route::get('/{name}', function ($name = 'newton') {
    return 'Hello Laravel By ' . $name;
//    return view('welcome');
});

//VISIT: http://wx.dmtnewton.com/tom/by/6
Route::get('/{name}/by/{id}', function ($name = 'newton', $id = 1) {
    return "NAME: {$name} , ID: {$id}";
});

//页面访问时，如果没有参数，添加默认
//VISIT: http://wx.dmtnewton.com/
Route::get('/{age?}', function ($age = 18) {
    return "Age: {$age}";
});

//VISIT: http://wx.dmtnewton.com/user/123
Route::get('/user/{id}', function($id){
    return "USER-ID: {$id}";
})->where('id', '\d+');

//正则约束
//VISIT: http://wx.dmtnewton.com/user/123/_/English
Route::get('/user/{id}/_/{type}', function($id, $type){
    return "ID: {$id}, TYPE: {$type}";
})->where(['id' => '\d+', 'type' => '[\w]+']);

//表单提交时，需要令牌
//VISIT: http://wx.dmtnewton.com/name
Route::match(['get', 'post'], 'token', function (){

    return '<form method="post" action="/">
' . csrf_field() . '
<button type="submit">Submit_BTN</button>
</form>';
});

//路由重定向
//Route::redirect('/', '/name', 301);

Route::view('/view', 'welcome', ['name' => 'VIEW -> NEWTON']);

//路由视图
Route::get('/', function(){
    return view('welcome', ['name' => 'GET -> NEWTON']);
});

//VISIT: http://wx.dmtnewton.com/user/1
Route::get('user/{id}', 'UserController@show');

//指定中间件
//VISIT: http://wx.dmtnewton.com/pm/1?token=wx
Route::get('pm/{id}', function($id){
    return "program: id : {$id}";
})->middleware('token');

//指定多个中间件
//VISIT: http://wx.dmtnewton.com/pm/1?token=wx.dmtnewton.com&a=b
Route::get('pm/{id}', 'UserController@pm')->middleware('token', 'throttle');

//分组中间件
//VISIT: http://wx.dmtnewton.com/pm/1
Route::get('pm/{id}', function($id){
    return "Middleware group: {$id}";
})->middleware('web');

//VISIT: http://wx.dmtnewton.com/web/1
Route::group(['middleware' => ['web']], function (){
    Route::get('web/{id}', function($id){
        return "Middleware group web : {$id}";
    });
});
*/



Route::get('/', 'IndexController@index');