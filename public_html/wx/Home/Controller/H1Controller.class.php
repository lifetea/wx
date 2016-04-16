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

        $playRes     = M("Player")->where("userid = {$userId}")->find();
        if(!empty($playRes)){
            $this->assign('playerStatus',1);
        }
        $count = M("Zu_log")->where("userid ={$userId}")->count();
        $have = (int)$count % 5;
        $need = 5 - $have;
        $zuList   = D("playerView")->where("userid={$userId}")->select();
        while(count($zuList)<5){
            array_push($zuList,array());
        }
        $jssdk       = new Util\JSSDK();
        $signPackage = $jssdk->getSignPackage();
        $this->assign('data',$signPackage);
        $this->assign('have',$have);
        $this->assign('need',$need);
        $this->assign('userId',$userId);
        $this->assign('zuList',$zuList);

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

    public function zu(){
        $d         = I("get.");
        $userId    = $d["userId"];
        $fId       = $d["fId"];

        $stamp     = date('Y-m-d');
        $arr       = array('friend' => $userId,'userid' => $fId,'time' => $stamp);

        $zuRes = M("Zu_log")->where("friend = {$userId}")->find();
        if (!$zuRes) {
            M("Zu_log")->data($arr)->add();
//            $countRes = M("Zu_log")->where("userid = {$userId}")->count();
//            if((int)$countRes % 5 == 0){
//                M("Player")->where("userid = ")
//            }
            echo "1";//通知报名成功
        }else{
            echo "0";
        }

    }
}