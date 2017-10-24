<?php
use ape\App;
use ape\view\View;
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
	require_once RUN_DIR."_lib/ape/init_db.php";
	
	// 引入视图
	global $view;
	$view = new View ();
	// 删除所有垃圾视图，重新生成、
	if($app->id==0){
		foreach ( glob ( RUN_DIR . 'z_*/storage/views/*.php' ) as $start_file ) {
			unlink($start_file);
		}
	}
	if($app->id==0){
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
	}
	
	//链接UDP日志服务器 start
	global $log_connection;
	$log_connection = new AsyncTcpConnection ( $config["log_server_address"]);
	$log_connection->connect ();
	//链接UDP日志服务器 end
};

require_once RUN_DIR."filter.php";