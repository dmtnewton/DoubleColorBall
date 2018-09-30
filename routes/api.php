<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**************** TEST ****************/
Route::middleware(['test'])->group(function () {
    Route::apiResource('post', 'Api\PostController');

    Route::get('wx/test', 'WeChat\WXBaseController@test');
});


/**************** WeChat ****************/
Route::middleware(['wechat'])->group(function () {
    //WeChat api verify
    Route::match(['get', 'post'], 'wx/check', 'WeChat\WXBaseController@check'); //Right
});


/**************** Ball ****************/
Route::middleware(['ball'])->group(function () {
    //获取球号列表
    Route::match(['get', 'post'], 'wx/getBallList', 'Api\WxMiniApp\BallController@getBallList');
    //获取某一天的球号
    Route::match(['get', 'post'], 'wx/getBallByDate', 'Api\WxMiniApp\BallController@getBallByDate');
    //获取时间段内的球号
    Route::match(['get', 'post'], 'wx/getBallByDateArea', 'Api\WxMiniApp\BallController@getBallByDateArea');
    //生成随机球号
    Route::match(['get', 'post'], 'wx/getRandBall', 'Api\WxMiniApp\BallController@getRandBall');
    //获取近一月球号列表
    Route::match(['get', 'post'], 'wx/getBallListMonth', 'Api\WxMiniApp\BallController@getBallListMonth');
    //获取近一周球号列表
    Route::match(['get', 'post'], 'wx/getBallListWeek', 'Api\WxMiniApp\BallController@getBallListWeek');
});


/**************** User ****************/
Route::middleware(['user'])->group(function () {
    //获取登录凭证
    Route::match(['get', 'post'], 'wx/user/getLoginCode', 'Api\WxMiniApp\UserController@getLoginCode');
    //保存用户信息
    Route::match(['get', 'post'], 'wx/user/saveUserData', 'Api\WxMiniApp\UserController@saveUserData');
});



//$route = Route::current();
//echo 'route:<pre>' . PHP_EOL;
//print_r($route);
//echo '</pre>' . PHP_EOL;

//$name = Route::currentRouteName();
//echo 'name:<pre>' . PHP_EOL;
//print_r($name);
//echo '</pre>' . PHP_EOL;

//$action = Route::currentRouteAction();
//echo 'action:<pre>' . PHP_EOL;
//print_r($action);
//echo '</pre>' . PHP_EOL;

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
//
//Route::middleware('api:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

//Route::middleware('api:api', 'throttle:1, 1')->group(function(){
//    Route::get('/user', function(){
//        return 'Please refresh slowly.';
//    });
//});

//VISIT: http://wx.dmtnewton.com/api/name
//Route::match(['get', 'post'], 'name', function(){
//    return 'API working...';
//});

//VISIT: http://wx.dmtnewton.com/api/user/1
//RESULT: {"id":1,"name":"FHlrg0LtI2","email":"mNe4OH1mbp@163.com","created_at":null,"updated_at":null}
//Route::get('user/{userObj}', function (App\User $userObj){
//    return $userObj;
//});

//中间件
//VISIT: http://wx.dmtnewton.com/api/profile/1
//$router->get('profile/{user_model}', function(App\User $user){
//    dd($user);
//});
