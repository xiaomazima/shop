<?php

namespace App\Http\Controllers\Weixin;

use App\Model\ShopGoods;
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
class WeixinController extends Controller
{
    //

    protected $redis_weixin_access_token = 'str:weixin_access_token';     //微信 access_token
    protected $redis_weixin_jsapi_ticket = 'str:weixin_jsapi_ticket';     //微信 jsapi_ticket
    public function test()
    {
//        echo __METHOD__;
       echo $this->getWXAccessToken();
        $this->getUserInfo(1);
    }

    /**
     * 首次接入
     */
    public function validToken1()
    {
        //$get = json_encode($_GET);
        //$str = '>>>>>' . date('Y-m-d H:i:s') .' '. $get . "<<<<<\n";
        //file_put_contents('logs/weixin.log',$str ,FILE_APPEND);
        echo $_GET['echostr'];
    }




    /**
     * 接收微信服务器事件推送
     */
    public function wxEvent()
    {
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
        if(isset($xml->MsgType)){
            if($xml->Content=='图文消息'){ //用户发送文本消息
//                $msg=$xml->Content;
//                $xml_response = '<xml><ToUserName><![CDATA['.$openid.']]></ToUserName><FromUserName><![CDATA['.$xml->ToUserName.']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[你好啊！欢迎来到小麻子公众号]]></Content></xml>';
//                echo $xml_response;
//                $user_info = $this->getUserInfo($openid);
//
//
//                //将用户发送的消息写入数据库
//                $data=[
//                    'openid'    => $openid,
//                    'add_time'=>time(),
//                    'message'=>$msg,
//                    'headimgurl' =>$user_info['headimgurl']
//                ];
//                $w_message=WeixinMessage::insertGetId($data);
//                var_dump($w_message);

                $FromUserName=$xml->FromUserName;
                $ToUserName=$xml->ToUserName;
                $Title='你好';
                $Description='我叫小麻子';
                $url='www.baidu.com';
                $picurl='http://mmbiz.qpic.cn/mmbiz_jpg/C2YxcLaiaqn8MgTAZoRv29jx9j9ofLsTmlM3utEMqupdpZmnIFZK5jpw5M6YGWRj6u4VF6PowInr4XIKbIw3NLw/0';

                $image_text='<xml>
         <ToUserName><![CDATA['.$FromUserName.']]></ToUserName>
        <FromUserName><![CDATA['.$ToUserName.']]></FromUserName>
        <CreateTime>'.time().'</CreateTime>
        <MsgType><![CDATA[news]]></MsgType>
          <ArticleCount>1</ArticleCount>
            <Articles>
                <item>
                     <Title><![CDATA['.$Title.']]></Title>
                           <Description><![CDATA['.$Description.']]></Description>
                                 <PicUrl><![CDATA['.$picurl.']]></PicUrl>
                                       <Url><![CDATA['.$url.']]></Url>
                                           </item>
                                  </Articles>
                           </xml>';
                echo $image_text;
         }elseif($xml->MsgType=='image'){       //用户发送图片信息
                //视业务需求是否需要下载保存图片
                if(1){  //下载图片素材
                    $file_name=$this->media($xml->MediaId);
                    $xml_response = '<xml><ToUserName><![CDATA['.$openid.']]></ToUserName><FromUserName><![CDATA['.$xml->ToUserName.']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['. date('Y-m-d H:i:s') .']]></Content></xml>';
                    echo $xml_response;

                    //写入数据库
                    $data = [
                        'openid'    => $openid,
                        'add_time'  => time(),
                        'msg_type'  => 'image',
                        'media_id'  => $xml->MediaId,
                        'format'    => $xml->Format,
                        'msg_id'    => $xml->MsgId,
                        'local_file_name'   => $file_name
                    ];

                    $m_id = WeixinMedia::insertGetId($data);
                    var_dump($m_id);
                }
            }elseif($xml->MsgType=='text'){
                $name=$xml->Content;
                $data=ShopGoods::where(['goods_name'=>$name])->first()->toArray();

                $FromUserName=$xml->FromUserName;
                $ToUserName=$xml->ToUserName;
                $Title=$data['goods_name'];
                $Description=$data['describe'];
                $url='www.baidu.com';
                $picurl='http://mmbiz.qpic.cn/mmbiz_jpg/C2YxcLaiaqn8MgTAZoRv29jx9j9ofLsTmlM3utEMqupdpZmnIFZK5jpw5M6YGWRj6u4VF6PowInr4XIKbIw3NLw/0';

                $shop='<xml>
         <ToUserName><![CDATA['.$FromUserName.']]></ToUserName>
        <FromUserName><![CDATA['.$ToUserName.']]></FromUserName>
        <CreateTime>'.time().'</CreateTime>
        <MsgType><![CDATA[news]]></MsgType>
          <ArticleCount>1</ArticleCount>
            <Articles>
                <item>
                     <Title><![CDATA['.$Title.']]></Title>
                           <Description><![CDATA['.$Description.']]></Description>
                                 <PicUrl><![CDATA['.$picurl.']]></PicUrl>
                                       <Url><![CDATA['.$url.']]></Url>
                                           </item>
                                  </Articles>
                           </xml>';
                echo $shop;



            }elseif($xml->MsgType=='voice'){        //处理语音信息
                $this->voice($xml->MediaId);
            }elseif($xml->MsgType=='event'){        //判断事件类型


        if($event=='subscribe'){
            $sub_time = $xml->CreateTime;               //扫码关注时间


            echo 'openid: '.$openid;echo '</br>';
            echo '$ksub_time: ' . $sub_time;

            //获取用户信息
            $user_info = $this->getUserInfo($openid);
            echo '<pre>';print_r($user_info);echo '</pre>';

            //保存用户信息
            $u = WeixinUser::where(['openid'=>$openid])->first();
            //var_dump($u);die;
            if($u){       //用户不存在
                echo '用户已存在';
            }else{
                $user_data = [
                    'openid'            => $openid,
                    'add_time'          => time(),
                    'nickname'          => $user_info['nickname'],
                    'sex'               => $user_info['sex'],
                    'headimgurl'        => $user_info['headimgurl'],
                    'subscribe_time'    => $sub_time,
                    'blacklist'          =>2
                ];

                $id = WeixinUser::insertGetId($user_data);      //保存用户信息
            }
        }elseif($event='CLICK'){
            if($xml->EventKey=='kefu01'){
            $this->keFu($openid,$xml->ToUserName);
                     }
                 }
            }
         }
    }

