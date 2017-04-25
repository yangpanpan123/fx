<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


Route::any('/wx','WxController@index');
//Route::any('/wx','WxController@index');
//微博登录
Route::any('denglu','WxController@denglu');
Route::any('weibo','WxController@weibo');
//微信登录测试
Route::any('center','UserController@center');
Route::any('login','UserController@login');
Route::any('logout','UserController@logout');
//销售商品
Route::any('/','GoodsController@index');
Route::any('goods/{gid}','GoodsController@goods');
Route::any('buy/{id}','GoodsController@buy');
Route::any('cart','GoodsController@cart');
Route::any('cart_clear','GoodsController@cart_clear');
Route::any('xiadan','GoodsController@xiadan');
Route::any('payok','GoodsController@payok');


