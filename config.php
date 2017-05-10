<?php
// 开始启动服务
$config = array (
		// 程序监听地址
		"home" => "http://127.0.0.1:1000/",
		// 程序监听地址
		"listen_address" => "0.0.0.0:1000",
		// 负载均衡服务器地址,集群你懂得
		"register_address" => [ 
				"127.0.0.1:81" 
		],
		// debug开关
		"debug" => false,
		// session模式 file存文件 database存入数据库
		"session_type" => "database",
		// 默认session
		"session_name" => "session_workerman_web_ape",
		// 进程名字
		"worker_name" => "worker_ape",
		// 开启多少个进程,windows下只能开启一个
		"worker_count" => 1,
		// 每个进程最多接待多少个访客后自动去死(重启)~
		"max_request" => 0,
		// 默认controller
		"default_module" => "admin",
		// 默认controller
		"default_controller" => "Index",
		// 默认方法
		"default_method" => "index",
		// 错误日志文件位置,相对与运行的worker的目录
		"logFile" => "/../info.log",
		// 重定向标准输出，即将所有echo、var_dump等终端输出写到对应文件中 注意 此参数只有在以守护进程方式运行时有效
		"stdoutFile" => "/../stdout.log" 
);

// 数据库配置
$database = array (
		'host' => "127.0.0.1",
		'port' => "3306",
		'username' => "root",
		'password' => "root",
		'db_name' => "workerman_web_ape",
		
		// 緩存方式,只支持GlobalData(支持gd就够了，代码可控)
		'cache' => true, // 数据库缓存
		'cache_ips' => "127.0.0.1:2000" 
);