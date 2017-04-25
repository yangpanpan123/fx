<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use EasyWeChat\Foundation\Application;
use App\User;
use DB;

class WxController extends Controller
{
	public $app = '';
    public function __construct(){
    	$options = [
	    'debug'  => true,
	    'app_id' => 'wxb0d717667536c956',
	    'secret' => '268e6dc460438f15df848efa2f9c8c9f',
	    'token'  => 'qwertyuiop',
	    // 'aes_key' => null, // 可选
	    'log' => [
	        'level' => 'debug',
	        'file'  => 'D:\xampp\htdocs\fx\easywechat.log', // XXX: 绝对路径！！！！
	    ],
	    //...
		];
		$this->app = new Application($options);
    
    }
		public function index(){
		//验证
		//$response = $app->server->serve();
		$server = $this->app->server;
		$server->setMessageHandler(function($msg){
			if ($msg->MsgType == 'event') {
			    return $this->shijian($msg);
			}else{
				return $this->xiaoxi($msg);
			}
		});
        $server->serve()->send();//返回消息给微信服务器
		// 将响应输出
		//return $response;

        }


    //事件处理
    public function shijian($msg){
        	$openid = $msg->FromUserName;
        	$userModel = new User();
        	if ($msg->Event == "subscribe") {
        		$u = $userModel->where('openid',$openid)->first();
                    //返回昵称
	        		// $openid = $msg->FromUserName;
	        		// $userServer = $this->app->user;
	        		// $userinfo = $userServer->get($openid);
	        		// return $userinfo->nickname;
                if ($u) {
                    $u->sta = 1;
                    $u->save();
                }else{
                    $userServer = $this->app->user;
                    $userinfo = $userServer->get($openid);
                    $userModel->name = $userinfo->nickname;
                    $userModel->openid = $openid;
                    $userModel->subtime = time();
                   //1:扫描二维码但是二维码中没有参数
                   //$code = $msg->EventKey;
                   //2:二维码中有参数
                    if ($msg->EventKey) {
                        $code = $msg->EventKey;
                        $p1_openid = str_replace('qrscene_','',$code);    //str_replace("world","Shanghai","Hello world!");
                        $p = DB::table('users')->where('openid',$p1_openid)->first();
                        $userModel->p1 = $p->uid;
                        $userModel->p2 = $p->p1;
                        $userModel->p3 = $p->p2;
                    }
                    $userModel->save();
                    $this->erweima($openid);
                }
        	        return '欢迎关注';
        }elseif($msg->Event == "unsubscribe"){
        	$u = $userModel->where('openid',$openid)->first();
        	if ($u) {
        		$u->sta = 0 ;
        		$u->save();
        	}
        }
    }
    public function xiaoxi($msg){
    //消息处理
    	if ($msg->MsgType == 'text') {
    		return '你好,text';
    	}elseif($msg->MsgType == 'image'){
    		return '你好,tupian';
    	}
    }
    //生成二维码
    public function erweima($openid){
        $qrcode = $this->app->qrcode;
        $result = $qrcode->forever($openid);
        $ticket = $result->ticket;
        $url = $qrcode->url($ticket);

        $content = file_get_contents($url); // 得到二进制图片内容
        file_put_contents(public_path()."/$openid.jpg", $content); // 写入文件
    }









//微博登录
    public function denglu(){
        return view('denglu');
    }
    public function weibo(){
        $weibo_code = $_GET['code'];
        $url = "https://api.weibo.com/oauth2/access_token";
        $data = [
        'client_id'=>'492391618',
        'client_secret'=>'8593131a22e767ee54de9800e91a0834',
        'grant_type'=>'authorization_code',
        'code'=>$weibo_code,
        'redirect_uri'=>'http://qwertyuiop.ittun.com/weibo'
        ];
        $curl = curl_init();
         //2：设置参数
        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl,CURLOPT_HEADER,0);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
        curl_setopt($curl,CURLOPT_POSTFIELDS,http_build_query($data));
        //3：发送请求
        $n = curl_exec($curl);
        $rs = json_decode($n,true);
        $at = $rs['access_token'];
        $uid = $rs['uid'];
        $uinfo = file_get_contents('https://api.weibo.com/2/users/show.json?access_token=' .$at .'&uid='.$uid);
        var_dump($uinfo);
    }









}
