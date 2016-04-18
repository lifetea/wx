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
    //测试
    public function test()
    {
      //$token = S('access_token');
      //$openId = "";
      //$result = M("User")->where("openid2 = '`".$openId."'")->find();
      // $str = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd2e82d66cc76016c&redirect_uri=http://wx.vlegend.cn/oauth2FuWu?t=top&openId=".$openId."&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
      // var_dump($str);
      //$this->display();
      
        $openId     = "oRv8fwXLvOdVoJk7a_BFaD_nMIwA";
        $sdk        = new Util\JSSDK();
        $res        = $sdk->getUserId($openId);
        $uniondId   = $res["unionid"];
        var_dump($uniondId);
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
          //var_dump(!$result);
          if (!$result) {
            $result = $user->data($arr)->add();
            $new = "true";
            // $userId = $result;
            // $logArr = array("userid" => $userId,"score"=>0,"event"=>"init");
            // M("Log")->data($arr)->add();
            cookie('userId',$result);
            session('userId',$result);
          }else{
            cookie('userId',$result["id"]);
            session('userId',$result["id"]);
            // $userId = $result["id"];
            // $logArr = array("userid" => $userId,"score"=>0,"event"=>"init");
            // M("Log")->data($arr)->add();            
          }

          $t = I("get.t");
          $openId = I("get.openId");
          $cert = I("get.cert");
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
          }elseif ($t == "cj"){
            header("Location: http://wx.vlegend.cn/cj.html");
          }elseif ($t == "bird"){
            header("Location: http://wx.vlegend.cn/bird.html");
          }elseif ($t == "tp"){
            header("Location: http://wx.vlegend.cn/tp.html");
          }elseif($t == "hb"){
              header("Location: http://wx.vlegend.cn/index.php/Home/H1/hb.html");
          }
          
          $userId = cookie("userId");
          //var_dump($userId);
          //$data = array('' => , );
      }else{
          echo "NO CODE";
      }
    }
    public function bind(){
      $openId = I("get.openId");
      $bind   = M("bind");
      $arr    = array('unionid' => $openId);
      $bind->data($arr)->add();
      
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
        $stamp = date("Y-m-d H:i:s");
        $arr = array("userid" => $userId,"score"=>$score,"event"=>"game","stamp"=>$stamp);
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
            $credit = 50;
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
    //检测红包兑换
    public function checkHB(){
      $userId = cookie("userId");
      $stamp  = $openId.date('Y-m-d');
      $exLog = M("Exchange")->where("userid = {$userId} and stamp = \"{$stamp}\"")->find();
      if (!empty($exLog)) {
        echo "0";
      }else{
        echo "1";
      }
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
        if((int)($gift["count"]) <= 0){
          $this->error("奖品已经被兑换完了");
        }
        if($giftId == 7 || $giftId == 18 || $giftId == 12){
          $stamp  = $openId.date('Y-m-d');
          $exLog = M("Exchange")->where("userid = {$userId} and stamp = \"{$stamp}\"")->find();
          if (!empty($exLog)) {
            $this->error("当天只能兑换一种红包，您已经兑换");
          }
        }else{
          $exLog = M("Exchange")->where("userid = {$userId} and giftid = {$giftId} and stamp = \"{$stamp}\"")->find();
          if (!empty($exLog)) {
            $this->error("本月已兑换");
          }
        }
        

        //var_dump($exLog);
        if($total > $need){
          $post["stamp"] = $stamp;
          $post["date"] = date("Y-m-d H:i:s");
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
        if (empty($user["sum"])) {
          $user = M("User")->where("id = {$userId}")->find();
          $user["sum"] = 0;
        }
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
          $data = array("userid" => $userId,"score"=>50,"event"=>"dianzan");
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

  public function bird(){
      $userId = cookie("userId");

      if (!$userId) {
        header("Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd2e82d66cc76016c&redirect_uri=http://wx.vlegend.cn/oauth2FuWu?t=bird&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect");
      }
      if(!empty($userId)){
        
        $this->assign('userId',$userId);     
      }
      $jssdk = new Util\JSSDK();
      $signPackage = $jssdk->getSignPackage();
      $this->assign('data',$signPackage);      
      $this->display();  
  }
  public function cj(){
    $userId = cookie("userId");
      //跳转
    if (!$userId) {
      header("Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd2e82d66cc76016c&redirect_uri=http://wx.vlegend.cn/oauth2FuWu?t=cj&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect");
    }
    if(!empty($userId)){
      session('userId',$userId);
      //var_dump(session('userId'));
      $user = D("ScoreView")->field('Sum(score) as sum,nickname,headimgurl')->where("userid = {$userId}")->find();
      if (!empty($user["sum"])) {
        # code...
      }else{
        $user = M("User")->where("id = {$userId}")->find();
        $user["sum"] = 0;
      }
      $cList  = D("CjView")->field('id,nickname,dsc')->order('id DESC')->limit(10)->select();
      $this->assign("list",$cList);
      $this->assign("user",$user);
      $jssdk = new Util\JSSDK();
      $signPackage = $jssdk->getSignPackage();
      $this->assign('data',$signPackage);         
      $this->display();
    }
  }
  public function doCj(){
    // 【数据库处理】    数据 验证（如 一周 一次 抽奖  等  ）  --  登录验证  -- 其他验证等 。    
    //判断 成功 执行  下方代码 
    $userId = session('userId');

    if (!empty($userId)) {
      $user   = D("ScoreView")->field('Sum(score) as sum,nickname,headimgurl')->where("userid = {$userId}")->find();
      

      if ($user["sum"] >1000) {
        $logArr = array("userid" => $userId,"score"=>-1000,"event"=>"choujiang");
        M("Log")->data($logArr)->add();
        $prize_arr = array(
            // 序列                                              prize中奖数 10方格                             概率v 相加 100       code中奖数字 -- 对应积分/礼品
            '0' => array('id' => 1, 'prize' => array(1,6),'v'=>35, "code" => array(4=>8,7=>8)),   // 再接再厉
            '1' => array('id' => 2, 'prize' => 2,'v'=>0.05, "code" => array(2=>0)),  // 轮毂 0.05
            '2' => array('id' => 3, 'prize' => 0,'v'=>0, "code" => array(0=>0)),   // 两万元 0
            '3' => array('id' => 4, 'prize' => 4,'v'=>4, "code" => array(1=>0)),   // 大礼包 4
            '4' => array('id' => 5, 'prize' => 7,'v'=>20, "code" => array(6=>0)), // 500积分  20
            '5' => array('id' => 6, 'prize' => 5,'v'=>0.95, "code" => array(5=>0)), //  十元现金 0.95
            '6' => array('id' => 7, 'prize' => 8,'v'=>15, "code" => array(8=>0)), // 1000积分 15
            '7' => array('id' => 8, 'prize' => 3,'v'=>25, "code" => array(3=>0)), // 200积分 25
        );
         
        foreach ($prize_arr as $key => $val)
        {
            if($val['v']>0)
            {
                $arr[$val['id']] = $val['v'];
            }
        }

        $rid = $this->getRand($arr); //根据概率获取奖项id

        $res = $prize_arr[$rid-1]; //中奖项
        $prize = $res["prize"];
        if(is_array($prize))
        {
            $i = mt_rand(0, count($prize) - 1);
            $return = $prize[$i];
        }else{
            $return = $prize;
        }

        if ($return != 1 && $return != 6) {
          $stamp = date("Y-m-d H:i:s");
          $rllArr = array("userid" => $userId,"prize"=>$return,"date"=>$stamp);
          $res = M("Roll")->data($rllArr)->add();      
        }
        if ($return == 7) {
          $logArr = array("userid" => $userId,"score"=>500,"event"=>"choujiang");
          M("Log")->data($logArr)->add();           
        }

        if ($return == 8) {
          $logArr = array("userid" => $userId,"score"=>1000,"event"=>"choujiang");
          M("Log")->data($logArr)->add();           
        }
        if ($return == 3) {
          $logArr = array("userid" => $userId,"score"=>200,"event"=>"choujiang");
          M("Log")->data($logArr)->add();           
        } 


        // 【数据库处理】  ---  用户  奖品  
        $cr   = M("Log")->field('Sum(score) as sum')->where("userid = {$userId}")->find();
        $this->output_data(array("prize" => $return,"credit"=>$cr["sum"]));        
      }else{
        $this->output_data(array("prize" => -1));
      }
    }
  }

  private function getRand($proArr){
      $result = '';
      //概率数组的总概率精度
      $proSum = array_sum($proArr);
      //概率数组循环
      foreach ($proArr as $key => $proCur) {
          $randNum = mt_rand(1, $proSum);
          if ($randNum <= $proCur) {
              $result = $key;
              break;
          } else {
              $proSum -= $proCur;
          }
      }
      unset ($proArr);
      return $result;
  }

  private function output_data($datas, $extend_data = array()) {
    $data = array();
    $data['code'] = 200;

    if(!empty($extend_data)) {
        $data = array_merge($data, $extend_data);
    }
    $data['datas'] = $datas;
    if(!empty($_GET['callback'])) {
        echo $_GET['callback'].'('.json_encode($data).')';die;
    } else {
        echo json_encode($data);die;
    }
  }
  public function checkLast(){
    $userId = I("get.id");
    if (!empty($userId)) {
      $res = M("User")->field("lasttime")->where("id={$userId}")->find();
      $curr = date().time();
      $last = (int)$res["lasttime"];
      if (($curr-$last)>260000) {
        $arr  = array('lasttime' => $curr, );
        M("User")->where("id={$userId}")->save($arr);
        echo "1";
      }else{
        echo "0";
      }
    }
  }

  public function restHB(){
    $userId = session('userId');
    if (!empty($userId) && $userId == 1027) {
      $arr1 = array("count" => 30);
      M("Gift")->where("id=7")->save($arr1);
      $arr2 = array("count" => 10);
      M("Gift")->where("id=12")->save($arr2);
      $arr3 = array("count" => 5);
      M("Gift")->where("id=18")->save($arr3);
    }
  }

  public function tp(){
    $userId = cookie("userId");
    $openId = I("get.openId");
    if (!!$openId) {
      cookie("openId",$openId,7200000);
    }
      //跳转
    if (!$userId) {
      header("Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd2e82d66cc76016c&redirect_uri=http://wx.vlegend.cn/oauth2FuWu?t=tp&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect");
    }
    if (!empty($userId)) {
       //投票状态
      $votersRes        = M("Voters")->where("userid = {$userId}")->find();
      $votersStatus     =  !!($votersRes);
      //投票者
      $count            = M("Voters")->field("count(*) as count")->where("1=1")->find();
      $visitCount       = (int)S('visit') + 1;
      S('visit',$visitCount,720000);
      //投票数
      $voteCount        = M("Vote")->field("Count(*) as count")->where("1=1")->find();
      //关注状态
      $uRes        = M("User")->where("id = {$userId}")->find();
      if (!!$uRes && !!$uRes["openid2"]) {
        $this->assign("subscribe","1");
      }

      $jssdk   = new Util\JSSDK();
      $signPackage = $jssdk->getSignPackage();
      $this->assign('data',$signPackage); 
      //排行榜
      $topList = D("VotersView")->where("1=1")->group("Vote.voterid")->order("Count(Vote.voterid) DESC")->limit(30)->select();
      $this->assign("topList",$topList);

      //个人页
      $vId = I("get.id");
      if (!!$vId) {
        $userList    = D("VotersView")->where("Vote.voterid = {$vId}")->find();
        $this->assign("vId",$vId);
      }else{
        $userList    = D("VotersView")->where("Vote.voterid = {$userId}")->find();
      }      
      if (!!$userList) {
        $this->assign("userList",$userList);
      }
      
      $this->assign("count",$count["count"]);
      $this->assign("visitCount",$visitCount);
      $this->assign("voteCount",$voteCount["count"]);
      $this->assign("userId",$userId);
      $this->assign("votersStatus",$votersStatus);
    }
    $hubs =  M("hub")->where("1=1")->select();
    $this->assign("hubs",$hubs);
    $this->display();
  }

  public function voters(){
    $d         = I("get.");
    $userId    = $d["userId"];
    $hubId     = $d["hubId"];
    $userName  = trim($d["userName"]);
    $addr      = $d["address"];
    $phone     = $d["phone"];
    $stamp     = date('Y-m-d');
    $arr       = array('username' => $userName,'phone' => $phone , 'address' => $addr);
    $userRes   = M("User")->where("id = ${userId}")->save($arr);
    $votersRes = M("Voters")->where("userid = {$userId}")->find();
    if (!$votersRes) {
      $votersArr = array('userid' => $userId, 'hubid' => $hubId);
      M("Voters")->data($votersArr)->add();
      $arrV       = array('hubid' =>$hubId , 'stamp'=>$stamp ,'voterid'=> $userId,'userid'=>$userId);
      M("Vote")->data($arrV)->add();      
      echo "1";//通知报名成功
    }
    echo "0";
  }

  public function getHub(){
    $id         = trim(I("get.id"));
    $topList = D("VotersView")->where("Vote.hubid = {$id}")->group("User.id")->order("Count(Vote.voterid) DESC")->limit(6)->select();
    $this->ajaxReturn (json_encode($topList),'JSON');
  }

  public function vote(){
    $d         = I("get.");
    $hubId     = $d["hubid"];
    $voterId   = $d["voterid"];
    $temp      = session('userId');
    if (!!$temp) {
      $userId  = session('userId');
    }else{
      $userId  = $d["userid"];
    }
    $stamp     = date('Y-m-d');
    $vRes      = M("Vote")->where("userid = {$userId} and stamp = \"{$stamp}\"")->find();
    //var_dump($vRes);
    if (!!$vRes) {
      echo "2";  
    }else{
      $arr       = array('hubid' =>$hubId , 'stamp'=>$stamp ,'voterid'=> $voterId,'userid'=>$userId);
      M("Vote")->data($arr)->add();      
      echo "1";
    }
  }


  public function searchVoter(){
    $userId         = I("get.id");
    $hRes = D("HubView")->where("Voters.userid like '".$userId."%'")->limit(8)->select();
    // var_dump($hRes);
    $this->ajaxReturn (json_encode($hRes),'JSON');
  }

  public function getVoter(){
    $hubId         = I("get.hubid");
    $userId        = I("get.userId");
    $vRes          = D("VotersView")->where("Vote.hubid ={$hubId} and Vote.voterid = {$userId}")->find();
    //var_dump($vRes);
    $this->ajaxReturn (json_encode($vRes),'JSON');
  }


  public function votersTop(){
    $topList = D("VotersView")->where("1=1")->group("Vote.voterid")->select();
    var_dump($topList);
  }
}
