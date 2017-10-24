<?php

namespace util;

class HttpClient {
	
	/**
	 * 请求
	 * @param unknown $url
	 * @param unknown $xml
	 * @return unknown
	 */
	public static function request_https($url, $xml = "") {
		$curl = curl_init ();
		curl_setopt ( $curl, CURLOPT_URL, $url );
		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, false ); // 这个是重点。
		curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, FALSE );
		curl_setopt ( $curl, CURLOPT_POSTFIELDS, $xml );
		$data = curl_exec ( $curl );
		curl_close ( $curl );
		return $data;
	}
	
}
