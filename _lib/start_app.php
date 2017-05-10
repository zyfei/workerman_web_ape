<?php
use Workerman\Worker;
use ape\App;
use ape\db\MySQL;
use ape\view\View;
use GlobalData\Client;
use Workerman\Connection\AsyncTcpConnection;

global $config;
$app = new App ( "http://" . $config ["listen_address"] );
$app->name = $config ["worker_name"];
// 设置每个进程处理多少请求后重启(防止程序写的有问题导致内存泄露)，默认为0(不重启)
$app->max_request = $config ["max_request"];
// 进程数
$app->count = $config ["worker_count"];

// 启动时执行的代码，这儿包含的文件支持reload
$app->onStart = function ($app) {
	// 创建数据库连接
	global $config;
	global $db;
	global $database;
	$db = new MySQL ( $database ['host'], $database ['port'], $database ['username'], $database ['password'], $database ['db_name'] );
	
	// 引入视图
	global $view;
	$view = new View ();
	
	// 引入globalData
	if ($database ["cache"] === true) {
		global $global;
		$global = new Client ( $database ["cache_ips"] );
	}
	
	// 连接负载均衡服务器
	foreach ( $config ["register_address"] as $n ) {
		$connection_reg = new AsyncTcpConnection ( "tcp://" . $n );
		$connection_reg->onConnect = function ($connection_reg) {
			global $config;
			echo "register server connect success\n";
			$connection_reg->send ( "register_" . $config ["listen_address"] );
			$connection_reg->close ();
		};
		
		$connection_reg->onError = function ($connection_reg) {
			echo "register server connect error\n";
			$connection_reg->close ();
		};
		
		$connection_reg->connect ();
	}
	// 连接负载均衡服务器结束
};
require_once "filter.php";

// 日志
Worker::$logFile = __DIR__ . $config ["logFile"];
// 访问日志
Worker::$stdoutFile = __DIR__ . $config ["stdoutFile"];