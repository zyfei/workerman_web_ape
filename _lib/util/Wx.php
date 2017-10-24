<?php

namespace util;

class Wx {
	public static $wx_token = "weixin";
	public static $wx_appid = "wx2aa65e6ec6e7144a";
	public static $wx_appsecret = "48e3f8790ee62eb313a4c8015ec2addd";
	public static $wx_mch_id = "1459548502";
	/**
	 * 获取access_token
	 */
	public static function getAccess_token() {
		// 测试代码
		//return HttpClient::request_https("http://pingtai.tulongqipai.com/wx/wx/token");
		global $cache;
		var_export ( $cache->wx_access_token, true );
		var_export ( $cache->wx_access_token_time, true );
		if ($cache->wx_access_token != null && $cache->wx_access_token_time > time ()) {
			return $cache->wx_access_token;
		}
		$data = HttpClient::request_https ( "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . self::$wx_appid . "&secret=" . self::$wx_appsecret );
		$tokenArr = json_decode ( $data, true );
		$cache->wx_access_token = $tokenArr ["access_token"];
		// 设置缓存60分钟
		$cache->wx_access_token_time = time () + 60 * 60;
		return $cache->wx_access_token;
	}
	
	/**
	 * 获取JsApiTicket
	 */
	public static function getJsApiTicket() {
		global $wx_js_api_ticket;
		global $wx_js_api_ticket_time;
		if ($wx_js_api_ticket != null && $wx_js_api_ticket_time > time ()) {
			return $wx_js_api_ticket;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=" . Wx::getAccess_token ();
		$data = HttpClient::request_https ( $url );
		
		$tokenArr = json_decode ( $data, true );
		$wx_js_api_ticket = $tokenArr ["ticket"];
		// 设置缓存60分钟
		$wx_js_api_ticket_time = time () + 60 * 60;
		return $wx_js_api_ticket;
	}
	
	/**
	 * 网页授权，获取用户信息
	 */
	public static function getUserInfo() {
		$uri = $_SERVER ['REQUEST_URI'];
		$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . self::$wx_appid . "&redirect_uri=" . urlencode ( HOME . "wx/wx/getUserInfo" ) . "&response_type=code&scope=snsapi_userinfo&state=" . urlencode ( $uri ) . "#wechat_redirect";
		R ( $url );
	}
	
	/**
	 * 格式化参数格式化成url参数
	 */
	public static function ToUrlParams($signs) {
		$buff = "";
		foreach ( $signs as $k => $v ) {
			if ($k != "sign" && $v != "" && ! is_array ( $v )) {
				$buff .= $k . "=" . $v . "&";
			}
		}
		
		$buff = trim ( $buff, "&" );
		return $buff;
	}
	
	/**
	 * 生成签名
	 *
	 * @return 签名，本函数不覆盖sign成员变量，如要设置签名需要调用SetSign方法赋值
	 */
	public static function MakeSign($signs) {
		// 签名步骤一：按字典序排序参数
		ksort ( $signs );
		$string = self::ToUrlParams ( $signs );
		// 签名步骤二：在string后加入KEY
		$string = $string . "&key=qwertyuiopasdfghjklzxcvbnm123456";
		// 签名步骤三：MD5加密
		$string = md5 ( $string );
		// 签名步骤四：所有字符转为大写
		$result = strtoupper ( $string );
		return $result;
	}
	public static function get_sucai($type, $offset, $count) {
		// 欢迎关注途隆互娱 C92seon46ySg8fNTd-GQ60GbgB7O50evGmypkqCv95M
		// 即将上线，诚招代理商 C92seon46ySg8fNTd-GQ6-lTAicuFnXIHk1uEyBP4M8
		// 游戏暂未上线，敬请期待。C92seon46ySg8fNTd-GQ65dsDLc1QU-tpWdbDkJ8QIQ
		// 关于游戏 C92seon46ySg8fNTd-GQ6zmuTJCFuQkpYSApEPHI0oA
		// 猫妈不发怒你以为她是HelloKitty？ C92seon46ySg8fNTd-GQ6yhdzAsbVx935IAPdBI40i0
		// 官方声明 C92seon46ySg8fNTd-GQ62cCSUVcFv5TNI0_fCeBfok
		// 直接输入文字即可连接客服 C92seon46ySg8fNTd-GQ6whX-2Ze9A42asL9A1itRZc
		// "type":"video",
		$url = "https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=" . Wx::getAccess_token ();
		$data = '{
				   "type":"' . $type . '",
				   "offset":' . $offset . ',
				   "count":' . $count . '
				}';
		$data = HttpClient::request_https ( $url, $data );
		return $data;
	}
	
	// /**
	// * 获取带参数的二维码
	// */
	// public static function get_ewm_with_attr($id) {
	// $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=" . Wx::getAccess_token ();
	// $body = '{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": ' . $id . '}}}';
	// $data = httpF2 ( $url, $body );
	// $d_arr = json_decode ( $data, true );
	// if ($d_arr ["ticket"] != null) {
	// $url2 = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . urlencode ( $d_arr ["ticket"] );
	// $image_data = httpF ( $url2 );
	// $image = U_64 ( $image_data );
	// // echo $image;
	// return $image;
	// }
	// return false;
	// }
	
	/**
	 * 获取用户所有数据，包括unionid
	 * //
	 */
	// public static function getUserDate($openid) {
	// // alert(1);
	// $t = self::getAccess_token ();
	// $data = httpF ( "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$t&openid=$openid&lang=zh_CN" );
	// $data = json_decode ( $data, true );
	// return $data;
	// }
	// public static function getAllMedia() {
	// $token = self::getAccess_token ();
	// $data = httpF2 ( "https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=" . $token, '{ "type":"news", "offset":0,"count":10}' );
	// dd ( $data );
	// }
	
	/**
	 * 订单状态更新
	 *
	 * @param Order $order        	
	 */
	public static function send_redeem_template($openid, $first, $keyword1, $keyword2, $remark) {
		if ($keyword1 == "") {
			$keyword1 = "暂未绑定";
		}
		$token = Wx::getAccess_token ();
		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $token;
		$body = '
	{
	"touser":"' . $openid . '",
	"template_id":"G4UhQktryF_UNWDjn3zfziaub6Of16tTc0XZI_P9Dh8",
	"url":"' . HOME . 'shop/index/get_redeem_code",
	"topcolor":"#FF0000",
	"data":{
	"first": {
	"value":"' . $first . '",
	"color":"#173177"
	},
	"keyword1":{
	"value":"' . $keyword1 . '",
	"color":"#173177"
	},
	"keyword2":{
	"value":"' . $keyword2 . '",
	"color":"#173177"
	},
	"remark":{
	"value":"' . $remark . '",
	"color":"#173177"
	}
	}
	}
	';
		
		$data = HttpClient::request_https ( $url, $body );
	}
}