    /**
     * 客服处理
     */
    public function keFu($openid,$from){
        $xml_response = '<xml><ToUserName><![CDATA['.$openid.']]></ToUserName><FromUserName><![CDATA['.$from.']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['. 'Hello World, 现在时间'. date('Y-m-d H:i:s') .']]></Content></xml>';
        echo $xml_response;
    }

    /**
     * 下载图片素材
     */
    public function media($media_id){
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$this->getWXAccessToken().'&media_id='.$media_id;
        //echo $url;echo '</br>';

        //保存图片
        $client = new GuzzleHttp\Client();
        $response = $client->get($url);
        //$h = $response->getHeaders();
        //echo '<pre>';print_r($h);echo '</pre>';die;

        //获取文件名
        $file_info = $response->getHeader('Content-disposition');

        $file_name = substr(rtrim($file_info[0],'"'),-20);

        $wx_image_path = 'wx/images/'.$file_name;
        //保存图片
        $r = Storage::disk('local')->put($wx_image_path,$response->getBody());
        if($r){     //保存成功
            //echo 'OK';
        }else{      //保存失败
            //echo 'NO';
        }
            return $file_name;
    }


    /**
     * 下载语音文件
     */
    public function voice($media_id)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$this->getWXAccessToken().'&media_id='.$media_id;

        $client = new GuzzleHttp\Client();
        $response = $client->get($url);
        //$h = $response->getHeaders();
        //echo '<pre>';print_r($h);echo '</pre>';die;
        //获取文件名
        $file_info = $response->getHeader('Content-disposition');
        $file_name = substr(rtrim($file_info[0],'"'),-20);

