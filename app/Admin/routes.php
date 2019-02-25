<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('/goods',GoodsController::class);
    $router->resource('/weixin',WeixinController::class);
    $router->resource('/weixinMedia',WeixinMediaController::class);
    $router->resource('/send',WeixinSendController::class);
    $router->post('/','WeixinSendController@sendAll');


    $router->get('/formshow','WeixinMediaController@formShow');//永久素材
    $router->post('/formshow','WeixinMediaController@formTest');
    $router->get('/weiService','WeixinController@weiService');
});
