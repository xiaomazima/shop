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
//        var_dump($xml);echo '<hr>';die;
            $openid = $xml->FromUserName;               //用户openid
            //处理用户发送信息
            if(isset($xml->MsgType)) {
                if ($xml->MsgType == 'text') { //用户发送文本消息
                    $xml->Content;
                    $xml_response = '<xml><ToUserName><![CDATA[' . $openid . ']]></ToUserName><FromUserName><![CDATA[' . $xml->ToUserName . ']]></FromUserName><CreateTime>' . time() . '</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[你好啊！欢迎来到小麻子公众号]]></Content></xml>';
                    echo $xml_response;

                }

                if ($event == "subscribe") {
                    $sub_time = $xml->CreateTime;               //扫码关注时间
                    echo 'openid: ' . $openid;
                    echo '</br>';
                    echo '$ksub_time: ' . $sub_time;

                    //获取用户信息
                    $user_info = $this->getUserInfo($openid);
                    echo '<pre>';
                    print_r($user_info);
                    echo '</pre>';

                    //保存用户信息
                    $u = WeixinUser::where(['openid' => $openid])->first();
                    //var_dump($u);die;
                    if ($u) {       //用户不存在
                        echo '用户已存在';
                    } else {
                        $user_data = [
                            'openid' => $openid,
                            'add_time' => time(),
                            'nickname' => $user_info['nickname'],
                            'sex' => $user_info['sex'],
                            'headimgurl' => $user_info['headimgurl'],
                            'subscribe_time' => $sub_time,
                            'blacklist'=>2
                        ];

                        $id = WeixinUser::insertGetId($user_data);      //保存用户信息
                        Redis::set('user',$id);//redis存用户信息
                    }

                }


            }

        }

    //展示用户列表
   public function UserList(){
       $data=WeixinUser::paginate(2);
//       print_r($data);
       return view('weixin.userlist',['arr'=>$data]);
   }

    //加入黑名单
    public function blacklist($id){
        $where=[
          'blacklist'=>1
        ];
        $res=WeixinUser::where(['id'=>$id])->update($where);
        if($res){
            echo '加入黑名单成功';
        }else{
            echo '失败';
        }
    }



    /**
     * 获取用户信息
     * @param $openid
     */
    public function getUserInfo($openid)
    {
//        $openid = 'oLreB1jAnJFzV_8AGWUZlfuaoQto';
        $access_token = $this->Accesstoken();
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';

        $data = json_decode(file_get_contents($url),true);
//        echo '<pre>';print_r($data);echo '</pre>';
        return $data;
    }



}