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
          }elseif ($t == "best") {
            header("Location: http://wx.vlegend.cn/best");
          }elseif ($t == "ji"){
            header("Location: http://wx.vlegend.cn/ji?");
          }elseif ($t == "yd"){
            header("Location: http://wx.vlegend.cn/yd.html?");
          }elseif ($t == "dbj"){
            header("Location: http://wx.vlegend.cn/dbj.html?");
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
      
      
      if (!empty($userId)) {
        $best = M("Best")->where("userid= {$userId} and gameid = 1")->find();
        $this->assign("bestScore",$best["score"]);
      }
      
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

    //礼品兑换
    public function best(){
      $userId = cookie("userId");

      //跳转
      if (!$userId) {
        header("Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd2e82d66cc76016c&redirect_uri=http://wx.vlegend.cn/oauth2FuWu?t=best&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect");
      }
      //
      if (!empty($userId)) {
        $user          = M("User")->where("id= {$userId}")->find();
        $userBest      = M("Best")->where("userid= {$userId} and gameid = 1")->find();
        $bestScore     = $userBest["score"];
        $user["score"] = $bestScore;
        $count = M("best")->field("Count(id) as count")->where("score > \"{$bestScore}\"")->find();
        $user["count"] = $count["count"] + 1;
        //var_dump($user);
        $this->assign("user",$user);
      }

      $best = D("BestView")->where("gameid = 1")->order("score desc")->limit(20)->select();
      $this->assign("best",$best);
      //var_dump($best);
      $this->display();
    }

    public function addBest(){
      $get = I("get.");
      if (!empty($get)) {
        $userId = $get["userid"];
        $gameId = $get["gameid"];
        $bestLog = M("Best")->where("userid = {$userId} and  gameid = {$gameId}")->find();  
        if (!empty($bestLog)) {
          if((float)$bestLog["score"] < (float)$get["score"]){
            M("best")->where("userid = {$userId}")->save($get);
          }
        }else{
          M("best")->data($get)->add();
        }
      }
    }

    public function ji(){
      $userId   = I("get.id");
      $self   = I("get.self");
      if (!empty($userId)) {
        cookie("jid",$userId);
      }else{
        $userId = cookie("jid");
      }
      $friendId = cookie("userId");
      if (!$friendId) {
        header("Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd2e82d66cc76016c&redirect_uri=http://wx.vlegend.cn/oauth2FuWu?t=ji&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect");
      }
      if(empty($userId)){
        $userId = $friendId;
      }
      if(!empty($self)){
        $userId = $friendId;
      }
      if (!empty($userId)) {
        $user   = D("ScoreView")->field('Sum(score) as sum,nickname,headimgurl')->where("userid = {$userId}")->find();
        $stamp = date("Y-m-d");
        $list = D("JiView")->where("ji.userid = {$userId} and ji.stamp = \"{$stamp}\"")->select();
        $count = count($list);
        $this->assign("count",$count);
        $this->assign("list",$list);
        $this->assign("user",$user);
        $this->assign("userId",$userId);
      }

      if(!empty($friendId)){
        $friend = M("User")->where("id = $friendId")->find();
        $this->assign("friend",$friend);
        $this->assign("friendId",$friendId);
      }
      
      //var_dump($user);

      $jssdk = new Util\JSSDK();
      $signPackage = $jssdk->getSignPackage();
      $this->assign('data',$signPackage); 
      $this->display();
    }

    public function doJi(){
      $get   = I("get.");
      if (!empty($get)) {
        $stamp = date("Y-m-d");
        
        $userId = $get["userId"];
        $friendId = $get["friendId"];
        $jiLog = M("Ji")->where("userid = {$userId} and friendid = {$friendId} and  stamp = \"{$stamp}\"")->find();
        if (empty($jiLog)) {
          $arr = array();
          $arr["stamp"] = $stamp;
          $arr["userid"] = $userId;
          $arr["friendid"] = $friendId;
          $data = array("userid" => $userId,"score"=>10,"event"=>"dianzan");
          $res = M("Log")->data($data)->add();
          M("Ji")->data($arr)->add();
          echo "1";
        }
      }
    }

  public function yd(){
      $userId = cookie("userId");

      //跳转
      if (!$userId) {
        header("Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd2e82d66cc76016c&redirect_uri=http://wx.vlegend.cn/oauth2FuWu?t=yd&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect");
      }
      if(!empty($userId)){
        $user = M("User")->where("id = {$userId}")->find();
        
        $ydp = M("yd")->where("userid = {$userId} and stamp = 1")->select();
        $ydm = M("yd")->field("Sum(stamp) as sum")->where("userid = {$userId}")->find();
        $count = 3-count($ydp);
        $this->assign("user",$user);
        $this->assign("count",$count);
        $this->assign("enable",$ydm["sum"]);
      }
      $jssdk = new Util\JSSDK();
      $signPackage = $jssdk->getSignPackage();
      $this->assign('data',$signPackage);      
      $this->display();   
  }

  public function doYd(){
    $userId = cookie('userId');
    $score = I("get.score");
    if (!!$score && !!$userId) {
      $ydRes = M("yd")->where("userid = {$userId} and stamp = -1")->select();
      if (count($ydRes) <= 3) {
        $arrLog   = array("userid" => $userId,"score"=>$score,"event"=>"yd");
        $resLog   = M("Log")->data($arrLog)->add();
        $arrYd   = array("userid" => $userId,"stamp"=>-1);
        $resYd   = M("yd")->data($arrYd)->add();
      }

      //$result = $user->data($arr)->add();
      var_dump($ydRes);
    }
  }

  public function doYdShare(){
    $userId = I("get.userid");
    if (!empty($userId)) {
      $ydRes = M("yd")->where("userid = {$userId} and stamp = 1")->select();
      if (count($ydRes) < 3) {
        $arrYd   = array("userid" => $userId,"stamp"=>1);
        $resYd   = M("yd")->data($arrYd)->add();
      }
    }
  }


  public function dbj(){
      $userId = cookie("userId");

      //跳转
      if (!$userId) {
        header("Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd2e82d66cc76016c&redirect_uri=http://wx.vlegend.cn/oauth2FuWu?t=dbj&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect");
      }
      if(!empty($userId)){
        // $user = M("User")->where("id = {$userId}")->find();
        
        // $ydp = M("yd")->where("userid = {$userId} and stamp = 1")->select();
        // $ydm = M("yd")->field("Sum(stamp) as sum")->where("userid = {$userId}")->find();
        // $count = 3-count($ydp);
        // $this->assign("user",$user);
        // $this->assign("count",$count);
        // $this->assign("enable",$ydm["sum"]);
      }
      $jssdk = new Util\JSSDK();
      $signPackage = $jssdk->getSignPackage();
      $this->assign('data',$signPackage);      
      $this->display();   
  }


}
