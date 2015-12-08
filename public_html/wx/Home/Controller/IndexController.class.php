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
		
		$wechatObj = new Lib\WeChat();
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
      var_dump($access_token);
   		$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
		  $result = $sdk->https_request($url, $jsonmenu);
		  var_dump($result);
   	}
    public function oauth2(){
      if (isset($_GET['code'])){
          echo $_GET['code'];
          $code = $_GET['code'];
          $sdk = new Util\JSSDK();
          $res = $sdk->getUserAccessToken($code);
          $sdk->getUserInfo($res);
      }else{
          echo "NO CODE";
      }
    }
    public function intro(){
      $id = I('get.id');
      $this->assign('id',$id);
      //var_dump($id);
      $this->display();
    }    
}