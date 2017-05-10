<?php

namespace util;

class Upload {
	
	/**
	 * 上传文件方法
	 * @param unknown $_F
	 * @param unknown $home_dir
	 * @return string
	 */
	public static function upload_file($_F, $home_dir="public") {
		$tmp_name = $_F ["file_name"];
		
		// 加上三位随机数
		$t = time () . "_" . rand ( 0, 9 ) . "" . rand ( 0, 9 ) . "" . rand ( 0, 9 );
		$hz = ".a";
		$dataDir = date ( "Y-m-d" );
		$dir = STATIC_DIR. "upload/$home_dir/" . $dataDir . "/";
		if (! is_dir ( $dir )) {
			// 创建dir
			mkdir ( $dir );
		}
		$path = $dir . $t.$hz;
		file_put_contents($path, $_F['file_data']);
		$image = "upload/$home_dir/" . $dataDir . "/" . $t.$hz;
		return $image;
	}
	
	/**
	 * 不需要base64解析的
	 */
	public static function U_64($data) {
		// 加上三位随机数
		$t = time () . "_" . rand ( 0, 9 ) . "" . rand ( 0, 9 ) . "" . rand ( 0, 9 );
		$dataDir = date ( "Y-m-d" );
		$dir = base_path () . "/../static/upload/" . $dataDir . "/";
		if (! is_dir ( $dir )) {
			// 创建dir
			mkdir ( $dir );
		}
		$path = $dir . $t . ".jpg";
		$IMG = base64_decode ( $data );
		file_put_contents ( $path, $IMG );
		$image = "upload/" . $dataDir . "/" . $t . ".jpg";
		return $image;
	}
	
}
