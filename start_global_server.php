<?php
require_once '_lib/Autoloader.php';
require_once '_lib/ape/workerman_version.php';

/**
 * 进程间变量共享服务，类似于redis
 */
use Workerman\Worker;
use GlobalData\Server;

$global_worker = new Server('0.0.0.0', 2000);
Worker::runAll ();
