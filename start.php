<?php
// 根目录
define ( 'RUN_DIR', __DIR__ . "/" );
// 加载配置文件
require_once '_lib/lib/helper.php';
require_once '_lib/Autoloader.php';
require_once '_lib/ape/chose_workerman_version.php';

use Workerman\Worker;

ini_set ( 'default_socket_timeout', - 1 );

// 文件分隔符
define ( 'DS', DIRECTORY_SEPARATOR );
// 静态文件目录
define ( 'STATIC_DIR', RUN_DIR . "static/" );
// 静态文件目录
define ( 'LIB_DIR', RUN_DIR . "_lib/lib/" );
// util文件夹目录
define ( 'UTIL_DIR', RUN_DIR . "_lib/util/" );
// 定义一个空数组
define ( 'NULL_ARRAY', array () );

require_once 'config/config.php';
// 网站根目录
define ( 'HOME', $config ["home"] ); // 网站uri
                                     
// 启动服务
                                     // 加载所有Applications/*/start.php，以便启动所有服务
foreach ( glob ( RUN_DIR . '_lib/start*.php' ) as $start_file ) {
	require_once $start_file;
}
// 日志
Worker::$logFile = __DIR__ . "/log/" . $config ["logFile"];
// 访问日志
Worker::$stdoutFile = __DIR__ . "/log/" . $config ["stdoutFile"];

//引入文件修改检查，linux下可用
//require_once "filemonitor_start.php";

// 运行所有服务
Worker::runAll ();
