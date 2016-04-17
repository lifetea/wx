<?php
/* 微信发放红包        */
/* V 1.0               */
/* by tiandi           */
/* www.tiandiyoyo.com  */
/* 2015.3.5            */

define("APIKEY","abcdefghijklmnopqrstuvwxyz123456");  //微信现金红包api key
define("PATHCERT","apiclient_cert.pem"); //cert存放位置,不放在网站下
define("PATHKEY","apiclient_key.pem"); //key存放位置,不放在网站下
define("PATHCA","rootca.pem"); //ca存放位置,不放在网站下

class  RedPacket{
	var $para;
	function RedPacket() 
	{
	}

	function __construct()
	{
	}

	function set_para($key,$value){
		$this->para[$key] = $value;
	}

	function create_noncestr( $length = 24 ) {  
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";  
		$str ="";  
		for ( $i = 0; $i < $length; $i++ )  {  
			$str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);   
		}  
		return $str;  
	}

	function check_sign_para(){
		if($this->para["nonce_str"] == null || 
			$this->para["mch_billno"] == null || 
			$this->para["mch_id"] == null || 
			$this->para["wxappid"] == null || 
			$this->para["nick_name"] == null || 
			$this->para["send_name"] == null ||
			$this->para["re_openid"] == null || 
			$this->para["total_amount"] == null || 
			$this->para["max_value"] == null || 
			$this->para["total_num"] == null || 
			$this->para["wishing"] == null || 
			$this->para["client_ip"] == null || 
			$this->para["act_name"] == null || 
			$this->para["remark"] == null || 
			$this->para["min_value"] == null
			)
		{
			return false;
		}
		return true;

	}

	function create_sign(){
		if($this->check_sign_para() == false) {
			echo "签名参数错误！";
		}
		ksort($this->para);
		$tempsign = "";
		foreach ($this->para as $k => $v){
			if (null != $v && "null" != $v && "sign" != $k) {
				$tempsign .= $k . "=" . $v . "&";
			}
		}
		$tempsign = substr($tempsign, 0, strlen($tempsign)-1); //去掉最后的&
		$tempsign .="&key=". APIKEY;  //拼接APIKEY

		return strtoupper(md5($tempsign));
	}

	function create_xml(){
		$this->set_para('sign', $this->create_sign());
		return $this->ArrayToXml($this->para);
	}

	function ArrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
        	 if (is_numeric($val))
        	 {
        	 	$xml.="<".$key.">".$val."</".$key.">"; 

        	 }
        	 else
        	 	$xml.="<".$key."><![CDATA[".$val."]]></".$key.">";  
        }
        $xml.= "</xml>";
        return $xml; 
    }

	function curl_post_ssl($url, $vars, $second=30)
	{
		$ch = curl_init();
		//超时时间
		curl_setopt($ch,CURLOPT_TIMEOUT,$second);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
		
		//以下两种方式需选择一种
		//第一种方法，cert 与 key 分别属于两个.pem文件
		curl_setopt($ch,CURLOPT_SSLCERT,PATHCERT);
 		curl_setopt($ch,CURLOPT_SSLKEY,PATHKEY);
 		curl_setopt($ch,CURLOPT_CAINFO,PATHCA);
	 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
	 
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
		$data = curl_exec($ch);
		if($data){
			curl_close($ch);
			return $data;
		}
		else { 
			$error = curl_errno($ch);
			curl_close($ch);
			return false;
		}
	}
}