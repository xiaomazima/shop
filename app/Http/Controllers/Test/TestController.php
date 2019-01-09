<?php

namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;

class TestController extends Controller
{
    //

	public function world1()
	{
		echo __METHOD__;
	}

	public function world3()
	{
		header('Location:http://www.baidu.com/');
	}
	public function hello2()
	{
		echo __METHOD__;
		header('Location:/world2');
	}

	public function world2()
	{
		echo __METHOD__;
	}

	public function md($m,$d)
	{
		echo 'm: '.$m;echo '<br>';
		echo 'd: '.$d;echo '<br>';
	}

	public function showName($name=null)
	{
		var_dump($name);
	}

	public function query1()
	{
		$list = DB::table('shop_user')->get()->toArray();
		echo '<pre>';print_r($list);echo '</pre>';
	}

	public function query2()
	{
		$user = DB::table('shop_user')->where('id', 3)->first();
		echo '<pre>';print_r($user);echo '</pre>';echo '<hr>';
		$email = DB::table('shop_user')->where('id', 8)->value('email');
		var_dump($email);echo '<hr>';
		$info = DB::table('shop_user')->pluck('phone', 'user_name')->toArray();
		echo '<pre>';print_r($info);echo '</pre>';


	}

	public function test(){
		var_dump($_POST);
		var_dump($_GET);
	}

	public function mid1()
	{
		echo __METHOD__;
	}

	public function checkCookie()
	{
		echo __METHOD__;
	}

	public function phpInfo()
	{
		echo phpinfo();
	}
}
