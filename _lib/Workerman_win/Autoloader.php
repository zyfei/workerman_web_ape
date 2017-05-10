<?php

/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Workerman;

// 包含常量定义文件
require_once __DIR__ . '/Lib/Constants.php';

/**
 * 自动加载类
 *
 * @author walkor<walkor@workerman.net>
 */
class Autoloader {
	
	// 项目目录,默认是根目录
	protected static $_appInitPath= '';
	// lib文件目录,如果用namespace在外面没找到，尝试在lib下寻找
	protected static $_libPath = '_lib';
	
	/**
	 * 设置应用初始化目录
	 *
	 * @param string $root_path        	
	 * @return void
	 */
	public static function setRootPath($root_path) {
		self::$_appInitPath = $root_path;
	}
	
	/**
	 * 根据命名空间加载文件
	 *
	 * @param string $name        	
	 * @return boolean
	 */
	public static function loadByNamespace($name) {
		// 相对路径
		$class_path = str_replace ( '\\', DIRECTORY_SEPARATOR, $name );
		
		// 先尝试通过namespace 匹配地址寻找
		$class_file = $class_path . '.php';
		
		//如果没找到的话，再在_lib文件夹下寻找
		if (!is_file ( $class_file )) {
			$class_file = self::$_libPath. DIRECTORY_SEPARATOR . $class_path . '.php';
			//echo "$class_file"."<br2/>\n";
		}
		// 找到文件
		if (is_file ( $class_file )) {
			// 加载
			require_once ($class_file);
			if (class_exists ( $name, false )) {
				return true;
			}
		}
		return false;
	}
}

// 设置类自动加载回调函数
spl_autoload_register ( '\Workerman\Autoloader::loadByNamespace' );
