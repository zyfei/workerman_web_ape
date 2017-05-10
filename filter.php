<?php
/**
 * 全部拦截器
 */
$app->AddFunc ( "/", lib\filter\Filter::all ( $app ) );

/**
 * admin拦截器
 */
$app->AddFunc ( "/admin/", \z_admin\filter\Filter::login ( $app ) );


$app->AddFunc ( "/api/test/", \z_api\filter\Filter::login ( $app ) );

// 自定义404
$app->on404 = function () {
	$this->send ( "page not found!" );
};