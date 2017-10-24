<?php
namespace z_api\controller;

use lib\base\BaseController;

/**
 * 测试
 */
class TestController extends BaseController{
	
	public static function test($app, $data) {
		return api("success", "200", "test");
	}

}
