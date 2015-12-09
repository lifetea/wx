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
    //订阅号认证
    public function cert()
    {
  		$wechatObj = new Lib\WeChat();
    		if (isset($_GET['echostr'])) {
    			$wechatObj->valid();
    		}else{
    			$wechatObj->responseMsg();
    	}
   	}
    //服务号认证
    public function certFuWu()
    {
      $fuWu = new Lib\FuWu();
        if (isset($_GET['echostr'])) {
          $fuWu->valid();
        }else{
          $fuWu->responseMsg();
      }
    }
    //订阅号菜单  	
   	public function menu(){
		  $jsonmenu  = C("menuJson");  
      $appId     = "wx85eea0cbf0d30d65";
      $appSecret = "7fd7e90c834f1d29c8bae9b484a9a72d";		
   		$sdk = new Util\JSSDK();
   		$access_token  = $sdk->getAccessToken();
      var_dump($access_token);
   		$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
		  $result = $sdk->https_request($url, $jsonmenu);
		  var_dump($result);
   	}
    //服务号菜单
    public function menuFuWu(){
      $jsonmenu  = C("menuJson");
      $appId     = "wxd2e82d66cc76016c";
      $appSecret = "7d2d31c0120ad6812a7403a87403825b";
      $sdk = new Util\JSSDKFuWu();
      $access_token  = $sdk->getAccessToken();
      var_dump($access_token);
      $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
      $result = $sdk->https_request($url, $jsonmenu);
      var_dump($result);
    }
    //订阅号oauth2 
    public function oauth2(){
      if (isset($_GET['code'])){
          //echo $_GET['code'];
          $code = $_GET['code'];
          $sdk = new Util\JSSDK();
          $res = $sdk->getUserAccessToken($code);
          $sdk->getUserInfo($res);
      }else{
          echo "NO CODE";
      }
    }
    //服务号oauth2 
    public function oauth2FuWu(){
      if (isset($_GET["code"])){
          $user = M("user");
          //echo $_GET["code"];
          $code = $_GET["code"];
          $sdk  = new Util\JSSDKFuWu();
          $res  = $sdk->getUserAccessToken($code);
          $arr  = $sdk->getUserInfo($res);
          $unionid = $arr["unionid"];
          $result = $user->where("unionid='{$unionid}'")->find();
          if (!$result) {
            $user->data($arr)->add();
          }
          //var_dump($result);
          //$data = array('' => , );
          
      }else{
          echo "NO CODE";
      }
    }     
    public function intro(){
      $id = I("get.id");
      $this->assign("id",$id);
      //var_dump($id);
      $this->display();
    }
    public function top(){
      $this->display();
    }      
}