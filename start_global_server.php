<?php
define ( 'RUN_DIR', __DIR__ . "/" );
require_once '_lib/Autoloader.php';
require_once '_lib/ape/chose_workerman_version.php';

/**
 * 进程间变量共享服务，类似于redis
 */
use Workerman\Worker;
use GlobalData\Server;

$global_worker = new Server('0.0.0.0', 8001);
// 日志
Worker::$logFile = __DIR__ . "/log/global_server.log";
// 访问日志
Worker::$stdoutFile = __DIR__ . "/log/global_server.log";

Worker::runAll ();