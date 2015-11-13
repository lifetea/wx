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
		 $jsonmenu = '{
		      "button":[
		      {
		            "name":"天气预报",
		           "sub_button":[
		            {
		               "type":"click",
		               "name":"北京天气",
		               "key":"天气北京"
		            },
		            {
		               "type":"click",
		               "name":"深圳天气",
		               "key":"天气深圳"
		            },
		            {
		                "type":"view",
		                "name":"本地天气",
		                "url":"http://m.hao123.com/a/tianqi"
		            }]
		      

		       },
		       {
		           "name":"车侣威擎",
		           "sub_button":[
		            {
		               "type":"click",
		               "name":"公司简介",
		               "key":"company"
		            },
		            {
		               "type":"click",
		               "name":"趣味游戏",
		               "key":"游戏"
		            },
		            {
		                "type":"click",
		                "name":"讲个笑话",
		                "key":"笑话"
		            }]
		       

		       }]
		 }';  		
   		$sdk = new Util\JSSDK();
   		$access_token  = $sdk->getAccessToken();
   		var_dump($access_token);
   		$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
		$result = $sdk->https_request($url, $jsonmenu);
		var_dump($result);
   		//$wechatObj = new Lib\WeChat();
   	}
}