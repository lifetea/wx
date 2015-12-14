<?php
namespace Home\Controller;

use Think\Controller;
use Home\Lib;
use Home\Model;
use Org\Util;

class IndexController extends Controller
{
    public function index()
    {
       $this->display();
   	}

   	public function test()
    {
      $token = S('access_token');
      var_dump($token);
      //$this->display();
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
          $new    = "";
          $result = $user->where("unionid='{$unionid}'")->find();
          if (!$result) {
            $result = $user->data($arr)->add();
            $new = "true";
            cookie('userId',$result);
          }else{
            cookie('userId',$result["id"]);
          }
          $t = I("get.t");
          $cert = I("get.cert");
          //var_dump(I("get.t"));
          if($t == "top"){
            header('Location: http://wx.vlegend.cn/top');
          }elseif ($t == "sdgs") {
            header("Location: http://wx.vlegend.cn/sdgs/index.html?cert={$cert}&new={$new}");
          }
          
          $userId = cookie("userId");
          //var_dump($userId);
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
    public function sdgs(){
      $userId = cookie('userId');
      $new = I("get.new");
      //var_dump($userId);
      //var_dump("haha");
      if (!$userId) {
        header('Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd2e82d66cc76016c&redirect_uri=http://wx.vlegend.cn/oauth2FuWu?t=sdgs&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect');
      }
      if(!!$new){
        header("Location: http://wx.vlegend.cn/erwei.html");
      }

      //$userResult = M("User")->where("id = {$userId}")->find();
      //$openId = $userResult["openid"];
      $jssdk = new Util\JSSDK();
      //$result = $jssdk->getUserList($openId);
      //var_dump($result);
      //$userToken = $res['access_token'];
      //$openId = $res['openid'];
      $signPackage = $jssdk->getSignPackage();
      //echo $jssdk->getJsApiTicket();
      //echo $jssdk->hello();
      //var_dump($signPackage);
      $this->assign('data',$signPackage);
      $this->display();
    }
    public function addScore(){
      $userId = cookie('userId');
      $score = I("get.score");
      if (!!$score && !!$userId) {
        //var_dump($score);
        $arr = array("userid" => $userId,"score"=>$score,"event"=>"game");
        $res = M("Log")->data($arr)->add();
        //$result = $user->data($arr)->add();
        //var_dump($score);
      }
      
    }
    //排行榜
    public function top(){
      $userId = cookie("userId");
      $openId = I("get.openId");
      if(!!$userId && !!$openId){
        $result = M("User")->where("openid2 = '{$openId}'")->find();
        if(!$result){
          $arr = array("openid2"=>$openId);
          $res = M("User")->where("id = {$userId}")->data($arr)->save();
          $credit = 5;
          $arr = array("score"=>$credit,"userid"=>$userId,"event"=>"qiandao");
          M("Log")->where("userid = {id}")->data($arr)->add();         
        }
      }
      if (!$userId) {
        //跳转
        header("Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd2e82d66cc76016c&redirect_uri=http://wx.vlegend.cn/oauth2FuWu?t=top&openId={$openId}&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect");
      }else{
        //$Model = new Think\Model() // 实例化一个model对象 没有对应任何数据表
        //$Model->query("select * from think_user where status=1");
        $user   = D("ScoreView")->field('Sum(score) as sum,nickname,headimgurl')->where("userid = {$userId}")->find();
        //var_dump($user);
        if (!!$user["nickname"]) {
          $score = $user["sum"];
          $count = D("ScoreView")->field("id")->group('userid')->having("Sum(score) > {$score}")->select();
          $user["count"] = count($count)+1;
        }else{
          $user = M("User")->where("id = {$userId}")->find();
          $count = M("User")->field("Count(id) as count")->find();
          $user["count"] = $count["count"];
          $user["sum"] = 0;
          //var_dump($count);
        }


        $this->assign("user",$user);
      }
      //var_dump($user);
      $list = D("ScoreView")->field('Sum(score) as sum,nickname,headimgurl')->group('user.id')->order('sum DESC')->limit(15)->select();
      //var_dump($list);
      $this->assign("list",$list);
      
      $this->display();
    }
    //礼品兑换
    public function gift(){
      $userId = cookie("userId");

      if (!$userId) {
        //跳转
        header("Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd2e82d66cc76016c&redirect_uri=http://wx.vlegend.cn/oauth2FuWu?t=top&openId={$openId}&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect");
      }
      $this->display();
    }
}