<?php
namespace z_api\controller;

use ape\base\BaseController;
use model\Admin;
use ape\ApeWeb;

/**
 * 测试
 */
class TestController extends BaseController{

	public static function test1($app, $data) {
		$admin = Admin::find("3");
		dd($admin);
		return "hello world";
	}

	public static function test2($app, $data) {
		Admin::all("id=1 and id!='2' and created_at!=null");
		$arr["admins"] = $admins;
		return view("test2",$arr);
	}

}
