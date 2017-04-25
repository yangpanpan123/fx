2<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use EasyWeChat\Foundation\Application;

class UserController extends Controller
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
        'oauth'=>[
              'scopes'=>['snsapi_userinfo'],
              'callback'=>'/login',
        ],
        //...
        ];
        $this->app = new Application($options);
        }
        public function center(Request $req){
            if(!$req->session()->has('uinfo')){
                $oauth = $this->app->oauth;
                return $oauth->redirect();
            }
            return '你好欢迎登录';
        }
        public function login(){
            $oauth = $this->app->oauth;
            $user = $oauth->user();
            // var_dump($user);
            session()->put('uinfo',$user);
            return redirect('center');
        }
        public function logout(){
            session()->forget('uinfo');
        }










}
