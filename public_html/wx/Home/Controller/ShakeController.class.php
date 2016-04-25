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
class ShakeController extends  Controller
{
    public function index(){
        $userId = cookie('userId');
        if (empty($userId)) {
            header('Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd2e82d66cc76016c&redirect_uri=http://wx.vlegend.cn/oauth2FuWu?t=shake&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect');
        }
//        $user = M("User")->where("id = ${userId}")->find();
          var_dump($userId);
//        $d         = I("get.");
//        $userId    = $d["userId"];
//        $hubId     = $d["hubId"];
//        $userName  = trim($d["userName"]);
//        $wchat     = $d["wchat"];
//        $phone     = $d["phone"];
//        $stamp     = date('Y-m-d');
//        $arr       = array('username' => $userName,'phone' => $phone , 'wchat' => $wchat);
//        $userRes   = M("User")->where("id = ${userId}")->save($arr);
//        $playerRes = M("Player")->where("userid = {$userId}")->find();
//        if (!$playerRes) {
//            $playerArr = array('userid' => $userId, 'money' => 0,'status' => 0, 'zu' => 2);
//            M("Player")->data($playerArr)->add();
//            echo "1";//通知报名成功
//        }else{
//            echo "0";
//        }

        $jssdk       = new Util\JSSDK();
        $signPackage = $jssdk->getSignPackage();
        $this->assign('data',$signPackage);
        $this->display();
    }
}