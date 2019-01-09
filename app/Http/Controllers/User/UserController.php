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
	public function registerDo(Request $request)
	{
		$user_name= $request->input('user_name');
		$u=UserModel::where(['user_name'=>$user_name])->first();
		if($u){
			die('用户名已存在');
		}

		$arr = [
				'pwd'  => $request->input('pwd'),
				'pwd1'  => $request->input('pwd1'),
			];
		if($arr['pwd']!=$arr['pwd1']){
			die('密码不一致');
		}
		$pass=password_hash($arr['pwd'],PASSWORD_BCRYPT);

		$data=[
				'user_name'  => $request->input('user_name'),
				'phone'  => $request->input('phone'),
				'email'  => $request->input('email'),
				'age'  => $request->input('age'),
				'pwd'  => $pass,
				'ctime'  => time()
	];
		$uid = UserModel::insertGetId($data);
//		print_r($uid);

		if($uid){
			setcookie('uid',$uid,time()+86400,'/','shop.com',false,true);
			header("Refresh:3;url=/center");
			echo '注册成功,正在跳转';
		}else{
			echo '注册失败';
		}
	}

	/**
	 * 登录
	 */
	public function login(){
		return view('user/login');
	}

	/**
	 * @param Request $request
	 * 执行登录
	 */
	public function loginDo(Request $request){
		$arr = [
				'user_name'  => $request->input('user_name'),
				'pwd'  => $request->input('pwd'),
		];
		$where=[
			'user_name'=>$arr['user_name'],
		];
		$data=UserModel::where($where)->first();
		if($data){
			if( password_verify($arr['pwd'],$data->pwd) ){
				$token = substr(md5(time().mt_rand(1,99999)),10,10);
				setcookie('uid',$data->id,time()+86400,'','shop.com',false,true);
				setcookie('token',$token,time()+86400,'','',false,true);

				//$request->session()->put('u_token',$token);
				$request->session()->put('u_token',$token);
				header("Refresh:3;url=/center");
				echo "登录成功";
			}else{
				die("密码不正确");
			}
		}else{
			echo '登录失败';
		}
	}

	/**
	 * 个人中心
	 */
	public function center(Request $request)
	{
		if($_COOKIE['token'] != $request->session()->get('u_token')){
			die("非法请求");
		}else{
			echo '正常请求';
		}


//		echo 'u_token: '.$request->session()->get('u_token'); echo '</br>';
//		//echo '<pre>';print_r($request->session()->get('u_token'));echo '</pre>';
//
//		echo '<pre>';print_r($_COOKIE);echo '</pre>';
//		die;

		if(empty($_COOKIE['uid'])){

			header('Refresh:2;url=/login');
			echo '请先登录';
			exit;
		}else{
			echo 'UID: '.$_COOKIE['uid'] . ' 欢迎回来';
		}
	}


	public function cookieTest1()
	{
		setcookie('cookie1','lening',time()+1200,'/','shop.com',false,true);
		echo '<pre>';print_r($_COOKIE);echo '</pre>';
	}

	public function cookieTest2()
	{
		echo '<pre>';print_r($_COOKIE);echo '</pre>';
	}
}