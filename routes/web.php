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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/adduser','User\UserController@add');

//路由跳转
Route::redirect('/hello1','/world1',301);
Route::get('/world1','Test\TestController@world1');
Route::get('/world3','Test\TestController@world3');

Route::get('hello2','Test\TestController@hello2');
Route::get('world2','Test\TestController@world2');


//路由参数
Route::get('/user/{uid}','User\UserController@user');
Route::get('/month/{m}/date/{d}','Test\TestController@md');
Route::get('/name/{str?}','Test\TestController@showName');

Route::get('/phpInfo','Test\TestController@phpInfo');


// View视图路由
Route::view('/mvc','mvc');
Route::view('/error','error',['code'=>403]);


// Query Builder
Route::get('/query/get','Test\TestController@query1');
Route::get('/query/where','Test\TestController@query2');

Route::match(['get','post'],'/test','Test\TestController@test');


Route::get('/date', function () {
    echo date('Y-m-d H:i:s');
});


Route::get('/userList','User\UserController@userList');
#注册页面
Route::any('/register','User\UserController@register');
Route::any('/registerDo','User\UserController@registerDo');

#登录
Route::any('/login','User\UserController@login');
Route::any('/loginDo','User\UserController@loginDo');
Route::any('/center','User\UserController@center');


Route::any('/cookieTest1','User\UserController@cookieTest1');
Route::any('/cookieTest2','User\UserController@cookieTest2');

//测试中间件
Route::get('/test/mid1','Test\TestController@mid1')->middleware('check.uid');        //中间件测试
Route::get('/test/check_cookie','Test\TestController@checkCookie')->middleware('check.cookie');


//购物车
Route::get('/cart','Cart\IndexController@index')->middleware('check.login.token');//购物车页面
//商品添加购物车
Route::get('/add/{goods_id}','Cart\IndexController@add')->middleware('check.login.token');
//商品删除
Route::get('/del/{goods_id}','Cart\IndexController@del')->middleware('check.login.token');
Route::get('/clist','Cart\IndexController@clist');//商品详情（自己）
Route::get('/goodsList/{goods_id}','Goods\IndexController@goodsList');//商品详情
//添加购物车
Route::post('/cartAdd','Cart\IndexController@cartAdd')->middleware('check.login.token');
Route::get('/cartDel/{goods_id}','Cart\IndexController@cartDel')->middleware('check.login.token');

//下单
Route::get('/orderAdd','Order\IndexController@orderAdd');
Route::get('/orderList','Order\IndexController@orderList');

//结算
Route::any('/orderPay/{id}','Order\IndexController@orderPay')->middleware('check.login.token');
Route::any('/orderDel/{id}','Order\IndexController@orderDel')->middleware('check.login.token');


//支付
Route::get('/pay/alipay/test','Pay\AlipayController@test');         //测试
Route::get('/pay/o/{oid}','Pay\IndexController@order')->middleware('check.login.token');         //订单支付
Route::post('/pay/alipay/notify','Pay\AlipayController@aliNotify');        //支付宝支付 异步通知回调
Route::get('/pay/alipay/return','Pay\AlipayController@aliReturn');        //支付宝支付 同步通知回调


