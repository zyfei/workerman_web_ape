<?php
/**
 * 自动加载类
 * 类的命名空间必须和类的路径保持一致
 */
class Autoloader {
	
	// lib文件目录,如果用namespace在外面没找到，尝试在lib下寻找
	protected static $_libPath = '_lib';
	// protected static $_vendorPath = 'vendor';
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
		
		// 如果没找到的话，再在_lib文件夹下寻找
		if (! is_file ( $class_file )) {
			//使用相应的workerman包
			$class_path = str_replace ( "Workerman", WORKERMAN, $class_path );
			$class_file = self::$_libPath . DIRECTORY_SEPARATOR . $class_path . '.php';
			// if (! is_file ( $class_file )) {
			// $class_file = self::$_libPath . DIRECTORY_SEPARATOR . self::$_vendorPath . DIRECTORY_SEPARATOR . $class_path . '.php';
			// }
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
spl_autoload_register ( '\Autoloader::loadByNamespace' );
