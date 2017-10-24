<?php
// 房卡单价
define ( 'AGENT_CARD_PRICE', 1 );

// 开始启动服务
$config = array (
		// 程序监听地址
		"home" => "http://192.168.1.71:1000/",
		// 程序监听地址
		"listen_address" => "0.0.0.0:1000",
		// udp日志服务器地址
		"log_server_address" => "udp://192.168.135.128:8002",
		// 负载均衡服务器地址,集群你懂得
		"register_address" => [ 
				"127.0.0.1:81" 
		],
		// debug开关
		"debug" => false,
		// session模式 file存文件 database存入数据库
		"session_type" => "database",
		// 默认session
		"session_name" => "session_tulong_qipai_pingtai",
		// 进程名字
		"worker_name" => "worker_ape",
		// 开启多少个进程,windows下只能开启一个
		"worker_count" => 8,
		// 每个进程最多接待多少个访客
		"max_request" => 10000,
		// 默认controller
		"default_module" => "admin",
		// 默认controller
		"default_controller" => "Index",
		// 默认方法
		"default_method" => "index",
		// 错误日志文件位置,相对与运行的worker的目录
		"logFile" => "info.log",
		// 重定向标准输出，即将所有echo、var_dump等终端输出写到对应文件中 注意 此参数只有在以守护进程方式运行时有效
		"stdoutFile" => "stdout.log",
		'cache' => false, // 一般数据缓存
		'cache_ips' => "127.0.0.1:8001" 
);

// 数据库配置
$database = array (
		'host' => "192.168.1.71",
		'port' => "3306",
		'username' => "www",
		'password' => "www",
		'db_name' => "qipai_pingtai",
		// 緩存方式,只支持GlobalData
		'cache' => false, // 数据库缓存
);


