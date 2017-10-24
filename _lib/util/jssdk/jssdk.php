<?php
use util\Wx;
class JSSDK {
	private $appId;
	private $appSecret;
	public function __construct($appId, $appSecret) {
		$this->appId = $appId;
		$this->appSecret = $appSecret;
	}
	public function getSignPackage($new_url="") {
		$jsapiTicket = Wx::getJsApiTicket ();
		// 注意 URL 一定要动态获取，不能 hardcode.
		$protocol = (! empty ( $_SERVER ['HTTPS'] ) && $_SERVER ['HTTPS'] !== 'off' || $_SERVER ['SERVER_PORT'] == 443) ? "https://" : "http://";
		$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		if($new_url!=""){
			$url = $new_url;
		}
		$timestamp = time ();
		$nonceStr = $this->createNonceStr ();
		
		// 这里参数的顺序要按照 key 值 ASCII 码升序排序
		$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
		
		$signature = sha1 ( $string );
		
		$signPackage = array (
				"appId" => $this->appId,
				"nonceStr" => $nonceStr,
				"timestamp" => $timestamp,
				"url" => $url,
				"signature" => $signature,
				"rawString" => $string 
		);
		return $signPackage;
	}
	private function createNonceStr($length = 16) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for($i = 0; $i < $length; $i ++) {
			$str .= substr ( $chars, mt_rand ( 0, strlen ( $chars ) - 1 ), 1 );
		}
		return $str;
	}
	private function httpGet($url) {
		$curl = curl_init ();
		curl_setopt ( $curl, CURLOPT_URL, $url );
		// curl_setopt($curl, CURLOPT_HEADER, 1);//是否包含头信息
		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, false ); // 这个是重点。
		curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, FALSE );
		$data = curl_exec ( $curl );
		curl_close ( $curl );
		return $data;
	}
	private function get_php_file($filename) {
		return trim ( substr ( file_get_contents ( $filename ), 15 ) );
	}
	private function set_php_file($filename, $content) {
		$fp = fopen ( $filename, "w" );
		fwrite ( $fp, "<?php exit();?>" . $content );
		fclose ( $fp );
	}
}

