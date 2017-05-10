<?php

namespace z_admin\filter;

/**
 * 全部的拦截器
 */
class Filter {
	
	/**
	 * 登陆拦截器,判断是否登陆
	 */
	public static function login(&$app) {
		return function ($data) use (&$app) {
			global $MODULE_URL;
			if($data["server"]["REQUEST_URI"]=="/admin/index/login"){
				return true;
			}
			if(session("admin")==null){
				R("index/login");
				return false;
			}
			return true;
		};
	}
	
}