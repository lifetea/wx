<?php
namespace Home\Controller;

use Think\Controller;
use Home\Lib;
use Org\Util;

class IndexController extends Controller
{
    public function index()
    {
       $this->display();
   	}

   	public function test()
    {
       $this->display();
   	}
    public function cert()
    {
		
		$wechatObj = new wechatCallbackapiTest();
		if (isset($_GET['echostr'])) {
			$wechatObj->valid();
		}else{
			$wechatObj->responseMsg();
		}
   	}  	
   	public function menu(){
		$jsonmenu = C("menuJson");  		
   		$sdk = new Util\JSSDK();
   		$access_token  = $sdk->getAccessToken();
   		$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
		$result = $sdk->https_request($url, $jsonmenu);
		var_dump($result);
   	}
}