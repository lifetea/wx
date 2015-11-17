<?php
namespace Org\Util;
class JSSDK {
  private $appId;
  private $appSecret;

  public function __construct($appId, $appSecret) {
    $this->appId = "wxd2e82d66cc76016c";
    $this->appSecret = "7d2d31c0120ad6812a7403a87403825b";
  }

  public function getSignPackage() {
    $jsapiTicket = $this->getJsApiTicket();

    // 注意 URL 一定要动态获取，不能 hardcode.
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    $timestamp = time();
    $nonceStr = $this->createNonceStr();

    // 这里参数的顺序要按照 key 值 ASCII 码升序排序
    $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

    $signature = sha1($string);

    $signPackage = array(
      "appId"     => $this->appId,
      "nonceStr"  => $nonceStr,
      "timestamp" => $timestamp,
      "url"       => $url,
      "signature" => $signature,
      "rawString" => $string,
      "jsapi_ticket" => S('jsapi_ticket')
    );
    return $signPackage; 
  }

  private function createNonceStr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
  }

  public function getJsApiTicket() {
  	$ticket = S('jsapi_ticket');
  	if (!$ticket) {
  		$accessToken = $this->getAccessToken();
  		$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
  		$res = file_get_contents($url);
  		$res = json_decode($res, true);
  		$ticket = $res['ticket'];
  		// 注意：这里需要将获取到的token缓存起来（或写到数据库中）
  		// 不能频繁的访问https://api.weixin.qq.com/cgi-bin/token，每日有次数限制
  		// 通过此接口返回的token的有效期目前为2小时。令牌失效后，JS-SDK也就不能用了。
  		// 因此，这里将token值缓存1小时，比2小时小。缓存失效后，再从接口获取新的token，这样
  		// 就可以避免token失效。
  		// S()是ThinkPhp的缓存函数，如果使用的是不ThinkPhp框架，可以使用你的缓存函数，或使用数据库来保存。
  		S('jsapi_ticket', $ticket, 7000);
  	}
  	return $ticket;  	
  }
  
  public function getAccessToken() {
    $token = S('access_token');
    if (!$token) {
      $appId = $this->appId;
      $secret = $this->appSecret;
      $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appId}&secret={$secret}";
    	//var_dump($url);
      $res = file_get_contents($url);
    	$res = json_decode($res, true);
    	$token = $res['access_token'];
    	// 注意：这里需要将获取到的token缓存起来（或写到数据库中）
    	// 不能频繁的访问https://api.weixin.qq.com/cgi-bin/token，每日有次数限制
    	// 通过此接口返回的token的有效期目前为2小时。令牌失效后，JS-SDK也就不能用了。
    	// 因此，这里将token值缓存1小时，比2小时小。缓存失效后，再从接口获取新的token，这样
    	// 就可以避免token失效。
    	// S()是ThinkPhp的缓存函数，如果使用的是不ThinkPhp框架，可以使用你的缓存函数，或使用数据库来保存。
    	S('access_token', $token, 7000);
    }
    return $token;
  }

  public function getUserAccessToken($code) {
    //$token = S('access_token');
    if (!$token) {
      $appId = $this->appId;
      $secret = $this->appSecret;
      var_dump($code);
      $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$secret}&code={$code}&grant_type=authorization_code";
      $res = file_get_contents($url);
      $res = json_decode($res, true);
      $token = $res['access_token'];
      var_dump($res);
      //S('access_token', $token, 7000);
    }
    return $token;
  }  

  public function hello() {
  	return "test";
  }
  public function https_request($url,$data = null){
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
      curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
      if (!empty($data)){
          curl_setopt($curl, CURLOPT_POST, 1);
          curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
      }
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      $output = curl_exec($curl);
      curl_close($curl);
      return $output;
  }  
}

