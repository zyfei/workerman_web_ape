<?php

namespace z_admin\controller;

use lib\base\BaseController;
use model\Admin;

class IndexController extends BaseController {
	public static function index($app, $data) {
		return R ( "agent_commodity/all" );
	}
	
	/**
	 * 登录
	 */
	public static function login($app, $data) {
		if (session ( "admin" ) != null) {
			return R ( "index/index" );
		}
		$phone = input ( "phone" );
		$password = input ( "password" );
		$admin = Admin::login ( $phone, $password );
		if ($admin != null) {
			session ( "admin", $admin );
			return R ( "index/index" );
		}
		return view ( 'login' );
	}
	
	/**
	 * 退出
	 */
	public static function logout($app, $data) {
		session ( "admin", null );
		return R ( "index/login" );
	}
	
	/**
	 * 修改密码前
	 */
	public static function password($app, $data) {
		return view ( "password" );
	}
	
	/**
	 * 修改密码
	 */
	public static function password_update($app, $data) {
		$admin = session ( "admin" );
		$password_old = input ( "password_old" ); // 原密码
		$password = input ( "password" ); // 新密码
		if ($admin ["password"] == $password_old) {
			$admin = Admin::find ( $admin ["id"] );
			$admin ["password"] = $password;
			Admin::update ( $admin );
			session ( "admin", $admin );
			return 1;
		} else {
			return 0;
		}
	}
}