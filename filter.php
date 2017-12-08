<?php
/**
 * 全部拦截器
 * arge0 拦截的路径前缀
 * arge1 回调方法
 */
$apeWeb->AddFilter ( "/", function(){
	var_dump("全局拦截器-我在根目录下的filter.php中");
	return true;
} );

// 自定义404
$apeWeb->on404 = function () {
	$this->send ( "page not found!" );
};
