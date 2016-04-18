<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/13
 * Time: 10:06
 */

namespace Home\Controller;
use Think\Controller;
use Home\Lib;
use Home\Model;
use Org\Util;
class H1Controller extends  Controller
{
    public function index(){
        $this->display();
    }
    public function hb(){
        $userId      = 1443;//cookie('userId');

        $player     = M("Player")->where("userid = {$userId}")->find();
        if(!empty($player)){
            $this->assign('playerStatus',1);
        }
        $count = M("Zu_log")->where("userid ={$userId}")->count();
        $have = (int)$count % 5;
        $need = 5 - $have;
//        if ($have == 0){
//            $zuList = array();
//        }else{
            $zuList   = D("playerView")->where("userid={$userId}")->limit($have)->select();
//        }

//        while(count($zuList)<5){
//            array_push($zuList,array());
//        }
        //moneylog
        $moneyLog = M("money_log")->where("uid = {$userId}")->limit(10)->select();

        //排行榜
        $topList = M("money_log")->field("SUM(money) as sum")->where("money > 0")->group("uid")->select();
        //获取礼品数
        $gift      = M("gift")->where("id = 18")->find();
        $giftcount = $gift["count"];


        $jssdk       = new Util\JSSDK();
        $signPackage = $jssdk->getSignPackage();
        $this->assign('data',$signPackage);
        $this->assign('have',$have);
        $this->assign('need',$need);
        $this->assign('userId',$userId);
        $this->assign('zuList',$zuList);
        $this->assign('giftCount',$giftcount);
        $this->assign('player',$player);
        $this->assign('moneyLog',$moneyLog);
        $this->assign('topList',$topList);

        $this->display();
    }

    public function voters(){
        $d         = I("get.");
        $userId    = $d["userId"];
        $hubId     = $d["hubId"];
        $userName  = trim($d["userName"]);
        $wchat     = $d["wchat"];
        $phone     = $d["phone"];
        $stamp     = date('Y-m-d');
        $arr       = array('username' => $userName,'phone' => $phone , 'wchat' => $wchat);
        $userRes   = M("User")->where("id = ${userId}")->save($arr);
        $playerRes = M("Player")->where("userid = {$userId}")->find();
        if (!$playerRes) {
            $playerArr = array('userid' => $userId, 'money' => 0,'status' => 0, 'zu' => 0);
            M("Player")->data($playerArr)->add();
            echo "1";//通知报名成功
        }else{
            echo "0";
        }

    }
    //助力
    public function zu(){
        $d         = I("get.");
        $userId    = $d["userId"];
        $fId       = $d["fId"];

        $stamp     = date('Y-m-d');
        $arr       = array('friend' => $userId,'userid' => $fId,'time' => $stamp);

        $zuRes = M("Zu_log")->where("friend = {$userId}")->find();
        if (!$zuRes) {
            M("Zu_log")->data($arr)->add();
            $countRes = M("Zu_log")->where("userid = {$fId}")->count();
            if((int)$countRes % 5 == 0){
                $playerRes = M("Player")->where("userid = $fId")->find();
                $data = array("zu"=>((int)$playerRes["zu"]+1));
                M("Player")->where("userid = $fId")->save($data);
            }
            echo "1";//通知报名成功
        }else{
            echo "0";
        }

    }
    //红包
    public function open(){
        $d         = I("get.");
        $userId    = $d["userId"];
        $playerRes = M("Player")->where("userid = {$userId}")->find();
        if((int)$playerRes["zu"]>0){
            $money=rand(40,130)/100;
            echo $money;
            $stamp     = date('Y-m-d');
            $data = array("uid"=>$userId,"money"=>$money,"time"=>$stamp);
            M("money_log")->data($data)->add();
            $player = M("Player")->where("userid = {$userId}")->find();
            $data = array("zu"=>((int)$player["zu"]-1),"money"=>($player["money"])+$money);
            M("Player")->where("userid = $userId")->save($data);
        }else{
            echo 0;
        }
    }

    //红包
    public function detail(){
        $d         = I("get.");
        $userId    = $d["userId"];
        $detail    = M("money_log")->where("uid = {$userId}")->select();
        var_dump($detail);
    }

    public  function withdraw(){
        $d         = I("get.");
        $userId    = $d["userId"];
        //获取礼品数
        $gift      = M("gift")->where("id = 18")->find();
        $count     = $gift["count"];
        //获取用户money
        $player    = M("Player")->where("userid = $userId")->find();
        if ((int)$count > 0 && ((float)$player["money"] >=5) ){
            //添加提现记录
            $stamp     = date('Y-m-d');
            $moneyData = array("money"=>-5,"uid"=>$userId,"time"=>$stamp);
            M("money_log")->data($moneyData)->add();
            //更新用户money
            M("Player")->where("userid = $userId")->save(array("money"=>((float)$player["money"]-5)));
            //更新礼物数量
            M("gift")->where("id = 18")->save(array("count"=>((int)$count-1)));
            echo 1;
        }else{
            if(((float)$player["money"] < 5)){
                echo -1;
            }else{
                echo 0;
            }
        }
    }
}