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
   		$sdk = new Util\JSSDK();
   		$ticket  = $sdk->getJsApiTicket();
   		var_dump($ticket);
   		//$wechatObj = new Lib\WeChat();
   	}
}