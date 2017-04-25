<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Cart;
use App\User;
use App\Order;
use App\Item;
use App\Fee;


class GoodsController extends Controller
{
  public function index(){
    $goods_list = DB::table('goods')->get();
    return view('index',['goods_list'=>$goods_list]);
  }

  public function goods($gid){
    $goods_info = DB::table('goods')->where('gid',$gid)->first();
    return view('goods',['goods_info'=>$goods_info]);
  }

//添加购物车
  public function buy($id){
     $goods_info = DB::table('goods')->where('gid',$id)->first();
     Cart::add($id,$goods_info->gname,$goods_info->price,1,array());
     return redirect('cart');
  }
  //查看购物车
  public function cart(){
  	$items = Cart::getContent();//购物车详细信息
  	$total = Cart::getTotal();//总价
  	return view('cart',['items'=>$items,'to'=>$total]);
  }
  //清空购物车
  public function cart_clear(){
  	Cart::clear();
  }
  //下单
  public function xiadan(Request $req){
    $address = $req->address;//地址
    $xm = $req->xm;//姓名
    $mobile = $req->mobile;//手机号
    $items = Cart::getContent();//购物车详细信息
    $total = Cart::getTotal();//总价

    $order_sn = date('YmdHis').mt_rand(1000,9999);//订单号

    $userinfo = session()->get('uinfo');//用户信息

    $orderModel = new Order();

    $orderModel->ordsn = $order_sn;
    $orderModel->uid = $userinfo->uid;
    $orderModel->openid = $userinfo->openid;
    $orderModel->xm = $xm;
    $orderModel->address = $address;
    $orderModel->tel = $mobile;
    $orderModel->money = $total;
    $orderModel->ispay = 0;
    $orderModel->ordtime = time();

    $orderModel->save();

    foreach ($items as $i){
    	$item = new Item();
    	$item->oid = $orderModel->oid;
    	$item->gid = $i->id;
    	$item->goods_name = $i->name;
    	$item->price = $i->price;
    	$item->amount = $i->quantity;
    	$item->save();
    }
    $this->cart_clear();
    return view('zhifu',['oid'=>$orderModel->oid]);
}
public function payok(){
	$req = request();
	$oid = $req->oid;
	DB::table('orders')->where('oid',$oid)->update(['ispay'=>1]);
	$order = Order::where('oid',$oid)->first();
	$uid = $order->uid;
	$money = $order->money;

	$user = User::where('uid',$uid)->first();
	//p1=0.5   p2=0.2   p3=0.1

	$sy = [$user->p1,$user->p2,$user->p3];
	$yj = [0.5,0.2,0.1];
	foreach ($sy as $k => $v) {
		$fee = new Fee();
		$fee->money = $money*$yj[$k];//收益金额
		$fee->uid = $v;//收益者
		$fee->byid = $uid;//购买者
		$fee->oid = $oid;
		$fee->save();
	}
	return '<h1>购买成功</h1>';

}




}
