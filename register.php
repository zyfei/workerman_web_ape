<?php
require_once '_lib/Autoloader.php';
require_once '_lib/ape/workerman_version.php';

use \Workerman\Worker;
use \Workerman\Connection\AsyncTcpConnection;

$worker = new Worker ( 'tcp://0.0.0.0:81' );
$worker->count = 1;
$worker->name = 'ape_register';

Worker::$logFile = __DIR__ . "/register_log.log";
Worker::$stdoutFile = __DIR__ . "/register_stdout.log";

// 保存所有客户端
$clients = array ();
$worker->onMessage = function ($connection, $buffer) {
	global $clients;
	// 客户端进行注册
	if (strpos ( $buffer, "register_" ) === 0) {
		$listen_address = str_replace ( "register_", "", $buffer );
		$listen_address = str_replace ( "0.0.0.0", "127.0.0.1", $listen_address );
		if (! array_key_exists ( $listen_address, $clients )) {
			if ($listen_address == "") {
				$connection->close ();
				return;
			}
			$clients [$listen_address] = true;
var_dump ( $listen_address . " is reg~~~~~~~~ " );
		}
		$connection->close ();
		return;
	}
	if (count ( $clients ) > 0) {
		global $_key;
		$_key = key ( $clients );
		if ($_key == false) {
			reset ( $clients );
			$_key = key ( $clients );
		}
		$remote_connection = new AsyncTcpConnection ( "tcp://" . $_key );
		next ( $clients );
		$remote_connection->onError = function ($remote_connection) use ($connection) {
			global $clients;
			global $_key;
var_dump ( $_key . " is close!!!!!!!!! " );
			unset ( $clients [$_key] );
			$connection->send ( "HTTP/1.1 502 Connection Established\r\n\r\n" );
			$connection->close ();
			return;
		};
		$remote_connection->send ( $buffer );
		// Pipe.
		$remote_connection->pipe ( $connection );
		$connection->pipe ( $remote_connection );
		$remote_connection->connect ();
	} else {
		$connection->send ( "HTTP/1.1 502 Connection Established\r\n\r\n" );
		$connection->close ();
	}
};

// Run.
Worker::runAll ();