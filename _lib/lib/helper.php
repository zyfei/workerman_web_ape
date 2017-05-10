<?php
use Workerman\Protocols\Http;

/**
 * 毫秒获取
 *
 * @return number
 */
function microtime_float() {
	list ( $usec, $sec ) = explode ( " ", microtime () );
	return (( float ) $usec + ( float ) $sec);
}

/**
 * 格式化时间
 * @param unknown $time
 * @param unknown $fmt
 * @return unknown
 */
function T($time = null, $fmt = null) {
	if ($fmt == null) {
		$fmt = "Y-m-d H:i:s";
	}
	if ($time == null) {
		return "";
	}
	return date($fmt,$time);
}

/**
 * 获取get请求
 */
function get($name, $default = "") {
	if (array_key_exists ( $name, $_GET )) {
		return $_GET [$name];
	}
	return $default;
}

/**
 * 获取post请求
 */
function post($name, $default = "") {
	if (array_key_exists ( $name, $_GET )) {
		return $_POST [$name];
	}
	return $default;
}

/**
 * 获取get或post请求
 */
function input($name, $default = "") {
	if (array_key_exists ( $name, $_GET )) {
		return $_GET [$name];
	}
	if (array_key_exists ( $name, $_POST )) {
		return $_POST [$name];
	}
	return $default;
}

/**
 * session相关操作
 *
 * @param unknown $key        	
 * @param string $val        	
 * @return unknown|string
 */
function session($key, $val = "") {
	// 开启session，在访问最后关闭
	Http::sessionStart ();
	// 获取
	if ($val === "") {
		if (array_key_exists ( $key, $_SESSION )) {
			$v = $_SESSION [$key];
			Http::sessionWriteClose ();
			return $v;
		} else {
			Http::sessionWriteClose ();
			return null;
		}
		// 删除
	} elseif ($val === null) {
		unset ( $_SESSION [$key] );
		Http::sessionWriteClose ();
		// 设置
	} else {
		$_SESSION [$key] = $val;
		Http::sessionWriteClose ();
	}
}

/**
 * 调用某个方法
 *
 * @param unknown $f        	
 */
function fun($f) {
	return call_user_func ( $f );
}

// 重定向方法
function alert($str) {
	global $SEND_BODY;
	$SEND_BODY = $SEND_BODY . "<meta content='text/html; charset=utf-8' http-equiv='Content-Type'>";
	$SEND_BODY = $SEND_BODY . "<script>alert('" . $str . "');</script>";
	return true;
}

/**
 * layer插件操作
 *
 * @param string $url        	
 */
function close_layer($url = "null") {
	global $SEND_BODY;
	if ($url == "null") {
		$SEND_BODY = $SEND_BODY . "<script>var index = parent.layer.getFrameIndex(window.name);parent.layer.close(index);</script>";
		return true;
	}
	if ($url == "reload") {
		$SEND_BODY = $SEND_BODY . "<script>var index = parent.layer.getFrameIndex(window.name);parent.location.reload();parent.layer.close(index);</script>";
		return true;
	}
	$SEND_BODY = $SEND_BODY . "<script>var index = parent.layer.getFrameIndex(window.name);parent.location.href='" . $url . "';parent.layer.close(index);</script>";
	return true;
}

// 重定向方法
function R($url, $arr = array()) {
	global $SEND_BODY;
	global $MODULE_URL;
	$en = strpos ( $url, "http" );
	if ($en !== 0) {
		$url = $MODULE_URL . $url;
	}
	// 跳转默认是当前模块
	if (count ( $arr ) <= 0) {
		$SEND_BODY = $SEND_BODY . "<script>location.href='" . $url . "';</script>";
	} else {
		$str = "";
		$str = "<form method='post' action='" . $url . "' id='my_f_fomr_d'>";
		foreach ( $arr as $key => $n ) {
			$str = $str . "<input name='" . $key . "' value='" . $n . "' type='hidden' />";
		}
		$str = $str . "</form>";
		$str = $str . "<script>document.getElementById('my_f_fomr_d').submit();</script>";
		$SEND_BODY = $SEND_BODY . $str;
	}
	return true;
}

