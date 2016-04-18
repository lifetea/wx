<?php
namespace Home\Lib;
use Org\Util;
class WeChat
{
   public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);

            switch ($RX_TYPE)
            {
                case "text":
                    $resultStr = $this->receiveText($postObj);
                    break;
                case "event":
                    $resultStr = $this->receiveEvent($postObj);
                    break;
                default:
                    $resultStr = "";
                    break;
            }
            echo $resultStr;
        }else {
            echo "";
            exit;
        }
    }

    private function receiveText($object)
    {
        $funcFlag = 0;
        // $contentStr = "你发送的内容为：".$object->Content;
        // $resultStr = $this->transmitText($object, $contentStr, $funcFlag);
        // return $resultStr;
        $contentStr     = trim($object->Content);
        if($contentStr == "时间" || $contentStr == "测试"){
            $contentStr = $object->FromUserName;
            $resultStr  = $this->transmitText($object, $contentStr, $funcFlag);
        }elseif($contentStr == "投票"){
            $this->bindId($object);
            $content[]  = array("Title" =>"快去抢红包吧",
                "Description" =>"车侣威擎 红包活动",
                "PicUrl" =>"http://wx.vlegend.cn/Public/tp/banner.jpg",
                "Url" =>"http://wx.vlegend.cn/index.php/Home/H1/hb");//?{$str}={$openId}
            $resultStr = $this->transmitNews($object, $content,$funcFlag);
        }elseif($contentStr == "红包"){
            $this->bindId($object);
            $content[]  = array("Title" =>"快去抢红包吧",
                "Description" =>"车侣威擎 红包活动",
                "PicUrl" =>"http://wx.vlegend.cn/Public/images/hongbao.png",
                "Url" =>"http://wx.vlegend.cn/index.php/Home/H1/hb");//?{$str}={$openId}
            $resultStr = $this->transmitNews($object, $content,$funcFlag);
        }else{
            //触发多客服模式
            $resultStr = $this->transmitService($object, $contentStr, $funcFlag);
        }
        return $resultStr;
    }

    private function bindId($object)
    {
        $openId     = $object->FromUserName;
        $sdk        = new Util\JSSDK();
        $res        = $sdk->getUserId($openId);
        $uniondId   = $res["unionid"];
        $sql        = "unionid ='$uniondId'";
        $arr        = array('openid2' =>"{$openId}");
        $hRes       = M("User")->where($sql)->save($arr);
    }
 
    private function unbindId($object)
    {
        $openId     = $object->FromUserName;
        $sdk        = new Util\JSSDK();
        $res        = $sdk->getUserId($openId);
        $uniondId   = trim($res["unionid"]);
        $sql        = "unionid ='$uniondId'";
        $arr        = array('openid2' =>"");
        $hRes       = M("User")->where($sql)->save($arr);
    }

    private function receiveEvent($object)
    {
        $contentStr = "";
        switch ($object->Event)
        {
            case "subscribe":
                //关注后发送的消息
                $contentStr = "回复 【投票】 你选轮毂 我就送!";
                //$contentStr ="OAuth2.0网页授权演示 <a href=\"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx85eea0cbf0d30d65&redirect_uri=http://wx.vlegend.cn&response_type=code&scope=snsapi_base&state=1&component_appid=wxef28dc4fa8885658#wechat_redirect\">点击这里体验</a>技术支持 车侣威擎";
                $this->bindId($object);
                //$contentStr ="OAuth2.0网页授权演示 <a href=\"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx85eea0cbf0d30d65&redirect_uri=http://wx.vlegend.cn/oauth2&response_type=code&scope=snsapi_base&state=1#wechat_redirect\">点击这里体验</a>技术支持 车侣威擎"; 
                        
                //$this->bindOpenId($object);

                $object["funcFlag"] = 1;
            case "unsubscribe":
                $this->unbindId($object);
                break;
            case "CLICK":
                switch ($object->EventKey)
                {
                    case "hongbao":
                        $contentStr ="OAuth2.0网页授权演示 <a href=\"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd2e82d66cc76016c&redirect_uri=http://wx.vlegend.cn/oauth2FuWu&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect\">点击这里体验</a>技术支持 车侣威擎"; 
                        break;
                    case "vote":
                        $contentStr[]  = array("Title" =>"点击进入", 
                            "Description" =>"车侣威擎 你选轮毂 我就送!", 
                            "PicUrl" =>"http://wx.vlegend.cn/Public/tp/banner.jpg", 
                            "Url" =>"http://wx.vlegend.cn/tp");//?{$str}={$openId}
                        break;
                    case "hb":
                        $contentStr[]  = array("Title" =>"快去抢红包吧",
                            "Description" =>"车侣威擎 红包活动",
                            "PicUrl" =>"http://wx.vlegend.cn/Public/images/hongbao.png",
                            "Url" =>"http://wx.vlegend.cn/index.php/Home/H1/hb");//?{$str}={$openId}
                        break;
                    case "qiandao":
                        //$str = "";
                        //foreach($object as $key => $value) {
                        //    $str = $str."$key => $value\n";
                        //}
                        //$sdk = new Util\JSSDK();
                        $openId = $object->FromUserName;
                        $stamp  = $openId.date('Y-m-d');
                        $sgin   = M("sgin");
                        $result = $sgin->where("stamp='{$stamp}'")->find();
                        $userResult = M("User")->where("openid2='{$openId}'")->find();
                        if(!!$userResult){
                            
                            if(!$result){
                                //$contentStr ="您已签到{$userResult}";
                                $id = $userResult["id"];
                                $credit = 50;
                                $arr = array("score"=>$credit,"userid"=>$id,"event"=>"qiandao");
                                M("Log")->where("userid = {id}")->data($arr)->add();
                                $arr = array("stamp"=>$stamp);
                                M("sgin")->data($arr)->add();
                                $contentStr ="签到成功获得{$credit}点积分"; 
                            }else{
                                $contentStr ="您已签到";
                            }                            
                        }else{
                            if(!$result){
                                $arr = array("stamp"=>$stamp);
                                M("sgin")->data($arr)->add();
                                $contentStr ="签到成功 <a href=\"http://wx.vlegend.cn/top?openId={$openId}\">点击领取50点积分</a>"; 
                            }else{
                                $contentStr ="您已签到";
                            }                            
                        }
                        $object["funcFlag"] = 1;
                        
                        
                        //cookie('test','111');
                        break;
                    case "zhunbei":

                        $contentStr ="准备新游戏中";
                        $object["funcFlag"] = 1;
                        break;                              
                    case "company":
                        $contentStr[] = array("Title" =>"公司简介", 
                        "Description" =>"车侣威擎提供移动互联网相关的产品及服务", 
                        "PicUrl" =>"http://discuz.comli.com/weixin/weather/icon/cartoon.jpg", 
                        "Url" =>"weixin://addfriend/pondbaystudio");
                        break;
                    case "violation":
                        $contentStr[] = array("Title" =>"公司简介", 
                        "Description" =>"车侣威擎提供移动互联网相关的产品及服务", 
                        "PicUrl" =>"http://discuz.comli.com/weixin/weather/icon/cartoon.jpg", 
                        "Url" =>"weixin://addfriend/pondbaystudio");
                        break;                        
                    case "刮伤":
                        $contentStr[] = array("Title" =>"公司简介", 
                        "Description" =>"车侣威擎提供移动互联网相关的产品及服务", 
                        "PicUrl" =>"http://t.cn/RUYqefc", 
                        "Url" =>"http://t.cn/RUYqFF7");
                        break;                        
                    default:
                        $contentStr[] = array("Title" =>"默认菜单回复", 
                        "Description" =>"您正在使用的是威擎网络科技有限公司的自定义菜单测试接口", 
                        "PicUrl" =>"http://t.cn/RUYqefc", 
                        "Url" =>"weixin://addfriend/pondbaystudio");
                        break;
                }
                break;
            default:
                break;      

        }
        if (is_array($contentStr)){
            $resultStr = $this->transmitNews($object, $contentStr);
        }else{
            $resultStr = $this->transmitText($object, $contentStr);
        }
        return $resultStr;
    }

    private function transmitText($object, $content, $funcFlag = 0)
    {
        $textTpl = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[text]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            <FuncFlag>%d</FuncFlag>
            </xml>";
           
        $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content, $funcFlag);
        return $resultStr;
    }

    //回复多客服消息
    private function transmitService($object, $content, $funcFlag = 0)
    {
        $textTpl = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[transfer_customer_service]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            <FuncFlag>%d</FuncFlag>
            </xml>";
        $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content, $funcFlag);
        return $resultStr;
    }
    


    private function transmitNews($object, $arr_item, $funcFlag = 0)
    {
        //首条标题28字，其他标题39字
        if(!is_array($arr_item))
            return;

        $itemTpl = "    <item>
            <Title><![CDATA[%s]]></Title>
            <Description><![CDATA[%s]]></Description>
            <PicUrl><![CDATA[%s]]></PicUrl>
            <Url><![CDATA[%s]]></Url>
            </item>";
        $item_str = "";
        foreach ($arr_item as $item)
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);

        $newsTpl = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[news]]></MsgType>
            <Content><![CDATA[]]></Content>
            <ArticleCount>%s</ArticleCount>
            <Articles>
            $item_str</Articles>
            <FuncFlag>%s</FuncFlag>
            </xml>";

        $resultStr = sprintf($newsTpl, $object->FromUserName, $object->ToUserName, time(), count($arr_item), $funcFlag);
        return $resultStr;
    }
}