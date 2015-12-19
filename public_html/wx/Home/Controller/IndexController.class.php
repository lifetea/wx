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
      //$token = S('access_token');
      //$openId = "";
      //$result = M("User")->where("openid2 = '`".$openId."'")->find();
      $str = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd2e82d66cc76016c&redirect_uri=http://wx.vlegend.cn/oauth2FuWu?t=top&openId=".$openId."&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
      var_dump($str);
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
          $openId = I("get.openId");
          $cert = I("get.cert");
          //var_dump(I("get.t"));
          if($t == "top"){
      header("Location: http://wx.vlegend.cn/top?openId={$openId}");
          }elseif ($t == "sdgs") {
            header("Location: http://wx.vlegend.cn/sdgs/index.html?cert={$cert}&new={$new}");
          }elseif ($t == "gift") {
            header("Location: http://wx.vlegend.cn/gift");
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
    public function detail(){
      $id = I("get.id");
      $this->assign("id",$id);
      //var_dump($id);
      $this->display();
    }
    public function sdgs(){
      $userId = cookie('userId');
      $new = I("get.new");
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
      
      $this->assign("id",$userId);
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
      $openId = cookie("openId");
      if(!$openId){
        $openId =  I("get.openId");
        cookie("openId",$openId);
      }else{
        cookie("openId",$openId);
      }
      //var_dump($openId);
      //var_dump("Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd2e82d66cc76016c&redirect_uri=http://wx.vlegend.cn/oauth2FuWu?t=top&openId=".$openId."&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect");

      if (!$userId) {
        //跳转
        header("Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd2e82d66cc76016c&redirect_uri=http://wx.vlegend.cn/oauth2FuWu?t=top&openId={$openId}&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect");
      }else{
        //var_dump(!!$userId);
        //var_dump(!!$openId);
        //var_dump($openId);
        if(!!$userId && !!$openId){
          $result = M("User")->where("openid2 = \"".$openId."\"")->find();
          //var_dump($result);
          //var_dump(!$result);
          if(!$result){
            //

            $arr = array("openid2"=>$openId);
            $res = M("User")->where("id = {$userId}")->data($arr)->save();
            $credit = 5;
            $arr = array("score"=>$credit,"userid"=>$userId,"event"=>"qiandao");
            M("Log")->where("userid = {id}")->data($arr)->add();         
          }
        }        
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
      $list = D("ScoreView")->field('Sum(score) as sum,nickname,headimgurl')->group('user.id')->order('sum DESC')->limit(20)->select();
      //var_dump($list);
      $this->assign("list",$list);
      
      $this->display();
    }
    //礼品兑换
    public function gift(){
      $userId = cookie("userId");

      //跳转
      if (!$userId) {
        header("Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd2e82d66cc76016c&redirect_uri=http://wx.vlegend.cn/oauth2FuWu?t=gift&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect");
      }
      $gift   = M("gift")->order("id asc")->select();
      //var_dump($gift);
      if(!empty($userId)){
        $user   = D("ScoreView")->field("Sum(score) as sum")->where("userid = {$userId}")->find();
        $this->assign("user",$user);
      }
      //var_dump($user);
      $this->assign("id",$userId);
      
      $this->assign("gift",$gift);
      $this->display();
    }
    public function exchange(){
      $post = I("post.");
      //var_dump(!empty($post));
      if (!empty($post)) {
        $userId = $post["userid"];
        $giftId = $post["giftid"];
        $log  = M("Log")->field("Sum(score) as sum")->where("userid = {$userId}")->find();
        $gift = M("Gift")->where("id = {$giftId}")->find();
        $total = (int)$log["sum"];
        $need  = (int)$gift["credit"];
        $stamp  = $openId.date('Y-m');

        $exLog = M("Exchange")->where("userid = {$userId} and giftid = {$giftId} and stamp = \"{$stamp}\"")->find();
        if (!empty($exLog)) {
          $this->error("本月已兑换");
        }
        //var_dump($exLog);
        if($total > $need){
          $post["stamp"] = $stamp;
          $res = M("exchange")->data($post)->add();
          $arr = array("userid"=>$userId,"event"=>"duihuan","score"=>(-$need));
          $resLog = M("Log")->data($arr)->add();
          $gift["count"] = (int)$gift["count"] - 1;
          $giftLog = M("Gift")->where("id = {$giftId}")->data($gift)->save();
          $this->success("兑换成功");
        }else{
          $this->error("积分不足");
        }

        //var_dump($post);
        //var_dump($res);
      }
    }

    public function share(){
      
      $get = I("get.");
      
      if (!empty($get)) {
        $userId = $get["userid"];
        $stamp = date("Y-m-d");
        $get["stamp"] = $stamp;

        $shareLog = M("Share")->where("userid = {$userId} and  stamp = \"{$stamp}\"")->find();  
        if (empty($shareLog)) {
          M("Share")->data($get)->add();
          $arr = array("userid"=>$userId,"score"=>40,"event"=>"share");
          M("Log")->data($arr)->add();
        }        
        

      }
      
    }

}
