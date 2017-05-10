<?php

namespace z_api\filter;

/**
 * 全部的拦截器
 */
class Filter {
	
	public static function login(&$app) {
		return function ($data) use (&$app) {
			return true;
		};
	}
	
}