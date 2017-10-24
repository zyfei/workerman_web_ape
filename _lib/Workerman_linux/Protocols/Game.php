<?php

/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link      http://www.workerman.net/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Workerman\Protocols;

use Workerman\Connection\TcpConnection;

/**
 * Frame Protocol.
 */
class Game {
	/**
	 * 检查包的完整性
	 * 如果能够得到包长，则返回包的在buffer中的长度，否则返回0继续等待数据
	 * 如果协议有问题，则可以返回false，当前客户端连接会因此断开
	 *
	 * @param string $buffer        	
	 * @return int
	 */
	public static function input($buffer, TcpConnection $connection) {
		if (strlen ( $buffer ) < 2) {
			return 0;
		}
		// unpack() 函数从二进制字符串对数据进行解包。
		$unpack_data = unpack ( 'Stotal_length', $buffer );
		return ( int ) $unpack_data ['total_length'] + 2;
	}
	
	/**
	 * 解包，当接收到的数据字节数等于input返回的值（大于0的值）自动调用
	 * 并传递给onMessage回调函数的$data参数
	 *
	 * @param string $buffer        	
	 * @return string
	 */
	public static function decode($buffer) {
		$buffer = substr ( $buffer, 2 );
		return $buffer;
	}
	
	/**
	 * 打包，当向客户端发送数据的时候会自动调用
	 *
	 * @param string $buffer        	
	 * @return string
	 */
	public static function encode($buffer) {
		$bin_body = pack ( "a*", $buffer . "\0" );
		$body_len = strlen ( $bin_body );
		$bin_head = pack ( 'S*', $body_len );
		
		$bin = $bin_head . $bin_body;
		
		return $bin;
	}
}
