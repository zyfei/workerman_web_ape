<?php
// 文件分隔符
define('DS', DIRECTORY_SEPARATOR);
// 静态文件目录
define('STATIC_DIR', RUN_DIR . "public/");
// 类库主目录
define('LIB_DIR', RUN_DIR . "_lib");
// util文件夹目录
define('UTIL_DIR', RUN_DIR . "_lib/util/");
// 配置文件
$APE['config'] = require_once RUN_DIR.'config/config.php';
// 数据配置文件
$APE['database'] = require_once RUN_DIR.'config/database.php';
if (strtolower ( substr ( PHP_OS, 0, 3 ) ) == 'win') {
	$APE["WORKERMAN"] =  "Workerman_win";
} else {
	$APE["WORKERMAN"] =  'Workerman_linux';
}
define ( 'HOME', $APE['config'] ["home"] );
define ( "APE", $APE );
