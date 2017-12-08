<?php
namespace z_api\controller;

use ape\base\BaseController;
use model\Admin;

/**
 * 测试
 */
class TestController extends BaseController{

	public static function test1($app, $data) {
		return "hello world";
	}

	public static function test2($app, $data) {
		$admins = Admin::all();
		$arr['admins'] = $admins;
		return view("test2",$arr);
	}

}
