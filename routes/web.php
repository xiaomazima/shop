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
Route::get('/cart','Cart\IndexController@index');//购物车页面
//商品添加购物车
Route::get('/add/{goods_id}','Cart\IndexController@add');
//商品删除
Route::get('/del/{goods_id}','Cart\IndexController@del');
Route::get('/clist','Cart\IndexController@clist');//商品详情（自己）
Route::get('/goodsList/{goods_id}','Goods\IndexController@goodsList');//商品详情
//添加购物车
Route::post('/cartAdd','Cart\IndexController@cartAdd');
Route::get('/cartDel/{goods_id}','Cart\IndexController@cartDel');

//下单
Route::get('/orderAdd','Order\IndexController@orderAdd');
Route::get('/orderList','Order\IndexController@orderList');

//结算
Route::any('/orderPay/{id}','Order\IndexController@orderPay');
Route::any('/orderDel/{id}','Order\IndexController@orderDel');


//支付
//Route::get('/pay/alipay/test','Pay\AlipayController@test');         //测试
//Route::get('/pay/o/{oid}','Pay\IndexController@order')->middleware('check.login.token');         //订单支付
Route::get('/pay/o/{oid}','Pay\AlipayController@pay');
Route::post('/pay/alipay/notify','Pay\AlipayController@aliNotify');        //支付宝支付 异步通知回调
Route::get('/pay/alipay/return','Pay\AlipayController@aliReturn');        //支付宝支付 同步通知回调

Route::get('/orderDel','Pay\AlipayController@orderDel');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/goods_sou','Cart\IndexController@goods_sou');

//微信
Route::get('/weixin/test','Weixin\WeixinController@test');
Route::get('/weixin/valid','Weixin\WeixinController@validToken');
Route::get('/weixin/valid1','Weixin\WeixinController@validToken1');
Route::post('/weixin/valid1','Weixin\WeixinController@wxEvent');        //接收微信服务器事件推送
Route::post('/weixin/valid','Weixin\WeixinController@validToken');

Route::get('/createMenu','Weixin\WeixinController@createMenu');     //创建菜单
Route::get('/weixin/refresh_token','Weixin\WeixinController@refreshToken');     //刷新token


//微信支付
Route::post('/success','Weixin\PayController@success');
Route::get('/deciphering/{url}','Weixin\PayController@deciphering');//解密
Route::get('/weixin/pay/test/{order_sn}','Weixin\PayController@test');     //微信支付测试
Route::post('/weixin/pay/notice','Weixin\PayController@notice');     //微信支付通知回调
Route::get('/win','Weixin\PayController@win');


//微信登录
Route::get('/weixin/login','Weixin\WeixinController@login');        //微信登录
Route::get('/weixin/getcode','Weixin\WeixinController@code');        //接收code


//微信 JSSDK

Route::get('/weixin/jssdk/test','Weixin\WeixinController@jssdkTest');       // 测试



