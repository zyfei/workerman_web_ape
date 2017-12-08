<?php
//检查前提条件
if (! extension_loaded ( 'pcntl' )) {
    exit ( "Please install pcntl extension. See http://doc3.workerman.net/install/install.html\n" );
}

if (! extension_loaded ( 'posix' )) {
    exit ( "Please install posix extension. See http://doc3.workerman.net/install/install.html\n" );
}

define('RUN_DIR', __DIR__ . "/");
require_once '_lib/ape/helper.php';
require_once '_lib/ape/constant.php';
require_once '_lib/Autoloader.php';
require_once '_lib/ape/http/Http.php';

use Workerman\Worker;

// 启动服务
require_once RUN_DIR."_lib/log_server.php";
foreach ( glob ( RUN_DIR . '_lib/start*.php' ) as $start_file ) {
	require_once $start_file;
}
// 日志
Worker::$logFile = __DIR__ . "/log/" . APE['config'] ["logFile"];
// 访问日志
Worker::$stdoutFile = __DIR__ . "/log/" . APE['config'] ["stdoutFile"];

// 运行所有服务
Worker::runAll ();
