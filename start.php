<?php
// 加载配置文件
require_once '_lib/lib/helper.php';
require_once '_lib/Autoloader.php';
require_once '_lib/ape/workerman_version.php';

use Workerman\Worker;

// 版本
define ( 'VERSION', "0.0.1" );
// 文件分隔符
define ( 'DS', DIRECTORY_SEPARATOR );
// 根目录
define ( 'RUN_DIR', __DIR__ . "/" );
// 静态文件目录
define ( 'STATIC_DIR', RUN_DIR . "static/" );
// 静态文件目录
define ( 'LIB_DIR', RUN_DIR . "_lib/lib/" );
// util文件夹目录
define ( 'UTIL_DIR', RUN_DIR . "_lib/util/" );
// 定义一个空数组
define ( 'NULL_ARRAY', array () );

require_once 'config.php';
// 网站根目录
define ( 'HOME', $config ["home"] ); // 网站uri
                                     
// 启动服务
                                     // 加载所有Applications/*/start.php，以便启动所有服务
foreach ( glob ( RUN_DIR . '_lib/start*.php' ) as $start_file ) {
	require_once $start_file;
}

// 运行所有服务
Worker::runAll ();