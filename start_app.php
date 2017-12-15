<?php
if(!defined("RUN_DIR")){
    define('RUN_DIR', __DIR__ . "/");
}
require_once '_lib/ape/helper.php';
require_once '_lib/ape/constant.php';
require_once '_lib/Autoloader.php';
require_once '_lib/ape/http/Http.php';

use Workerman\Worker;
use ape\ApeWeb;
use ape\view\View;
use ape\MySQL;
use Workerman\Connection\AsyncTcpConnection;

// 日志
Worker::$logFile = __DIR__ . "/log/" . APE['config'] ["logFile"];
// 访问日志
Worker::$stdoutFile = __DIR__ . "/log/" . APE['config'] ["stdoutFile"];

$apeWeb = new ApeWeb("http://0.0.0.0:" . APE["config"]["port"]);
$apeWeb->name = APE["config"] ["worker_name"];
$apeWeb->max_request = APE["config"] ["max_request"];
$apeWeb->count = APE["config"] ["worker_count"];

$apeWeb->onStart = function ($apeWeb) {
    // 创建数据库连接
    $config = APE["config"];
    $database = APE["database"];

    $mysqls = array();
    foreach ($database as $k => $n) {
        $mysqls[$k] = new MySQL($n ['host'], $n ['port'], $n ['username'], $n ['password'], $n ['db_name']);
    }
    ApeWeb::$mysqls = $mysqls;
    ApeWeb::$view = new View();

    // 删除所有垃圾视图，重新生成、
    if ($apeWeb->id==0) {
        foreach (glob(RUN_DIR . 'z_*/storage/views/*.php') as $start_file) {
            unlink($start_file);
        }
    }

    require_once RUN_DIR."config/filter.php";
    foreach (glob(RUN_DIR . 'z_*/filter.php') as $file) {
        require_once($file);
    }

    //链接UDP日志服务器 start
    ApeWeb::$udp_log_client = new AsyncTcpConnection("udp://127.0.0.1:".APE['config']['port']);
    ApeWeb::$udp_log_client->connect();
    //链接UDP日志服务器 end
};

if(APE["WORKERMAN"] ==  "Workerman_win"){
    Worker::runAll();
}