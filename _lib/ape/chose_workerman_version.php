<?php
if (strtolower ( substr ( PHP_OS, 0, 3 ) ) == 'win') {
	define ( 'WORKERMAN', 'Workerman_win' );
} else {
	define ( 'WORKERMAN', 'Workerman_linux' );
	// 检查扩展
// 	if (! extension_loaded ( 'swoole' )) {
// 		exit ( "Please install swoole extension. See http://doc3.workerman.net/install/install.html\n" );
// 	}
	if (! extension_loaded ( 'pcntl' )) {
		exit ( "Please install pcntl extension. See http://doc3.workerman.net/install/install.html\n" );
	}
	
	if (! extension_loaded ( 'posix' )) {
		exit ( "Please install posix extension. See http://doc3.workerman.net/install/install.html\n" );
	}

}