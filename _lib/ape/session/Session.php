<?php

namespace ape\session;

use lib\base\DBBase;

/**
 * session存入数据库
 */
class Session extends DBBase {
	public static $table = "session";
	public static $softDelete = false;
	public static $id_cache = array ();
	
}
