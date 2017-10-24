<?php

namespace model;

use lib\base\DBBase;

/**
 * 管理员
 */
class Admin extends DBBase {
	public static $table = "t_admin";
	public static $softDelete = true;
	/**
	 * 登陆方法
	 */
	public static function login($phone, $password) {
		global $db;
		$admins = $db->select ( '*' )->from ( static::$table )->where ( 'phone= :phone' )->where ( 'password= :password' )->where ( 'deleted_at is null' )->bindValues ( array (
				'phone' => $phone,
				'password' => $password 
		) )->query ();
		if (is_array ( $admins) && count ( $admins) > 0) {
			return $admins[0];
		} else {
			return null;
		}
	}
	
	
}
