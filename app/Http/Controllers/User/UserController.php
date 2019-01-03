<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\UserModel;

class UserController extends Controller
{
    //

	public function user($uid)
	{
		echo $uid;
	}

	public function add()
	{
		$data = [
			'name'      => str_random(5),
			'age'       => mt_rand(20,99),
			'email'     => str_random(6) . '@gmail.com',
			'reg_time'  => time()
		];

		$id = UserModel::insertGetId($data);
		var_dump($id);
	}

	public function userList(){
		$list=UserModel::all()->toArray();
//		var_dump($list);
		$data=[
			'title'=>'展示页面',
			'list'=>$list
		];
		return view('user.userList',$data);
	}

	/**
	 * 用户注册页面
	 */
	public function register(){
		return view('user.register');
	}
}