/**
 * 发送api
 */
function api($msg, $code, $content) {
	global $SEND_BODY;
	Http::header ( "Content-type: application/json" );
	$arr ["msg"] = $msg;
	$arr ["code"] = $code;
	$arr ["content"] = $content;
	$SEND_BODY = $SEND_BODY . json_encode ( $arr );
	return true;
}

/**
 * 返回页面
 */
function view($tpl, &$arr = NULL_ARRAY) {
	global $SEND_BODY;
	global $view;
	$SEND_BODY = $SEND_BODY . $view->view ( $tpl, $arr );
	return true;
}

/**
 * 随机数
 *
 * @param unknown $length        	
 * @param string $chars        	
 * @return string
 */
function random($length, $chars = '123123ui1h2iu3g12f3l12g312f3ul1g2u3g12ui3f1212iu3g1i23gi12f312') {
	$hash = '';
	$max = strlen ( $chars ) - 1;
	for($i = 0; $i < $length; $i ++) {
		$hash .= $chars [mt_rand ( 0, $max )];
	}
	return $hash;
}

/**
 * 清理 HTML 中的 XSS 潜在威胁
 *
 * 千辛万苦写出来，捣鼓正则累死人
 *
 * @param string|array $string        	
 * @param bool $strict
 *        	严格模式下，iframe 等元素也会被过滤
 * @return mixed
 */
function clean_xss($string, $strict = true) {
	if (is_array ( $string )) {
		return array_map ( 'cleanXss', $string );
	}
	
	// 移除不可见的字符
	$string = preg_replace ( '/%0[0-8bcef]/', '', $string );
	$string = preg_replace ( '/%1[0-9a-f]/', '', $string );
	$string = preg_replace ( '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $string );
	
	$string = preg_replace ( '/<meta.+?>/is', '', $string ); // 过滤 meta 标签
	$string = preg_replace ( '/<link.+?>/is', '', $string ); // 过滤 link 标签
	$string = preg_replace ( '/<script.+?<\/script>/is', '', $string ); // 过滤 script 标签
	
	if ($strict) {
		$string = preg_replace ( '/<style.+?<\/style>/is', '', $string ); // 过滤 style 标签
		$string = preg_replace ( '/<iframe.+?<\/iframe>/is', '', $string ); // 过滤 iframe 标签 1
		$string = preg_replace ( '/<iframe.+?>/is', '', $string ); // 过滤 iframe 标签 2
	}
	
	$string = preg_replace_callback ( '/(\<\w+\s)(.+?)(?=( \/)?\>)/is', function ($m) {
		// 去除标签上的 on.. 开头的 JS 事件，以下一个 xxx= 属性或者尾部为终点
		$m [2] = preg_replace ( '/\son[a-z]+\s*\=.+?(\s\w+\s*\=|$)/is', '\1', $m [2] );
		
		// 去除 A 标签中 href 属性为 javascript: 开头的内容
		if (strtolower ( $m [1] ) == '<a ') {
			$m [2] = preg_replace ( '/href\s*=["\'\s]*javascript\s*:.+?(\s\w+\s*\=|$)/is', 'href="#"\1', $m [2] );
		}
		
		return $m [1] . $m [2];
	}, $string );
	
	$string = preg_replace ( '/(<\w+)\s+/is', '\1 ', $string ); // 过滤标签头部多余的空格
	$string = preg_replace ( '/(<\w+.*?)\s*?( \/>|>)/is', '\1\2', $string ); // 过滤标签尾部多余的空格
	
	return $string;
}

/**
 * 打印
 * 
 * @param unknown $arr        	
 */
function dd($arr) {
	var_dump ( $arr );
}
function find($model, $id) {
	return call_user_func ( "\model\\" . $model . "::find", $id );
}