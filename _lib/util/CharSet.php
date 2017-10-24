<?php
namespace util;

class CharSet{
	/**
	 * 检测字符串编码（注意：存在误判的可能性，降低误判的几率的唯一方式是给出尽可能多的样本$line）
	 * 检测原理：对给定的字符串的每一个字节进行判断，如果误差与gb18030在指定误差内，则判定为gb18030；与utf-8在指定误差范围内，则判定为utf-8；否则判定为utf-16
	 * 
	 * @param string $line        	
	 * @return string 中文字符集，返回gb18030（兼容gbk,gb2312,ascii）；西文字符集，返回utf-8（兼容ascii）；其他，返回utf-16（双字节unicode）
	 * @author fangl
	 */
	public static function detect_charset($line) {
		if (self::detect_gb18030 ( $line )) {
			return 'gb18030';
		} else if (self::detect_utf8 ( $line )) {
			return 'utf-8';
		} else
			return 'utf-16';
	}
	
	/**
	 * 兼容ascii，gbk gb2312，识别字符串是否是gb18030标准的中文编码
	 * 
	 * @param string $line        	
	 * @return boolean
	 * @author fangl
	 */
	public static function detect_gb18030($line) {
		$gbbyte = 0; // 识别出gb字节数
		for($i = 0; $i + 3 < strlen ( $line );) {
			if (ord ( $line {$i} ) >= 0 && ord ( $line {$i} ) <= 0x7f) {
				$gbbyte ++; // 识别一个单字节 ascii
				$i ++;
			} else if (ord ( $line {$i} ) >= 0x81 && ord ( $line {$i} ) <= 0xfe && (ord ( $line {$i + 1} ) >= 0x40 && ord ( $line {$i + 1} ) <= 0x7e || ord ( $line {$i + 1} ) >= 0x80 && ord ( $line {$i + 1} ) <= 0xfe)) {
				$gbbyte += 2; // 识别一个双字节gb18030（gbk）
				$i += 2;
			} else if (ord ( $line {$i} ) >= 0x81 && ord ( $line {$i} ) <= 0xfe && ord ( $line {$i + 2} ) >= 0x81 && ord ( $line {$i + 2} ) <= 0xfe && ord ( $line {$i + 1} ) >= 0x30 && ord ( $line {$i + 1} ) <= 0x39 && ord ( $line {$i + 3} ) >= 0x30 && ord ( $line {$i + 3} ) <= 0x39) {
				$gbbyte += 4; // 识别一个4字节gb18030（扩展）
				$i += 4;
			} else
				$i ++; // 未识别gb18030字节
		}
		return abs ( $gbbyte - strlen ( $line ) ) <= 4; // 误差在4字节之内
	}
	
	/**
	 * 识别字符串是否是utf-8编码，同样兼容ascii
	 * 
	 * @param string $line        	
	 * @return boolean
	 * @author fangl
	 */
	public static function detect_utf8($line) {
		$utfbyte = 0; // 识别出utf-8字节数
		for($i = 0; $i + 2 < strlen ( $line );) {
			// 单字节时，编码范围为：0x00 - 0x7f
			if (ord ( $line {$i} ) >= 0 && ord ( $line {$i} ) <= 0x7f) {
				$utfbyte ++; // 识别一个单字节utf-8（ascii）
				$i ++;
			}			// 双字节时，编码范围为：高字节 0xc0 - 0xcf 低字节 0x80 - 0xbf
			else if (ord ( $line {$i} ) >= 0xc0 && ord ( $line {$i} ) <= 0xcf && ord ( $line {$i + 1} ) >= 0x80 && ord ( $line {$i + 1} ) <= 0xbf) {
				$utfbyte += 2; // 识别一个双字节utf-8
				$i += 2;
			}			// 三字节时，编码范围为：高字节 0xe0 - 0xef 中低字节 0x80 - 0xbf
			else if (ord ( $line {$i} ) >= 0xe0 && ord ( $line {$i} ) <= 0xef && ord ( $line {$i + 1} ) >= 0x80 && ord ( $line {$i + 1} ) <= 0xbf && ord ( $line {$i + 2} ) >= 0x80 && ord ( $line {$i + 2} ) <= 0xbf) {
				$utfbyte += 3; // 识别一个三字节utf-8
				$i += 3;
			} else
				$i ++; // 未识别utf-8字节
		}
		return abs ( $utfbyte - strlen ( $line ) ) <= 3; // 误差在3字节之内的，则识别为utf-8编码
	}
}