        $wx_image_path = 'wx/voice/'.$file_name;
        //保存图片
        $r = Storage::disk('local')->put($wx_image_path,$response->getBody());
        if($r){     //保存成功

        }else{      //保存失败

        }
    }


    /**
     * 接收事件推送
     */
    public function validToken()
    {
        //$get = json_encode($_GET);
        //$str = '>>>>>' . date('Y-m-d H:i:s') .' '. $get . "<<<<<\n";
        //file_put_contents('logs/weixin.log',$str,FILE_APPEND);
        //echo $_GET['echostr'];
        $data = file_get_contents("php://input");
        $log_str = date('Y-m-d H:i:s') . "\n" . $data . "\n<<<<<<<";
        file_put_contents('logs/wx_event.log',$log_str,FILE_APPEND);
    }

    /**
     * 获取微信AccessToken
     */
    public function getWXAccessToken()
    {

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

    /**
     * 获取用户信息
     * @param $openid
     */
    public function getUserInfo($openid)
    {
//        $openid = 'oLreB1jAnJFzV_8AGWUZlfuaoQto';
        $access_token = $this->getWXAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';

        $data = json_decode(file_get_contents($url),true);
//        echo '<pre>';print_r($data);echo '</pre>';
        return $data;
    }

    /**
     *创建微信一级菜单
     */
    public function createMenu(){
        //echo __METHOD__;
        // 1 获取access_token 拼接请求接口
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$this->getWXAccessToken();
        //echo $url;echo '</br>';

        //2 请求微信接口
        $client = new GuzzleHttp\Client(['base_uri' => $url]);
        $data = [
            "button"    => [
                [
                    "type"  => "view",      // view类型 跳转指定 URL
                    "name"  => "小麻子",
                    "url"   => "https://www.baidu.com"
                ],
                [
                    "type"  => "click",      // click类型
                    "name"  => "点击这里",
                    "key"   => "kefu01"
                ],
                [
                    "name"=>"嘻嘻嘻",
                    "sub_button"=> [
                        [
                            "type"=>"view",
                            "name"=>"哈哈哈❤",
                             "url"=>"http://www.soso.com/"
                        ],
                        [
                            "type"=>"view",
                            "name"=>"嘻嘻嘻❤",
                            "url"=>"http://www.soso.com/"
                        ]
                    ]
                ]
            ]
        ];


        $r = $client->request('POST', $url, [
            'body' => json_encode($data,JSON_UNESCAPED_UNICODE)
        ]);

        // 3 解析微信接口返回信息

        $response_arr = json_decode($r->getBody(),true);
        //echo '<pre>';print_r($response_arr);echo '</pre>';

        if($response_arr['errcode'] == 0){
            echo "小麻子您的菜单创建成功";
        }else{
            echo "菜单创建失败，请重试";echo '</br>';
            echo $response_arr['errmsg'];

        }



    }

    /**
     * 刷新access_token
     */
    public function refreshToken()
    {
        Redis::del($this->redis_weixin_access_token);
        echo $this->getWXAccessToken();
    }
    /**
     * 消息群发
     */
    public function sendAll(){
        $url="https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token='.$this->getWXAccessToken().'";
        //2 请求微信接口
        $client = new GuzzleHttp\Client(['base_uri' => $url]);
        $data=[
            "touser"=>[
                "OPENID1",
                "OPENID2"
            ],
    "msgtype"=> "text",
    "text"=>["content"=> "hello from boxer."]
];
    }


    /**
     * 微信登录测试
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login()
    {
        return view('weixin.login');
    }
    /**
     * 接收code
     */
    public function code(){
//        echo __METHOD__;die;
        //1 回调拿到 code (用户确认登录后 微信会跳 redirect )
        print_r($_GET);echo '<hr/>';
        //获取code
        $code = $_GET['code'];
        //2 用code换取access_token 请求接口
        $token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=wxe24f70961302b5a5&secret=0f121743ff20a3a454e4a12aeecef4be&code='.$code.'&grant_type=authorization_code';
        $token_json = file_get_contents($token_url);
        $token_arr = json_decode($token_json,true);
        echo '<hr>';
        echo '<pre>';print_r($token_arr);echo '</pre>';

        $access_token = $token_arr['access_token'];
        $openid = $token_arr['openid'];

        // 3 携带token  获取用户信息
        $user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        $user_json = file_get_contents($user_info_url);

        $user_arr = json_decode($user_json,true);
        echo '<hr>';
        echo '<pre>';print_r($user_arr);echo '</pre>';
        return $this->info($user_arr);
    }

    /**
     * 入数据库
     */
    public function info($user_arr){
        $u = WeixinUser::where(['unionid'=>$user_arr['unionid']])->first();
//        var_dump($u);
        if($u){
            echo '登陆成功';

        }else{
            // 添加用户表
            $u_data = [
                'user_name'  => $user_arr['nickname'],
            ];

            $uid = PuserModel::insertGetId($u_data);

            //添加微信用户表
            $wx_u_data = [
                'uid'       => $uid,
                'openid'    =>$user_arr['openid'],
                'nickname'  => $user_arr['nickname'],
                'add_time'  => time(),
                'sex'       => $user_arr['sex'],
                'headimgurl'    => $user_arr['headimgurl'],
                'unionid'   => $user_arr['unionid']
            ];

            $wx_id = WeixinUser::insertGetId($wx_u_data);

            // 登录
        }
    }




    /**
     * 微信jssdk 调试
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function jssdkTest()
    {

        //计算签名

        $jsconfig = [
            'appid' => env('WEIXIN_APPID'),        //APPID
            'timestamp' => time(),
            'noncestr'    => str_random(10),
            //'sign'      => $this->wxJsConfigSign()
        ];

        $sign = $this->wxJsConfigSign($jsconfig);
        $jsconfig['sign'] = $sign;
        $data = [
            'jsconfig'  => $jsconfig
        ];
        return view('weixin.jssdk',$data);
    }


    /**
     * 计算JSSDK sign
     */
    public function wxJsConfigSign($param)
    {
        $current_url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];     //当前调用 jsapi的 url
        $ticket = $this->getJsapiTicket();
        $str =  'jsapi_ticket='.$ticket.'&noncestr='.$param['noncestr']. '&timestamp='. $param['timestamp']. '&url='.$current_url;
        $signature=sha1($str);
        return $signature;
    }


    /**
     * 获取jsapi_ticket
     * @return mixed
     */
    public function getJsapiTicket()
    {

        //是否有缓存
        $ticket = Redis::get($this->redis_weixin_jsapi_ticket);
        if(!$ticket){           // 无缓存 请求接口
            $access_token = $this->getWXAccessToken();
            $ticket_url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$access_token.'&type=jsapi';
            $ticket_info = file_get_contents($ticket_url);
            $ticket_arr = json_decode($ticket_info,true);

            if(isset($ticket_arr['ticket'])){
                $ticket = $ticket_arr['ticket'];
                Redis::set($this->redis_weixin_jsapi_ticket,$ticket);
                Redis::setTimeout($this->redis_weixin_jsapi_ticket,3600);       //设置过期时间 3600s
            }
        }
        return $ticket;

    }

}
