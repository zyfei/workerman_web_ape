<?php

namespace util;

class Upload {
	
	// 上传普通文件帮助函数
	public static function U($_F) {
		$name = $_F ["name"];
		$type = $_F ["type"];
		$tmp_name = $_F ["tmp_name"];
		$size = $_F ["size"];
		
		$hz = substr ( $name, strrpos ( $name, '.' ) + 1 );
		// 加上三位随机数
		$t = time () . "_" . rand ( 0, 9 ) . "" . rand ( 0, 9 ) . "" . rand ( 0, 9 );
		
		$dataDir = date ( "Y-m-d" );
		$dir = base_path () . "/../static/upload/" . $dataDir . "/";
		if (! is_dir ( $dir )) {
			// 创建dir
			mkdir ( $dir );
		}
		$path = $dir . $t . "." . $hz;
		
		$ok = @move_uploaded_file ( $tmp_name, $path );
		$image = "upload/" . $dataDir . "/" . $t . "." . $hz;
		return $image;
	}
	
	/**
	 * 保存web图片
	 * 
	 * @param unknown $path        	
	 * @return boolean
	 */
	public static function save_web_image($path) {
		//if (! preg_match ( '/\/([^\/]+\.[a-z]{3,4})$/i', $path, $matches )) {
		//	return false;
		//}
		//if (strrpos ( $path, '.' ) == false) {
			$hz = ".png";
		//} else {
		//	$hz = substr ( $path, strrpos ( $path, '.' ) );
		//}
		
		$t = time () . "_" . rand ( 0, 9 ) . "" . rand ( 0, 9 ) . "" . rand ( 0, 9 );
		
		// $image_name = strToLower ( $matches [1] );
		$ch = curl_init ( $path );
		curl_setopt ( $ch, CURLOPT_HEADER, false );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_BINARYTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 0 );
		
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
		$img = curl_exec ( $ch );
		if($img==null || $img=="" || $img==false){
			return false;
		}
		$image_name = "upload/image/" . $t . $hz;
		$fp = fopen ( STATIC_DIR . $image_name, 'a' );
		fwrite ( $fp, $img );
		fclose ( $fp );
		
		return "upload/image/" . $t . $hz;
	}
	
	/**
	 * 上传文件方法
	 *
	 * @param unknown $_F        	
	 * @param unknown $home_dir        	
	 * @return string
	 */
	public static function upload_file($_F, $home_dir = "public") {
		$tmp_name = $_F ["file_name"];
		
		// 加上三位随机数
		$t = time () . "_" . rand ( 0, 9 ) . "" . rand ( 0, 9 ) . "" . rand ( 0, 9 );
		if (strrpos ( $tmp_name, '.' ) == false) {
			$hz = ".a";
		} else {
			$hz = substr ( $tmp_name, strrpos ( $tmp_name, '.' ) );
		}
		
		$dataDir = date ( "Y-m-d" );
		$dir = STATIC_DIR . "upload/$home_dir/" . $dataDir . "/";
		if (! is_dir ( $dir )) {
			// 创建dir
			mkdir ( $dir );
		}
		$path = $dir . $t . $hz;
		file_put_contents ( $path, $_F ['file_data'] );
		$image = "upload/$home_dir/" . $dataDir . "/" . $t . $hz;
		return $image;
	}
	
	/**
	 * 不需要base64解析的
	 */
	public static function U_64($data, $home_dir = "public") {
		// 加上三位随机数
		$t = time () . "_" . rand ( 0, 9 ) . "" . rand ( 0, 9 ) . "" . rand ( 0, 9 );
		$dataDir = date ( "Y-m-d" );
		$dir = STATIC_DIR . "upload/$home_dir/" . $dataDir . "/";
		if (! is_dir ( $dir )) {
			// 创建dir
			mkdir ( $dir );
		}
		$path = $dir . $t . ".jpg";
		$IMG = base64_decode ( $data );
		if(!file_put_contents ( $path, $IMG )){
			return false;
		};
		$image = "upload/$home_dir/" . $dataDir . "/" . $t . ".jpg";
		return $image;
	}
	
	/**
	 * desription 压缩图片
	 *
	 * @param sting $imgsrc
	 *        	图片路径
	 * @param string $imgdst
	 *        	压缩后保存路径
	 *        	
	 */
	public static function image_yasuo($url, $new_widht = 300) {
		$filename = base_path () . "/../static/" . $url;
		// header ( 'Content-type: image/jpeg' );
		// 新地址生成
		$hz = substr ( $filename, strrpos ( $filename, '.' ) + 1 );
		// dd($hz);
		if ($hz != "jpg" && $hz != "jpeg" && $hz != "png") {
			return $url;
		}
		$new_url = str_replace ( "." . $hz, "_2." . $hz, $url );
		
		$new_filename = base_path () . "/../static/" . $new_url;
		
		// 获取图片的宽高
		list ( $width, $height, $image_type ) = getimagesize ( $filename );
		
		// $image_type GIF=1,JPG=2,PNG=3
		
		$new_height = $new_widht * $height / $width;
		
		$thumb = imagecreatetruecolor ( $new_widht, $new_height );
		if ($image_type == 2) {
			// dd($filename);
			$source = imagecreatefromjpeg ( $filename );
		}
		if ($image_type == 3) {
			$source = imagecreatefrompng ( $filename );
		}
		if ($image_type == 1) {
			$source = imagecreatefromgif ( $filename );
		}
		imagecopyresampled ( $thumb, $source, 0, 0, 0, 0, $new_widht, $new_height, $width, $height );
		
		// 输出给浏览器
		if ($image_type == 2) {
			imagejpeg ( $thumb, $new_filename );
		}
		if ($image_type == 3) {
			imagepng ( $thumb, $new_filename );
		}
		if ($image_type == 1) {
			imagegif ( $thumb, $new_filename );
		}
		imagedestroy ( $thumb );
		
		return $new_url;
	}
}
