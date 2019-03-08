<?php

namespace App\Http\Controllers;

use App\Model\WeixinMedia;
use App\Model\WeixinMessage;
use App\Model\WeixinUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Tests\Bundle\GuessedNameBundle;
use GuzzleHttp;
use App\Model\PuserModel;
class XweixinController extends Controller
{

    protected $redis_weixin_access_token = 'str:weixin_access_token';     //微信 access_token
        //获取access_token
    public function Accesstoken(){
        //获取缓存
        $token = Redis::get($this->redis_weixin_access_token);
        if(!$token){        // 无缓存 请求微信接口
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WEIXIN_APPID').'&secret='.env('WEIXIN_APPSECRET');
            $data = json_decode(file_get_contents($url),true);

            //记录缓存
            $token = $data['access_token'];
            Redis::set($this->redis_weixin_access_token,$token);
            Redis::setTimeout($this->redis_weixin_access_token,3600);
        }
        return $token;

        }

    //推送事件
        public function WXEvent(){
            $data = file_get_contents("php://input");
//            var_dump($data);die;

            //解析XML
            $xml = simplexml_load_string($data);        //将 xml字符串 转换成对象

            //记录日志
            $log_str = date('Y-m-d H:i:s') . "\n" . $data . "\n<<<<<<<";
            file_put_contents('logs/wx_event.log',$log_str,FILE_APPEND);



            $event = $xml->Event;                       //事件类型
        var_dump($xml);echo '<hr>';die;
            $openid = $xml->FromUserName;               //用户openid

            if($event=="subscribe"){

            }

        }








    //获取用户信息
    public function WxUser($openid){
        $openid='wxe355c6cac71fd488';
        $access_token=$this->Accesstoken();
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        $data = json_decode(file_get_contents($url),true);
        print_r($data) ;
    }

}